$(function() {
    $('.cancel-reservation').click(function() {
        var reservationId = $(this).data('reservation-id');
        $.ajax({
            url: '/api/reserva/cancelaReserva',
            type: 'DELETE',
            data: { idReserva: reservationId },
            success: function(result) {
                creaAlerta1("success", "Reserva cancelada con éxito", "¡Éxito!");
                location.reload();
            },
            error: function(result) {
                creaAlerta1("error", "Error al cancelar la reserva", "¡Error!");
            }
        });
    });

    $(".toast .toast-success").css("background-color", "green");
});

function creaAlerta1(tipo, mensaje, titulo) {
    // toastr.options = {
    //     "closeButton": false,
    //     "debug": false,
    //     "newestOnTop": false,
    //     "progressBar": true,
    //     "positionClass": "toast-top-center",
    //     "preventDuplicates": false,
    //     "onclick": null,
    //     // "showDuration": "300",
    //     "hideDuration": "1000",
    //     "timeOut": "5000",
    //     "extendedTimeOut": "1000",
    //     "showEasing": "swing",
    //     "hideEasing": "linear",
    //     "showMethod": "fadeIn",
    //     "hideMethod": "fadeOut"
    // }
    toastr.options = {
        "closeButton": false,
        "debug": true,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
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