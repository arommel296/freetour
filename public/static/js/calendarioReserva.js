$(function () {

    //Botón de reservar disabled hasta que no elija un tour
    $("#formulario input").attr("disabled", true);

    const daysTag = document.querySelector(".days"),
        currentDate = document.querySelector(".current-date"),
        prevNextIcon = document.querySelectorAll(".icons span");

    // getting new date, current year and month
    let date = new Date(),
        currYear = date.getFullYear(),
        currMonth = date.getMonth();

    // storing full name of all months in array
    const months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
        "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    const renderCalendar = () => {
        let firstDayofMonth = new Date(currYear, currMonth, 0).getDay(), // getting first day of month
            lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
            lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // getting last day of month
            lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month
        let liTag = "";

        for (let i = firstDayofMonth; i > 0; i--) { // creating li of previous month last days
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) { // creating li of all days of current month
            // adding active class to li if the current day, month, and year matched
            let isToday = i === date.getDate() && currMonth === new Date().getMonth() &&
                currYear === new Date().getFullYear() ? "active" : "";
            liTag += `<li class="${isToday}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
        }
        currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
        daysTag.innerHTML = liTag;

        $.each($("li"), function (i, valueOfElement) {
            if (i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear()) {
                $(this).removeClass("active");
                console.log(date);
            }


            $(this).on("click", function () {
                console.log($(this));
                let aux = new Date(currYear, currMonth, $(this).text());
                let hoy = new Date();
                if (aux.getMonth() >= hoy.getMonth() || aux.getFullYear() >= hoy.getFullYear()) {
                    if (aux.getMonth() == hoy.getMonth() && aux.getFullYear() == hoy.getFullYear()) {
                        if (aux.getDate() >= hoy.getDate()) {
                            date = new Date(currYear, currMonth, $(this).text());
                            // date = aux;
                            $(".active").removeClass("active");
                            $(this).addClass("active");
                            console.log("hola");
                        }

                    }
                    if (aux.getMonth() > hoy.getMonth() && aux.getFullYear() == hoy.getFullYear()) {
                        date = new Date(currYear, currMonth, $(this).text());
                        if (!$(this).hasClass("inactive")) {
                            $(".active").removeClass("active");
                            $(this).addClass("active");
                            console.log("adios");
                        }
                        // date = aux;

                    }
                    if (aux.getMonth() != hoy.getMonth() && aux.getFullYear() > hoy.getFullYear()) {
                        date = new Date(currYear, currMonth, $(this).text());
                        // date = aux;
                        $(".active").removeClass("active");
                        $(this).addClass("active");
                        console.log("hasta luego");

                    }
                }

                console.log(aux);
                if ($(this).find(".evento").length) {
                    console.log($(this).find(".evento").length);

                    //Formateo la fecha con el formato 25-02-2024 par apoder hacer la petición ajax
                    let dia = aux.getDate() < 10 ? '0' + aux.getDate() : aux.getDate() + "";
                    let mes = (aux.getMonth() + 1) < 10 ? '0' + (aux.getMonth() + 1) : (aux.getMonth() + 1) + "";
                    let anio = aux.getFullYear() + "";
                    let fecha = dia + "-" + mes + "-" + anio;

                    //Obtengo el ide de la Ruta que viene en la url
                    let r = window.location.pathname;
                    let idRuta = r.split("/ruta/", [2])[1];

                    //Hago el ajax para obtener todos los datos de los tours del día
                    $.ajax({
                        type: "GET",
                        url: "/api/tour/all/day/ruta/" + idRuta + "/" + fecha,
                        dataType: "json",
                        success: function (response) {
                            if (response) {
                                // limpia los tours del día seleccionado  antes
                                $(".tour-info").remove();
                                console.log(response);
                                // $(".wrapper").after('<div class="tour-info mt-3 mb-3 p-3 bg-white shadow rounded">Información del tour: </div>');
                                response.forEach(function (tour, index) {
                                    // Se crean los radio buttons con la información de cada tour (hora y aforo disponible)
                                    var tourInfo = 'Hora: ' + tour.tour.fechaHora.date.split(" ")[1].split(":")[0] + ":" + tour.tour.fechaHora.date.split(" ")[1].split(":")[1] + ', Plazas disponibles: ' + tour.aforo;
                                    // Radiobutton de Bootstrap
                                    console.log(tour.tour.id);
                                    var radioButton = '<input type="radio" class="btn-check" name="options" id="tour' + index + '" autocomplete="off">' +
                                        '<label class="btn btn-secondary" for="tour' + index + '">' + tourInfo + '</label><br>';
                                    // Se añade cada radio button al div que lo contiene, debajo del calendario
                                    $(".wrapper").after('<div class="tour-info mt-3 mb-3 p-3 bg-white shadow rounded">' + radioButton + '</div>');

                                    $('label.btn').click(function () {
                                        var radioId = $(this).attr('for');
                                        console.log("label pulsado");
                                        $('#' + radioId).prop('checked', true);
                                        if ($('input[type=radio]:checked').length > 0) {
                                            $("input[name=tourId]").val(tour.tour.id);
                                            $("#formulario input").attr("disabled", false);
                                        } else {
                                            $("#formulario input").attr("disabled", true);
                                        }
                                    });
                                    
                                });
                            }

                        },
                        error: function (error) {
                            $(".tour-info").remove();
                            $(".wrapper").after('<div class="tour-info mt-3 mb-3 p-3 bg-white shadow rounded">Ha habido un error al cargar los tours disponibles, inténtelo de nuevo.</div>');
                        }
                    });

                } else {
                    $(".tour-info").remove();
                    $(".wrapper").after('<div class="tour-info mt-3 mb-3 p-3 bg-white shadow rounded">No hay tours disponibles para esta ruta en la fecha elegida</div>');
                }
            })
            // }

        });
    }
    renderCalendar();
    cargaFechas();
    cargaMapa();

    prevNextIcon.forEach(icon => { // getting prev and next icons
        icon.addEventListener("click", () => { // adding click event on both icons
            // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
            currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

            if (currMonth < 0 || currMonth > 11) { // if current month is less than 0 or greater than 11
                // creating a new date of current year & month and pass it as date value
                date = new Date(currYear, currMonth, new Date().getDate());
                currYear = date.getFullYear(); // updating current year with new date year
                currMonth = date.getMonth(); // updating current month with new date month
            } else {
                date = new Date(); // pass the current date as date value
            }
            renderCalendar(); // calling renderCalendar function

            console.log("ooo");
            cargaFechas();

        });


    });


    // $(".days li.active").append("<div class='evento'></div>");

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
        var mapa = L.map('mapaRuta').setView([latitud + 0.001, longitud + 0.0001], 17);

        $("#mapaRuta").css({
            "border-radius": "5px",
            "height": "80%",
        });
        $(".testimonial-item").css({
            "height": "400px",
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


    function cargaMapa() {
        let r = window.location.pathname;
        let idRuta = r.split("/ruta/", [2])[1];
        console.log(idRuta);

        $.ajax({
            type: "GET",
            url: "http://127.0.0.1:8000/api/ruta/unica/" + idRuta,
            dataType: "json",
            success: function (ruta) {
                creaMapa(ruta);
            }
        });
    }

    function cargaFechas() {
        let r = window.location.pathname;
        let idRuta = r.split("/ruta/", [2])[1];
        console.log(idRuta);

        $.ajax({
            type: "GET",
            url: "http://127.0.0.1:8000/api/tour/all/fechas/ruta/" + idRuta,
            dataType: "json",
            success: function (fechas) {
                fechas.forEach(function (fecha) {
                    var obj = JSON.parse(fecha);
                    var date = new Date(obj.date);
                    var day = date.getDate();
                    var month = date.getMonth();
                    var year = date.getFullYear();
                    console.log(date);
                    console.log(obj);

                    $("ul.days li").each(function () {
                        // Check if the day, month, and year match
                        if ($(this).text() == day && currMonth == month && currYear == year) {
                            $(this).append('<div class="evento"></div>');
                        }
                        $(this).click(function () {
                            // $(".tour-info").remove();
                            // $(".wrapper").after('<div class="tour-info">Información del tour: ' + fecha + '</div>');
                        });
                    });
                });
            }
        });
    }

    // $('label.btn').click(function () {
    //     var radioId = $(this).attr('for');
    //     console.log("label pulsado");
    //     $('#' + radioId).prop('checked', true);
    //     if ($('input[type=radio]:checked').length > 0) {
    //         $("#formulario input").attr("disabled", false);
    //     } else {
    //         $("#formulario input").attr("disabled", true);
    //     }
    // });

    // $('input[type=radio]').change(function() {
    //     console.log("aquí al menos entra");
    //     if ($('input[type=radio]:checked').length > 0) {
    //         console.log("disabled false");
    //         $("#formulario input").attr("disabled", false);
    //     } else {
    //         console.log("disabled true");
    //         $("#formulario input").attr("disabled", true);
    //     }
    // });



    // function cargaFechas() {
    //     let r = window.location.pathname;
    //     let idRuta = r.split("/ruta/", [2])[1];
    //     console.log(idRuta);

    //     $.ajax({
    //         type: "GET",
    //         url: "http://127.0.0.1:8000/api/tour/all/fechas/ruta/" + idRuta,
    //         dataType: "json",
    //         success: function (fechas) {
    //             fechas.forEach(function (fecha) {
    //                 var obj = JSON.parse(fecha);
    //                 var date = new Date(obj.date);
    //                 var day = date.getDate();
    //                 console.log(date);
    //                 console.log(obj);

    //                 $("ul.days li").each(function () {
    //                     if ($(this).text() == day) {
    //                         $(this).append('<div class="evento"></div>');

    //                     }
    //                     $(this).click(function () {
    //                         // $(".tour-info").remove();
    //                         // $(".wrapper").after('<div class="tour-info">Información del tour: ' + fecha + '</div>');
    //                     });
    //                 });
    //             });
    //         }
    //     });
    // }

})
