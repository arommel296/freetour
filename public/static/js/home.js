$(function () {
    $("#carousel").waterwheelCarousel({
            flankingItems: 3,
    });

    var rutas;

    $.$.ajax({
        type: "get",
        url: "/api/rutas",
        data: "data",
        dataType: "dataType",
        success: function (response) {
            
        }
    });
        
})