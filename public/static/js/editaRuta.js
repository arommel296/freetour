$(function () {
    console.log("editaRuta.js");
    var ruta = JSON.parse($('body').attr('data-ruta'));
    console.log(ruta);
    console.log(ruta.items);
    // console.log(ruta.coordInicio);
    if (typeof ruta !== 'undefined' && ruta !== null) {
        console.log("entra a editaruta.js");
        $('#titulo').val(ruta.nombre);
        $('#descripcion').jqxEditor('val', ruta.descripcion);
        if (ruta.coordInicio != null && ruta.coordInicio != undefined && ruta.coordInicio != "") {
            console.log("coordInicio: " + ruta.coordInicio);
            $('#coord_inicio').val(ruta.coordInicio);
            // L.setMarker(ruta.coordInicio);
            $('#coordUsable').text("Punto establecido en el mapa ✔️");
            console.log("sigue en el if");
        } else {
            $('#coordUsable').text("Click para desplegar el mapa");
            console.log("está en el else");
        }

        $('#aforo').val(ruta.aforo);

        // var img = $('<img>', { src: "http://127.0.0.1:8000/fotos/"+ruta.foto });
        // var div = $('<div>', { class: 'uploaded-image' });
        // div.append(img);
        // $('.image-uploader').append(div);

        var imageUrl = "/fotos/" + ruta.foto;
        let foto = ruta.foto;
        let preloaded = [{
            id: 1,
            src: imageUrl,
            name: 'foto'
        }]

        //Borro el imageUploader anterior para poder crear uno nuevo
        var inputImages = $('.input-images');
        inputImages.remove();

        //Crea un div con la clase input-images y loañade debajo del label: <label for="foto" class="form-label">Imagen</label>
        var inputImages = $('<div>', {
            class: 'input-images'
        }).insertAfter('label[for="foto"]');

        inputImages.imageUploader({
            label: 'Arrastra imágenes o haz click para buscarlas en tu almacenamiento',
            preloaded: preloaded,
            imagesInputName: 'Foto',
            extensions: ['.jpg', '.jpeg', '.png'],
            mimes: 'image/*',
            maxSize: 2 * 1024 * 1024,
            maxFiles: 1,
        });

        // Formateo de la fecha en formato DD-MM-YYYY
        var inicioDate = new Date(ruta.inicio.date);
        var inicioFormateada = ('0' + inicioDate.getDate()).slice(-2) + '-' +
            ('0' + (inicioDate.getMonth() + 1)).slice(-2) + '-' +
            inicioDate.getFullYear();

        var finDate = new Date(ruta.fin.date);
        var finFormateada = ('0' + finDate.getDate()).slice(-2) + '-' +
            ('0' + (finDate.getMonth() + 1)).slice(-2) + '-' +
            finDate.getFullYear();

        $('#inicio').val(inicioFormateada);
        $('#fin').val(finFormateada);
        $('#sortable2').empty();
        //Carga los items de la ruta
        ruta.items.forEach(function (item) {
            console.log(item.foto);
            $('#sortable2').append("<li class='list-group-item' item-id='" + item.id + "'><img src='http://127.0.0.1:8000/fotos/" + item.foto + "' style='width: 30; height: 30'></img>" + item.nombre + "</li>");
        });

        console.log(ruta.programacion);
        //Carga la programación de la ruta
        if (ruta.programacion != null) {
            ruta.programacion.forEach(function (item) {
                if (Array.isArray(item.turno)) {
                    item.turno.forEach(function (turno) {
                        tablaTours.row.add([
                            item.fecha_inicio,
                            item.fecha_fin,
                            turno[0], // Hora
                            item.dias.join(', '), // Días
                            turno[1], // Guía
                            '<button class="edit btn btn-primary">Editar</button><button class="delete btn btn-primary">Eliminar</button>'
                        ]).draw();
                    });
                }
            });
        } 


    }


    $("#guardaRuta").on("click", function (ev) {
        ev.preventDefault();
        console.log("holaaaaa");
        guardaRuta(ruta);
    })
})

function guardaRuta(ruta) {
    console.log("aaa");
    //Datos para enviar a la api y crear la ruta
    var titulo = $('#titulo').val();
    console.log(titulo);
    var descripcion = $('#descripcion').jqxEditor('val');
    const fotoInput = $('.image-uploader input[type="file"]');
    const foto = fotoInput[0].files[0];
    var coordInicio = $('#coord_inicio').val();
    var aforo = $('#aforo').val();
    var inicio = $('#inicio').val();
    var fin = $('#fin').val();
    var items = [];
    $('#sortable2 li').each(function () {
        items.push($(this).attr('item-id'));
        console.log($(this).attr('item-id'));
    });
    // var programacion;


    let programacion = [];
    $('.dataTables_scroll tbody tr').each(function () {
        let row = $(this);
        let fecha_inicio = row.find('td').eq(0).text();
        let fecha_fin = row.find('td').eq(1).text();
        let hora = row.find('td').eq(2).text();
        let dias = row.find('td').eq(3).text().split(', ');
        let guia = row.find('td').eq(4).text();

        programacion.push({
            fecha_inicio: fecha_inicio,
            fecha_fin: fecha_fin,
            turno: [
                [hora, guia]
            ],
            dias: dias
        });
        console.log(programacion);
    });

    console.log(foto);
    // console.log($('#sortable2 li')[0].attr('item-id'));
    var formDataModificar = new FormData();
    if (foto) {
        formDataModificar.append('foto', foto, foto.name);
    } else {
        console.log('No había foto');
    }
    // formData.append('foto', foto, foto.name);


    formDataModificar.append('nombre', titulo);
    formDataModificar.append('descripcion', descripcion);
    formDataModificar.append('coordInicio', coordInicio);
    formDataModificar.append('aforo', aforo);
    formDataModificar.append('inicio', inicio);
    formDataModificar.append('fin', fin);
    formDataModificar.append('items', JSON.stringify(items));
    // formData.append('programacion', [1]);
    if ($('.dataTables_empty') > 0) {
        // formDataModificar.append('programacion', JSON.stringify(programacion));
        console.log('No hay programación');
    } else {
        formDataModificar.append('programacion', JSON.stringify(programacion));
        console.log('sí hay programación');
    }
    // formDataModificar.append('programacion', JSON.stringify(programacion));

    console.log(formDataModificar.get("programacion"));

    console.log(formDataModificar.get("nombre"));
    console.log(formDataModificar.get("items"));
    console.log(formDataModificar);

    $.ajax({
        url: '/api/ruta/modificarRuta/' + ruta.id,
        type: 'POST',
        data: formDataModificar,
        contentType: false,
        processData: false,
        success: function (response1) {
            console.log(response1.idRuta);
            $("#modalTours").dialog("open");
            console.log("éxito");
            console.log(response1);
            creaAlerta("success", "Ruta actualizada con éxito", "¡Éxito!");
            $("#crearTours").on("click", function () {
                var rutaId = response1.idRuta;
                $.ajax({
                    url: '/api/tour/crear/masivo',
                    type: 'POST',
                    data: JSON.stringify({
                        id: rutaId
                    }),
                    contentType: 'application/json',
                    success: function (response2) {
                        console.log("Tours creados con éxito");
                        console.log(response2);
                        $("#modalTours").dialog("close");
                        creaAlerta("success", "Tours creados con éxito", "¡Éxito!");
                    },
                    error: function (error) {
                        console.log("No se pudieron crear los tours");
                        console.log(error);
                        creaAlerta("error", "No se pudieron crear los tours", "¡Error!");
                    }
                });
            });
            creaAlerta("success", "ruta actualizada con éxito", "¡Éxito!");
        },
        error: function (error) {
            console.log("no se ha guardado");
            console.log(error);
            creaAlerta("error", "No se pudo actuaizar a ruta", "¡Error!");
        }
    });
}

function creaAlerta(tipo, mensaje, titulo) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
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

function resetForm(formId) {
    // Obtén el formulario usando su id
    var form = $('#' + formId);

    // Limpia todos los campos de texto, áreas de texto y campos ocultos
    form.find('input:text, input:password, input:hidden, textarea').val('');

    // Desmarca todos los botones de opción y casillas de verificación
    form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');

    // Limpia todos los selectores
    form.find('select').prop('selectedIndex', -1);
}