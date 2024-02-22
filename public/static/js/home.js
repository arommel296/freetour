$(function () {

    // var carousel = $("#carousel");


    // $.ajax({
    //     type: "get",
    //     url: "/api/ruta/all",
    //     dataType: "json",
    //     success: function (response) {
    //         response.forEach(function(ruta) {
    //             var img = $('<img>').attr('src', '/fotos/'+ruta.foto);
    //             var div = $('<div>').append(img);
    //             console.log(ruta.foto);
    //             // div.css("height", "400px")
    //             // div.css("width", "100%")
    //             div.addClass("carousel-item")
    //             carousel.append(div);

    //             // img.on("click", function () {

    //             // })
    //         });
    //         // $carousel.reload();

    //         carousel.slick({
    //             dots: true,
    //             infinite: true,
    //             speed: 300,
    //             slidesToShow: 1,
    //             adaptiveHeight: false
    //         })
    //         // console.log(ruta.foto);
    //         // var img = $('<img>').attr('src', '/fotos/'+ruta.foto);
    //         // var slide = $('<div>').addClass('swiper-slide').append(img);
    //         // $carousel.append(slide);

    //     },
    //     error: function (error) {
    //         console.error(error);
    //     }
    // });

    var buscar = $("input[type=search]");
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
            traeItems(ui.item.id);
        }
    });

    // var rutasP = $("#rutasPrincipal");
    // console.log(rutasP);
    // rutasP.css({
    //     "gap": "20px",
    //     "margin": "20px auto",
    //     "width": "60%"
    // }).addClass("row justify-content-center");
    // rutasP.css("grid-template-columns", "30% 30% 30%");
    // rutasP.css("gap", "20px");

    // $.ajax({
    //     type: "get",
    //     url: "/api/ruta/all",
    //     dataType: "json",
    //     success: function (response) {
    //         console.log(response);
    //         response.forEach(function(ruta) {
    //             console.log(ruta);
    //             var img = $('<img>').attr('src', '/fotos/'+ruta.foto).css({'width': '100%', 
    //                                                                         "height": "200px",
    //                                                                         "object-fit": "cover", 
    //                                                                         "border-radius": "5px"}); //, "box-shadow":"5px 5px 15px rgba(0, 0, 0, 0.3)"
    //             var span = $('<span>').text(ruta.nombre).css({"text-align": "center",
    //                                                         "position": "absolute", 
    //                                                         "bottom": "0",
    //                                                         "background": "rgba(255, 255, 255, 0.7)", 
    //                                                         "width": "80%", 
    //                                                         "left": "10%", 
    //                                                         "background": "linear-gradient(to right, transparent, rgba(255, 255, 255, 0.7), transparent)"})
    //                                                     .addClass("h4");
    //             var titulo = $('<div>').append(span);
    //             var div = $('<div>')
    //                                 .css({"display": "flex",
    //                                     "align-items": "center",
    //                                     "flex-direction": "column",
    //                                     "box-shadow": "0px -2px 2px rgba(0, 0, 0, 0.1), 0px 5px 5px rgba(0, 0, 0, 0.3)",
    //                                     "border-radius": "5px",
    //                                     "position": "relative"})
    //                                 .addClass("col-lg-3 col-sm-12 mx-auto")
    //                                 .append(img)
    //                                 .append(titulo);
    //             // div.addClass("carousel-item");
    //             div.on("click",function () {
    //                 reservar(ruta);
    //             });

    //             rutasP.append(div);

    //         });

    //         console.log("va bien");

    //     },
    //     error: function (error) {
    //         console.error(error);
    //     }
    // });

    // var rutasP = $("#recent-posts .container .row");

    // $.ajax({
    //     type: "get",
    //     url: "/api/ruta/all",
    //     dataType: "json",
    //     success: function (response) {
    //         console.log(response);
    //         response.forEach(function (ruta) {
    //             var img = $('<img>').attr('src', '/fotos/' + ruta.foto).addClass('img-fluid');
    //             var postImg = $('<div>').addClass('post-img').append(img);

    //             var postCategory = $('<p>').addClass('post-category').text(ruta.categoria);

    //             var link = $('<a>').attr('href', 'blog-details.html').text(ruta.nombre);
    //             var title = $('<h2>').addClass('title').append(link);

    //             var postAuthor = $('<p>').addClass('post-author').text(ruta.autor);
    //             var postDate = $('<p>').addClass('post-date').append($('<time>').attr('datetime', ruta.fecha).text(ruta.fecha));
    //             var postMeta = $('<div>').addClass('post-meta').append(postAuthor).append(postDate);

    //             var postAuthorImg = $('<img>').attr('src', 'assets/img/blog/blog-author.jpg').addClass('img-fluid post-author-img flex-shrink-0');
    //             var alignItemsCenter = $('<div>').addClass('d-flex align-items-center').append(postAuthorImg).append(postMeta);

    //             var article = $('<article>').append(postImg).append(postCategory).append(title).append(alignItemsCenter);

    //             var postListItem = $('<div>').addClass('col-xl-4 col-md-6 aos-init aos-animate').attr('data-aos', 'fade-up').append(article);

    //             rutasP.append(postListItem);
    //         });
    //     }
    // });

    var rutasP = $("#recent-posts .container .row");

    $.ajax({
        type: "get",
        url: "/api/ruta/all",
        dataType: "json",
        success: function (response) {
            console.log(response);
            response.forEach(function (ruta) {
                var img = $('<img>').attr('src', '/fotos/' + ruta.foto).addClass('img-fluid');
                var postImg = $('<div>').addClass('post-img').append(img);

                var title = $('<h2>').addClass('title').text(ruta.nombre);

                var alignItemsCenter = $('<div>').addClass('d-flex align-items-center');

                var articulo = $('<article>').append(postImg).append(title).append(alignItemsCenter);

                var postListItem = $('<div>').addClass('col-xl-4 col-md-6 col-12 aos-init aos-animate').attr('data-aos', 'fade-up').append(articulo);

                postListItem.on("click", function () {
                    reservar(ruta);
                })

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

    var calendarEl = document.querySelector('.ruta-calendario');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['dayGrid'],
        events: ruta.tours.map(function (tour) {
            return {
                title: tour.nombre,
                start: tour.fechaInicio,
                end: tour.fechaFin
            };
        })
    });

    calendar.render();

    // $.ajax({
    //     type: "get",
    //     url: "/api/ruta/all",
    //     dataType: "json",
    //     success: function (response) {
    //         console.log(response);
    //         response.forEach(function(ruta) {
    //             console.log(ruta);
    //             var img = $('<img>').attr('src', '/fotos/'+ruta.foto).addClass('w-100 h-200 object-fit-contain');
    //             var titulo = $('<div>').text(ruta.nombre).addClass('text-center position-absolute w-80 left-10').css({ "background": "linear-gradient(to right, transparent, rgba(255, 255, 255, 0.7), transparent)", "width": "80% !important", "left": "10% !important"});
    //             var div = $('<div>').addClass('d-flex flex-column align-items-center shadow rounded position-relative col-12 col-sm-6')
    //                                 .append(img)
    //                                 .append(titulo);
    //             // div.addClass("carousel-item");
    //             div.on("click",)

    //             rutasP.append(div);

    //         });

    //         console.log("va bien");

    //     },
    //     error: function (error) {
    //         console.error(error);
    //     }
    // });

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
        var mapa = L.map('mapaRuta').setView([latitud + 0.0001, longitud + 0.0001], 17);

        // Añade un marcador al mapa por defecto
        var marker = L.marker([latitud, longitud]).addTo(mapa);

        L.tileLayer('https://tile.jawg.io/jawg-streets/{z}/{x}/{y}{r}.png?access-token={accessToken}', {
            minZoom: 0,
            maxZoom: 22,
            accessToken: 'rSpYFAsrP0xh9UQNavI4LCwxGfaAzB4OVL9PGe4rABoU6l1awbhA9ORdSGE8m515'
        }).addTo(mapa);

    }



})