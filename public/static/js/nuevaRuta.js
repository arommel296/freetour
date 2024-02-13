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

    // Inicialización dvariables necesarias
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
        onSelect: function(selectedDate) {
            $('#fin').datepicker('option', 'minDate', selectedDate);
            $('#inicioPeriodo').datepicker('option', 'minDate', selectedDate);
        }
    });
    
    $('#fin').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        onSelect: function(selectedDate) {
            $('#inicio').datepicker('option', 'maxDate', selectedDate);
            $('#finPeriodo').datepicker('option', 'maxDate', selectedDate);
            $('#inicioPeriodo').datepicker('option', 'maxDate', selectedDate);
        }
    });
    
    $('#inicioPeriodo').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        onSelect: function(selectedDate) {
            $('#finPeriodo').datepicker('option', 'minDate', selectedDate);
        }
    });
    
    $('#finPeriodo').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        onSelect: function(selectedDate) {
            $('#inicioPeriodo').datepicker('option', 'maxDate', selectedDate);
        }
    });

    $('.input-images').imageUploader({
        label: 'Arrastra imágenes o haz click para buscarlas en tu almacenamiento',
        extensions: ['.jpg', '.jpeg', '.png'],
        imagesInputName: 'Foto',
        maxSize: 2 * 1024 * 1024,
        maxFiles: 1
    });


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
        });

        coordInicio.on("click", function () {
            creaMapa();
        })

    function creaMapa() {
        $("#mapaDialog").dialog("open");

            // Inicializa el mapa de Leaflet
        // L es una variable global de Leaflet
        // map() es una función que crea un mapa en el elemento con el id 'mapid'
        var mapa = L.map('mapid').setView([37.77, -3.79], 15);

        // Añade un marcador al mapa por defecto
        var marker = L.marker([37.77, -3.79]).addTo(mapa);

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
    
    let tablaTours = new DataTable($("#tablaTours"));
    

    $("#sortable1, #sortable2").sortable({
        connectWith: ".connectedSortable",
        placeholder: "placeholder"
    }).disableSelection();


    var localidad = $("#localidad");
    var itemDisp = $("#sortable1");

    localidad.autocomplete({
        source: function(request, response) {
            //petición ajax para obtener los datos
            $.ajax({
                url: "/api/localidad/all", //ruta de la api
                dataType: "json", //tipo de datos que se espera
                success: function(data) {
                    var results = data.filter(function(localidad) { //filtro para obtener los datos que coincidan con el término de búsqueda
                        return localidad.nombre.toLowerCase().startsWith(request.term.toLowerCase()); //comparación de la cadena de búsqueda con los datos
                    });
                    response(results.map(function(localidad) {
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
                    itemDisp.append("<li class='ui-state-default' item-id><img src='"+data[i].foto+"' style='width: 30; height: 30'></img>" + data[i].nombre + "</li>");
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

    // var foto = $("#subeFotos").on("drop", function (event) {
    //     var foto = event.dataTransfer.files[0];
    // })

    // $('.input-images').on('change', function() {
    //     // Obtenemos la imagen en base64
    //     alert("aaaa")
    //     let imgBase64 = $('.input-images img').attr('src');
    
    //     console.log(imgBase64);
    //     // Creamos un objeto FormData
    //     let formData = new FormData();
    
    //     // Añadimos la imagen al objeto FormData
    //     formData.append('Foto', imgBase64);
    
    //     // Enviamos la imagen al servidor
    //     $.ajax({
    //         url: '/ruta/del/servidor',
    //         type: 'POST',
    //         data: formData,
    //         processData: false,  // Indicamos a jQuery que no procese los datos
    //         contentType: false   // Indicamos a jQuery que no establezca el contentType
    //     })
    //     .done(function(data) {
    //         console.log(data);
    //     })
    //     .fail(function(error) {
    //         console.error(error);
    //     });
    // });

    var fotos = $("#subeFotos img").attr('src');
    // Hacemos una solicitud HTTP para obtener el Blob
fetch(fotos)
.then(response => response.blob())
.then(blob => {
    // Creamos un objeto File a partir del Blob
    let file = new File([blob], 'nombre-de-la-imagen.jpg', {type: 'image/jpeg'});

    // Creamos un objeto FormData
    let formData = new FormData();

    // Añadimos la imagen al objeto FormData
    formData.append('Foto', file);

    // Enviamos la imagen al servidor
    $.ajax({
        url: '/ruta/del/servidor',
        type: 'POST',
        data: formData,
        processData: false,  // Indicamos a jQuery que no procese los datos
        contentType: false   // Indicamos a jQuery que no establezca el contentType
    })
    .done(function(data) {
        console.log(data);
    })
    .fail(function(error) {
        console.error(error);
    });
})
.catch(error => console.error(error));

    $("#nuevaRuta").on("submit", function (e) {
        e.preventDefault();

        //Recogida de datos de la ruta
        var titulo = $("#titulo").val();
        console.log(titulo);
        var descripcion = $("#descripcion").val();
        console.log(descripcion);
        // var fotos = $("#subeFotos img")[0].src;
        console.log(fotos);
        var fotosB64 = fot

        var data = $(this).serialize(); // Obtiene los datos del formulario
        console.log(data);
        $.ajax({
            url: "/api/ruta",
            type: 'POST',
            data: data,
            success: function (data) {
                console.log(data);
                alert("Ruta creada con éxito");
            },
            error: function () {
                alert("Error al crear la ruta");
            }
        })
    })

    var selectGuia = $("#guia");

    $.ajax({
        url: "/api/usuario/guias", //ruta de la api
        type: 'GET',
        dataType: "json", //tipo de datos que se espera
        success: function(data) {
            selectGuia.empty();
            for (var i = 0; i < data.length; i++) {
                selectGuia.append("<option value='" + data[i].nombre +"'>" + data[i].nombre +"</option>");
            }
        },
        error: function () {
            console.log("Error al traer los items");
        }
    });

    console.log("eee");
    //Datos para enviar a la api y crear la ruta
    

    function guardaRuta() {
        var titulo = $('#titulo').val();
        var descripcion = $('#descripcion').html();
        const fileInput = $('.image-uploader input[type="file"]');
        const file = fileInput[0].files[0];
        var coord_inicio = $('#coord_inicio').val();
        var aforo = $('#aforo').val();
        var inicio = $('#inicio').val();
        var fin = $('#fin').val();
        var items = [];
        $('#sortable2 li').each(function () {
            items.push($(this).data('id'));
        });

        const formData = new FormData();
        formData.append('foto', file, file.name);
        formData.append('nombre', titulo);
        formData.append('descripcion', descripcion);
        formData.append('coord_inicio', coord_inicio);
        formData.append('aforo', aforo);
        formData.append('inicio', inicio);
        formData.append('fin', fin);
        formData.append('items', JSON.stringify(items));

        console.log("formdata" + formData.get("foto"));

        var data = {
            nombre: titulo,
            descripcion: descripcion,
            coord_inicio: coordInicio,
            aforo: aforo,
            inicio: inicio,
            fin: fin,
            items: items,
            foto: file
        };

        $.ajax({
            url: '', 
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                
            },
            error: function(error) {
                
            }
        });
    }

    // {
    //     console.log(data);
    //     itemDisp.empty(); // Limpia el contenedor de items
    //     for (var i = 0; i < data.length; i++) {
    //         itemDisp.append("<li class='ui-state-default' item-id><img src='"+data[i].foto+"' style='width: 30; height: 30'></img>" + data[i].nombre + "</li>");
    //     }
    // },
    // error: function () {
    //     console.log("Error al traer los items");
    // }


})