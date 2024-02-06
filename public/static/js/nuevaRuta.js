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
    var coord_inicio = $("#coord_inicio"); // Input de las coordenadas del punto de inicio
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

    $("#mapaDialog").dialog({
        autoOpen: false,
        modal: true,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "explode",
            duration: 500
        }
    });

    coord_inicio.on("click", function () {
        $("#mapaDialog").dialog("open");
    })

    $('#descripcion').jqxEditor({
        height: '220px',
        width: '100%'
    })

    $('#fin').datepicker({
        dateFormat: 'dd-mm-yy'
    })

    $('#inicio').datepicker({
        dateFormat: 'dd-mm-yy'
    })

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

    // Inicializa el mapa de Leaflet
    // L es una variable global de Leaflet
    // map() es una función que crea un mapa en el elemento con el id 'mapid'
    var mapa = L.map('mapid').setView([37.77, -3.79], 13);

    // Añade un marcador al mapa por defecto
    var marker = L.marker([37.77, -3.79]).addTo(mapa);

    // Añade la capa de tiles
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20,
        // attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(mapa);

    mapa.on('click', function (e) {
        // Define las coordenadas del marker donde se ha hecho clic
        marker.setLatLng(e.latlng);

        console.log("punto puesto clicando");
        // Pone las coordenadas del marker en el input
        coord_inicio.val(e.latlng.lat + ', ' + e.latlng.lng);
        console.log(coord_inicio.val());
    });


    // Función 
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
                    mapa.setView([lat, lon], 13);
                    // Pone las coordenadas del marker en el input
                    marker.setLatLng([lat, lon]);
                    console.log("punto puesto buscando por nombre");
                    coord_inicio.val(lat + ', ' + lon);
                    console.log(coord_inicio.val());
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

    $("#sortable1, #sortable2").sortable({
        connectWith: ".connectedSortable",
        placeholder: "placeholder"
    }).disableSelection();

})