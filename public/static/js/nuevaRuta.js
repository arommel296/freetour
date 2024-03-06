$(function () {
    var nuevaRuta = $("#nuevaRuta");

    $(".content").append(nuevaRuta);

    $("#tabs").tabs({
        beforeLoad: function (event, ui) {
            ui.jqXHR.fail(function () {
                ui.panel.html(
                    "No se ha podido cargar esta pestaña");
            });
        }
    });

    // Inicialización devariables necesarias
    var $tabs = $("#tabs").tabs(); // Inicializa los tabs con jquery
    var nTabs = $("#tabs > ul > li").length; // Número de tabs
    var atras = $(".atras"); // Botón de tab anterior
    atras.hide();
    var siguiente = $(".siguiente"); // Botón de tab siguiente
    var coordInicio = $("#coord_inicio"); // Input de las coordenadas del punto de inicio
    console.log(nTabs);
    var active = $tabs.tabs("option", "active");

    $("#tabs > ul > li").on("click", function () {
        active = $tabs.tabs("option", "active");
        if (active + 1 == nTabs) {
            siguiente.hide();
            atras.show();
        } else {
            if (active == 0) {
                atras.hide();
                siguiente.show();
            } else {
                atras.show();
                siguiente.show();
            }
        }

        // $tabs.tabs("option", "active", this);
        console.log(active);
    })



    $('#descripcion').jqxEditor({
        height: '220px',
        width: '100%'
    })


    $('#inicio').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        onSelect: function (selectedDate) {
            $('#fin').datepicker('option', 'minDate', selectedDate);
            $('#inicioPeriodo').datepicker('option', 'minDate', selectedDate);
        }
    });

    $('#fin').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        onSelect: function (selectedDate) {
            $('#inicio').datepicker('option', 'maxDate', selectedDate);
            $('#finPeriodo').datepicker('option', 'maxDate', selectedDate);
            $('#inicioPeriodo').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $('#inicioPeriodo').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        onSelect: function (selectedDate) {
            $('#finPeriodo').datepicker('option', 'minDate', selectedDate);
        }
    });

    $('#finPeriodo').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        onSelect: function (selectedDate) {
            $('#inicioPeriodo').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $('.input-images').imageUploader({
        label: 'Arrastra imágenes o haz click para buscarlas en tu almacenamiento',
        extensions: ['.jpg', '.jpeg', '.png'],
        imagesInputName: 'Foto',
        maxSize: 2 * 1024 * 1024,
        maxFiles: 1,
        preloaded: []
    });


    //Botones de siguiente y atrás de los tabs, pero no va a hacer falta:
    $(".siguiente").click(function (e) {
        e.preventDefault();
        // Obtiene el índice del tab activo
        // var active = $tabs.tabs("option", "active");
        active = $tabs.tabs("option", "active");
        console.log(active);

        if ((active + 1) == nTabs - 1) {
            // Oculta el botón de siguiente
            $(this).hide();
        }
        if (active == 0) {
            // Muestra el botón de atrás
            atras.show();
        }
        $tabs.tabs("option", "active", active + 1);

    });

    $(".atras").click(function (e) {
        e.preventDefault();
        // Obtiene el índice del tab activo
        // var active = $tabs.tabs("option", "active");
        active = $tabs.tabs("option", "active");
        console.log(active);
        if (active - 1 == 0) {
            // Oculta el botón de atrás
            $(this).hide();
        }
        if ((active - 1) < nTabs) {
            // Muestra el botón de siguiente
            siguiente.show();
        }
        $tabs.tabs("option", "active", active - 1);
    });

    $("#mapaDialog").dialog({
        autoOpen: false,
        modal: true,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "blind",
            duration: 500
        },
        width: 700,
        height: 650,
        buttons: {
            Volver: function () {
                $("#mapaDialog").dialog("close");
            }
        },
    });

    var coordUsable = $("#coordUsable")
    coordUsable.on("click", function () {
        creaMapa();
    })
    // coordInicio.on("click", function () {
    //     creaMapa();
    // })

    function creaMapa() {
        $("#mapaDialog").dialog("open");

        // Inicializa el mapa de Leaflet
        // L es una variable global de Leaflet
        // map() es una función que crea un mapa en el elemento con el id 'mapid'
        var mapa = L.map('mapid').setView([37.77, -3.79], 15);

        // Añade un marcador al mapa por defecto
        if (coordInicio.val() != "") {
            var coordenadas = coordInicio.val().split(", ");
            var lat = parseFloat(coordenadas[0]);
            var lon = parseFloat(coordenadas[1]);
            mapa.setView([lat, lon], 17);
            var marker = L.marker([lat, lon]).addTo(mapa);
            console.log("punto puesto por defecto");
            coordUsable.text("Punto establecido en el mapa ✔️");
        } else {
            coordUsable.text("Click para desplegar el mapa");
            var marker = L.marker([37.77, -3.79]).addTo(mapa);
        }


        L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', {
            minZoom: 0,
            maxZoom: 22,
            accessToken: 'rSpYFAsrP0xh9UQNavI4LCwxGfaAzB4OVL9PGe4rABoU6l1awbhA9ORdSGE8m515'
        }).addTo(mapa);

        mapa.on('click', function (e) {
            // Define las coordenadas del marker donde se ha hecho clic
            marker.setLatLng(e.latlng);

            console.log("punto puesto clicando");
            // Pone las coordenadas del marker en el input
            coordInicio.val(e.latlng.lat + ', ' + e.latlng.lng);
            coordUsable.text("Punto establecido en el mapa ✔️");
            console.log(coordInicio.val());
        });

        function buscarPorCiudad(event) {
            event.preventDefault();
            // Obtiene la ciudad del input
            var sitio = $("#sitio").val();

            // Utiliza la API de geocodificación de OpenStreetMap Nominatim para obtener las coordenadas del sitio de la ciudad
            var nominatimURL = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(sitio);

            $.ajax({
                url: nominatimURL,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        // Obtiene las coordenadas de la ciudad
                        var lat = data[0].lat;
                        var lon = data[0].lon;

                        // Centra el mapa en las coordenadas de la ciudad y coloca un marcador
                        mapa.setView([lat, lon], 17);
                        // Pone las coordenadas del marker en el input
                        marker.setLatLng([lat, lon]);
                        console.log("punto puesto buscando por nombre");
                        coordInicio.val(lat + ', ' + lon);
                        coordUsable.text("Punto establecido en el mapa ✔️");
                        console.log(coordInicio.val());
                    } else {
                        alert("No se encontró la ciudad.");
                    }
                },
                error: function () {
                    alert("Error al buscar la ciudad. Por favor, inténtalo de nuevo.");
                }
            });

        }

        var buscar = $("#buscarLugar");

        buscar.on("click", buscarPorCiudad);

    }

    // let tablaTours = new DataTable($("#tablaTours"));
    tablaTours = $('#tablaTours').DataTable({
        autoWidth: false,
        columns: [{
                width: '20%'
            },
            {
                width: '20%'
            },
            {
                width: '20%'
            },
            {
                width: '20%'
            },
            {
                width: '20%'
            },
            {
                width: '20%'
            }
        ],
        scrollY: 150
    });

    $('#add').click(function (ev) {
        ev.preventDefault();
        var inicioPeriodo = $('#inicioPeriodo').val();
        var finPeriodo = $('#finPeriodo').val();
        var hora = $('#hora').val();
        var dias = $('input[name="options-outlined"]:checked').map(function () {
            return this.id;
        }).get().join(', ');
        var guia = $('#guia').val();

        var row = $('#tablaTours').data('row');
        if (row) {
            // Actualiza la fila que se está editando
            row.data([
                inicioPeriodo,
                finPeriodo,
                hora,
                dias,
                guia,
                '<button class="edit btn btn-primary">Editar</button><button class="delete btn btn-primary">Eliminar</button>'
            ]).draw();

            // Borra la fila que se está editando
            $('#tablaTours').removeData('row');
        } else {
            // Añade una nueva fila a la tabla
            tablaTours.row.add([
                inicioPeriodo,
                finPeriodo,
                hora,
                dias,
                guia,
                '<button class="edit btn btn-primary">Editar</button><button class="delete btn btn-primary">Eliminar</button>'
            ]).draw();
        }

        $('#inicioPeriodo').val("");
        $('#finPeriodo').val("");
        $('#hora').val("");
        $('#guia').val("");
        $('input[type="checkbox"]').prop('checked', false);
    });

    tablaTours.on('click', '.edit', function (ev) {
        ev.preventDefault();
        var row = tablaTours.row($(this).parents('tr'));
        var data = row.data();

        // Carga los valores de la fila en el formulario
        $('#inicioPeriodo').val(data[0]);
        $('#finPeriodo').val(data[1]);
        $('#hora').val(data[2]);

        // Desmarca todos los checkboxes
        $('input[name="options-outlined"]').prop('checked', false);

        // Marca los checkboxes que corresponden a los días de la fila
        var dias = data[3].split(', ');
        for (var i = 0; i < dias.length; i++) {
            $('#' + dias[i]).prop('checked', true);
        }

        $('#guia').val(data[4]);

        row.remove().draw();
        // Guarda la fila que se está editando
        tablaTours.data('row', row);
    });

    tablaTours.on('click', '.delete', function (ev) {
        ev.preventDefault();
        var row = tablaTours.row($(this).parents('tr'));

        $('#inicioPeriodo').val("");
        $('#finPeriodo').val("");
        $('#hora').val("");
        $('#guia').val("");
        $('input[type="checkbox"]').prop('checked', false);
        // Elimina la fila de la tabla
        row.remove().draw();
    });


    $("#sortable1, #sortable2").sortable({
        connectWith: ".connectedSortable",
        placeholder: "placeholder"
    }).disableSelection();


    var localidad = $("#localidad");
    var itemDisp = $("#sortable1");

    localidad.autocomplete({
        source: function (request, response) {
            //petición ajax para obtener los datos
            $.ajax({
                url: "/api/localidad/all", //ruta de la api
                dataType: "json", //tipo de datos que se espera
                success: function (data) {
                    var results = data.filter(function (localidad) { //filtro para obtener los datos que coincidan con el término de búsqueda
                        return localidad.nombre.toLowerCase().startsWith(request.term.toLowerCase()); //comparación de la cadena de búsqueda con los datos
                    });
                    response(results.map(function (localidad) {
                        return {
                            value: localidad.nombre,
                            id: localidad.id
                        };
                    }));
                }
            });
        },
        minLength: 2,

        select: function (event, ui) {
            console.log("Loclaidad seleccionada: " + ui.item.value + " con id: " + ui.item.id);
            traeItems(ui.item.id);
        }
    });

    function traeItems(id) {
        $.ajax({
            async: true,
            url: "/api/item/localidad/" + id,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $("#sortable2").empty();
                itemDisp.empty(); // Limpia el contenedor de items
                for (var i = 0; i < data.length; i++) {
                    itemDisp.append("<li class='ui-state-default' item-id=" + data[i].id + "><img src='" + data[i].foto + "' style='width: 30; height: 30'></img>" + data[i].nombre + "</li>");
                }
            },
            error: function () {
                console.log("Error al traer los items");
            }
        })
    }

    localidad.on("change", function () {
        console.log("Localidad seleccionada: " + localidad.val());
    })

    var selectGuia = $("#guia");

    $.ajax({
        url: "/api/usuario/guias", //ruta de la api
        type: 'GET',
        dataType: "json", //tipo de datos que se espera
        success: function (data) {
            selectGuia.empty();
            for (var i = 0; i < data.length; i++) {
                selectGuia.append("<option value='" + data[i].id + "'>" + data[i].nombre + "</option>");
            }
        },
        error: function () {
            console.log("Error al traer los items");
        }
    });

    console.log("eee");

    $("#creaRuta").on("click", function (ev) {
        ev.preventDefault();

        let validacion = $('#tabs')[0].valida();
        if (validacion === true) {
            console.log("hola?");
            guardaRuta();
        } else {
            creaAlerta("error", "Revise los campos en rojo", "¡Error!");
        }

    })

    $("#modalTours").dialog({
        autoOpen: false,
        modal: true
    });


})

function guardaRuta() {
    var table = $('#tablaTours').DataTable();
    console.log("aaa");
    //Datos para enviar a la api y crear la ruta
    var titulo = $('#titulo').val();
    var descripcion = $('#descripcion').jqxEditor('val');
    const fotoInput = $('.image-uploader input[type="file"]');
    const foto = fotoInput[0].files[0];
    var coordInicio = $('#coord_inicio').val();
    var aforo = $('#aforo').val();
    var inicio = $('#inicio').val();
    var fin = $('#fin').val();
    var items = [];
    $('#sortable2 li').each(function () {
        items.push($(this).attr('item-id'));
        console.log($(this).attr('item-id'));
    });
    // var programacion;

    let hasError = false;

    if (!foto) {
        creaAlerta("error", "Debe subir una imagen", "¡Error!");
        hasError = true;
    }

    if (!items || items.length === 0) {
        creaAlerta("error", "Debe añadir al menos un item", "¡Error!");
        hasError = true;
    }

    if (hasError) {
        return;
    }

    let programacion = [];
    if (!table.data().any()) {
        console.log('La tabla no tiene datos');
    } else {
        console.log('La tabla tiene datos');

        $('.dataTables_scroll tbody tr').each(function () {
            let row = $(this);
            let fecha_inicio = row.find('td').eq(0).text();
            let fecha_fin = row.find('td').eq(1).text();
            let hora = row.find('td').eq(2).text();
            let dias = row.find('td').eq(3).text().split(', ');
            let guia = row.find('td').eq(4).text();

            programacion.push({
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
                turno: [
                    [hora, guia]
                ],
                dias: dias
            });
            console.log(programacion);
        });
    }

    
    console.log(foto);
    // console.log($('#sortable2 li')[0].attr('item-id'));
    var formData = new FormData();
    formData.append('foto', foto, foto.name);
    formData.append('nombre', titulo);
    formData.append('descripcion', descripcion);
    formData.append('coordInicio', coordInicio);
    formData.append('aforo', aforo);
    formData.append('inicio', inicio);
    formData.append('fin', fin);
    formData.append('items', JSON.stringify(items));
    // formData.append('programacion', [1]);
    if (programacion.length !== 0) {
        formData.append('programacion', JSON.stringify(programacion));
    }

    console.log(programacion);
    console.log(formData);
    console.log(formData.get("foto"));

    $.ajax({
        url: '/api/ruta/guardaRuta',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response1) {
            console.log(response1.idRuta);
            console.log(programacion);
            if (programacion.length !== 0) {
                $("#modalTours").dialog("open");
                console.log("éxito");
                console.log(response1);
                $("#crearTours").on("click", function () {
                    var rutaId = response1.idRuta;
                    $.ajax({
                        url: '/api/tour/crear/masivo',
                        type: 'POST',
                        data: JSON.stringify({
                            id: rutaId
                        }),
                        contentType: 'application/json',
                        success: function (response2) {
                            console.log("Tours creados con éxito");
                            console.log(response2);
                            $("#modalTours").dialog("close");
                            creaAlerta("success", "Tours creados con éxito", "¡Éxito!");
                        },
                        error: function (error) {
                            console.log("No se pudieron crear los tours");
                            console.log(error);
                            creaAlerta("error", "No se pudieron crear los tours", "¡Error!");
                        }
                    });
                });
            }
            resetForm();
            // location.reload();
            creaAlerta("success", "ruta creada con éxito", "¡Éxito!");

        },
        error: function (error) {
            console.log("no se ha guardado");
            console.log(error);
            creaAlerta("error", "No se pudo crear la ruta", "¡Error!");
        }
    });
}

function creaAlerta(tipo, mensaje, titulo) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "3000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    toastr[tipo](mensaje, titulo);
}

function resetForm() {
    $("#sortable2").empty();
    $('#titulo').val("");
    $('#descripcion').jqxEditor('val', '');
    // $('.uploaded').empty();
    $('.input-images').empty();
    $('.input-images').imageUploader({
        label: 'Arrastra imágenes o haz click para buscarlas en tu almacenamiento',
        extensions: ['.jpg', '.jpeg', '.png'],
        imagesInputName: 'Foto',
        maxSize: 2 * 1024 * 1024,
        maxFiles: 1,
    });
    $('#coord_inicio').val('');
    $('#coordUsable').text('Click para desplegar el mapa');
    $('#aforo').val('');
    $('#inicio').val('');
    $('#fin').val('');
    let tabla = $("#tablaTours").DataTable()
    tabla.clear().draw();
}