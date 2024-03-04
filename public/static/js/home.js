$(function () {

    var buscar = $("#buscaPorLoc");
    buscar.autocomplete({
        source: function (request, response) {
            //petición ajax para obtener los datos
            $.ajax({
                url: "/api/localidad/all", //ruta de la api
                dataType: "json", //tipo de datos que se espera
                success: function (data) {
                    var results = data.filter(function (buscar) { //filtro para obtener los datos que coincidan con el término de búsqueda
                        return buscar.nombre.toLowerCase().startsWith(request.term.toLowerCase()); //comparación de la cadena de búsqueda con los datos
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
            // traeItems(ui.item.id);
        }
    });



    var rutasP = $("#recent-posts .container .row");

    $.ajax({
        type: "get",
        url: "/api/ruta/all",
        dataType: "json",
        success: function (response) {
            console.log(response);
            response.forEach(function (ruta) {
                console.log(ruta.id);
                var a = $("<a>").attr("href", "/reservar/ruta/" + ruta.id);

                var img = $('<img>').attr('src', '/fotos/' + ruta.foto).addClass('img-fluid');
                var postImg = $('<div>').addClass('post-img').append(img);

                var title = $('<h2>').addClass('title').text(ruta.nombre);

                var alignItemsCenter = $('<div>').addClass('d-flex align-items-center');

                var articulo = $('<article>').append(postImg).append(title).append(alignItemsCenter);

                a.append(articulo);

                var postListItem = $('<div>').addClass('col-xl-4 col-md-6 col-12 aos-init aos-animate').attr('data-aos', 'fade-up').append(a);

                

                // postListItem.on("click", function () {
                //     reservar(ruta);
                // })

                rutasP.append(postListItem);
            });
            console.log("va bien");
        },
        error:function (error) {
            console.error(error);
        }
    });


    function reservar(ruta) {
        console.log("entra a la función " + ruta.id);
        $.ajax({
            type: "GET",
            url: "reservar/ruta/" + ruta.id,
            dataType: 'html',
            success: function (response) {
                console.log(response);
                // console.log(ruta.traeItems());
                $("#main").empty();

                $("#main").html(response);
                $(".descripcion").html(ruta.descripcion);
                console.log(response);
                creaMapa(ruta);
            }
        });
    }


    function creaMapa(ruta) {

        console.log(ruta);

        var coords = ruta.coordInicio.replace(" ", "").split(",");
        if (coords.length !== 2) {
            console.error('Formato de coordenadas incorrecto: ' + ruta.coordInicio);
        } else {
            var latitud = parseFloat(coords[0]);
            var longitud = parseFloat(coords[1]);
            console.log(latitud, longitud);
        }
        // Inicializa el mapa de Leaflet
        // L es una variable global de Leaflet
        // map() es una función que crea un mapa en el elemento con el id 'mapid'
        var mapa = L.map('mapaRuta').setView([latitud+ 0.001, longitud+ 0.0001], 17);

        $("#mapaRuta").css({"border-radius": "5px",
                    "height": "80%",
                });
        $(".testimonial-item").css({"height": "400px",
                                    "border-radius": "5px"
                                    });
        console.log("qué pasa");

        // Añade un marcador al mapa por defecto
        var marker = L.marker([latitud, longitud]).addTo(mapa);

        L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', {
            minZoom: 0,
            maxZoom: 22,
            accessToken: 'rSpYFAsrP0xh9UQNavI4LCwxGfaAzB4OVL9PGe4rABoU6l1awbhA9ORdSGE8m515'
        }).addTo(mapa);

    }



})