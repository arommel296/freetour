$(function () {

    $('input[type=radio][name=estrellasR]').change(function () {
        var rating = $(this).val();
        //   $('#valoracion_notaGuia').val(rating);
        $('#valoracion_notaRuta').val(rating - 1);
    });

    $('input[type=radio][name=estrellasG]').change(function () {
        var rating = $(this).val();
        $('#valoracion_notaGuia').val(rating - 1);
        // $('#valoracion_notaRuta').val(rating);
    });


})