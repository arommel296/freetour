$(function () {
    var itemNuevo = $("#itemNuevo");

    var guardaItem = $("input[type=submit]");
    console.log(guardaItem);

    $(".content").append(itemNuevo);

    $('#descripcion').jqxEditor({
        height: '220px',
        width: '100%'
    })

    $('.input-images').imageUploader({
        label: 'Arrastra imágenes o haz click para buscarlas en tu almacenamiento',
        extensions: ['.jpg', '.jpeg', '.png'],
        imagesInputName: 'Foto',
        maxSize: 2 * 1024 * 1024,
        maxFiles: 1
    });

    var localidad = $("#localidad");
    var sitio = $("#sitio");

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
            localidad.val(ui.item.value)
            localidad.attr("value", ui.item.id);
            console.log(localidad.attr("value"));
        }
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

    var coord = $("#coordenadas");
    var coordUsable = $("#coordUsable")
    coordUsable.on("click", function () {
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

            // Pone las coordenadas del marker en el input
            coord.val(e.latlng.lat + ', ' + e.latlng.lng);
            coordUsable.text("Punto establecido en el mapa ✔️");
            console.log(coordUsable.text());
            console.log(coord.val());
        });

        function buscarPorCiudad(event) {
            event.preventDefault();
            // Obtiene la ciudad del input
            var sitio = $("#sitio").val();

            var sitioFormateado = sitio;

            if (localidad.val() != "" && sitio != "") {
                sitioFormateado = sitio + ', ' + localidad.val();
            }

            // Utiliza la API de geocodificación de OpenStreetMap Nominatim para obtener las coordenadas del sitio de la ciudad
            var nominatimURL = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(sitioFormateado);

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
                        coord.val(lat + ', ' + lon);
                        coordUsable.text("Punto establecido en el mapa ✔️");
                        console.log(coordUsable.text());
                        console.log(coord.val());
                    } else {
                        alert("No se encontró el lugar en " + localidad.val());
                    }
                },
                error: function () {
                    alert("Error al buscar el lugar. Por favor, inténtalo de nuevo.");
                }
            });

        }

        var buscar = $("#buscarLugar");

        // buscar.on("click", function(){
        //     if (localidad.val()!=""&&sitio!="") {
        //         sitio.val(sitio.val()+', '+localidad.val())
        //         buscarPorCiudad;
        //     }

        // })

        buscar.on("click", buscarPorCiudad)

    }



    guardaItem.on("click", function (ev) {
        ev.preventDefault();

        const fotoInput = $('.image-uploader input[type="file"]');
        const foto = fotoInput[0].files[0];
        var titulo = $('#titulo').val();
        var coordenadas = $('#coordenadas').val();
        var descripcion = $('#descripcion').jqxEditor('val');
        var local = localidad.attr("value");
        console.log(local);

        //Iniciazlización variable de errores para coprobar si se puede enviar o no el form
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

        var formData = new FormData();
        formData.append('foto', foto, foto.name);
        formData.append('nombre', titulo);
        formData.append('descripcion', descripcion);
        formData.append('coordenadas', coordenadas);
        formData.append('localidad', local);


        $.ajax({
            url: '/api/item/guardaItem',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log("éxito");
                console.log(response);
            },
            error: function (error) {
                console.log("no se ha guardado");
                console.log(error);
            }
        })

    })

})