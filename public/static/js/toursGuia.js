$(function () {
    $('.tour').click(function() {
    var tourId = $(this).attr('data-tour-id');
    $.ajax({
        url: 'http://127.0.0.1:8000/api/reserva/tour/' + tourId,
        type: 'GET',
        success: function (reservas) {
            var form = $('#listaForm');
            form.empty();
            // Establecer el valor del campo oculto del tour ID
            form.append('<input type="hidden" name="tourId" value="' + tourId + '">');
            form.append('<input type="number" class="form-control mb-3" name="dinero" placeholder="Dinero" data-valida="numero">');
            form.append('<textarea class="form-control mb-3" name="observaciones" placeholder="Observaciones" data-valida="relleno"></textarea>');
            form.append('<input type="file" class="form-control mb-3" name="foto">');
            reservas.forEach(function (reserva) {
                form.append('<div class="reserva mb-3">' +
                    '<p>Reserva: ' + reserva.usuario.nombre + ' '+ reserva.usuario.apellidos + ', asistentes esperados: ' + reserva.nEntradas + '</p>' +
                    '<input type="number" class="form-control" name="reserva_' + reserva.id + '" placeholder="Asistentes finales" data-valida="numero">' +
                    '</div>');
            });
            form.append('<button type="submit" class="btn btn-primary">Enviar</button>');
        },
        error: function () {
            alert('Error al cargar las reservas');
        }
    })
});

    $('#listaForm').submit(function (e) {
        e.preventDefault();
        // Validar el formulario
        if (!this.valida()) {
            alert('El formulario no es válido');
            return;
        }
        
        var data = new FormData(this);
        $.ajax({
            url: '/api/informeLista/guardar',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                alert('Informe enviado con éxito');
                console.log(response);
            },
            error: function (response) {
                alert('Error al enviar el informe');
                console.log(response);
            }
        });
    });
});
