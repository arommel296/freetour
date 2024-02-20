$(function () {

    var carousel = $("#carousel");
    

    $.ajax({
        type: "get",
        url: "/api/ruta/all",
        dataType: "json",
        success: function (response) {
            response.forEach(function(ruta) {
                var img = $('<img>').attr('src', '/fotos/'+ruta.foto);
                var div = $('<div>').append(img);
                console.log(ruta.foto);
                // div.css("height", "400px")
                // div.css("width", "100%")
                div.addClass("carousel-item")
                carousel.append(div);
                
                // img.on("click", function () {
                    
                // })
            });
            // $carousel.reload();

            carousel.slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: false
            })
            // console.log(ruta.foto);
            // var img = $('<img>').attr('src', '/fotos/'+ruta.foto);
            // var slide = $('<div>').addClass('swiper-slide').append(img);
            // $carousel.append(slide);

        },
        error: function (error) {
            console.error(error);
        }
    });

    // $.ajax({
    //     type: "get",
    //     url: "/api/ruta/all",
    //     dataType: "json",
    //     success: function (response) {
    //         var carousel = $("#carousel");
    //         var carouselInner = carousel.find('.carousel-inner');
    
    //         response.forEach(function(ruta, index) {
    //             var img = $('<img>').attr('src', '/fotos/'+ruta.foto).addClass('d-block w-100');
    //             var div = $('<div>').addClass('carousel-item').append(img);
    
    //             if (index === 0) {
    //                 div.addClass('active');
    //             }
    
    //             carouselInner.append(div);
    //         });
    
    //         var prev = $('<a>').addClass('carousel-control-prev').attr('href', '#carousel').attr('role', 'button').attr('data-slide', 'prev');
    //         prev.append($('<span>').addClass('carousel-control-prev-icon').attr('aria-hidden', 'true'));
    //         prev.append($('<span>').addClass('sr-only').text('Previous'));
    
    //         var next = $('<a>').addClass('carousel-control-next').attr('href', '#carousel').attr('role', 'button').attr('data-slide', 'next');
    //         next.append($('<span>').addClass('carousel-control-next-icon').attr('aria-hidden', 'true'));
    //         next.append($('<span>').addClass('sr-only').text('Next'));
    
    //         carousel.append(prev);
    //         carousel.append(next);
    //     },
    //     error: function (error) {
    //         console.error(error);
    //     }
    // });
        
})