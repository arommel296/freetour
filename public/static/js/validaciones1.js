//Este es el bueno, increíble
// HTMLInputElement.prototype.relleno=function () {
//     var respuesta = false;
//     if (this.value!="") {
//         respuesta=true;
//     }
//     return respuesta;
// }

HTMLInputElement.prototype.relleno=function () {
    if (this.value.trim() == '') {
        return 'Este campo no puede estar vacío';
    }
    return '';
}

HTMLInputElement.prototype.dni=function () {
    var letras = "TRWAGMYFPDXBNJZSQVHLCKET";
    var respuesta = false;
    if (this.value!="") {
        var partes = (/^(\d{8})([TRWAGMYFPDXBNJZSQVHLCKET])$/i).exec(this.value); //coincidencia completa -> partes[0], por eso hacemos partes[1] y partes[2]
        if (partes) {
            respuesta = letras[partes[1]%23]==partes[2].toUpperCase();
        }
        console.log(partes);
    }
    return respuesta;
}

HTMLInputElement.prototype.edad=function () {
    var respuesta = false;
    if (this.value==parseInt(this.value) && this.value>=0 && this.value<150) {
        var respuesta = true;
    }
    return respuesta;
}

HTMLInputElement.prototype.fecha = function() {
    var respuesta = false;
    var partes = this.value.split('-');
    if (partes.length === 3) {
        var año = parseInt(partes[0], 10);
        var mes = parseInt(partes[1], 10);
        var dia = parseInt(partes[2], 10);
        var fecha = new Date(año, mes - 1, dia);
        if (fecha && fecha.getFullYear() === año && fecha.getMonth() + 1 === mes && fecha.getDate() === dia) {
            respuesta = true;
        }
    }
    return respuesta;
}




// HTMLInputElement.prototype.numero = function() {
//     var respuesta = false;
//     if (!isNaN(this.value) && this.value.trim() !== '' && Number(this.value) >= 0) {
//         respuesta = true;
//     }
//     return respuesta;
// }

HTMLInputElement.prototype.numero=function () {
    if (isNaN(this.value)) {
        return 'Este campo debe ser un número';
    }
    return '';
}

HTMLInputElement.prototype.seleccionado=function () {
    var respuesta = false;
    var name = this.name;
    if (this.form[name].value!="") {
        respuesta = true;
    }
    return respuesta;
}

// HTMLFormElement.prototype.valida=function () {
//     var elementos = this.querySelectorAll("input[data-valida], select[data-valida]");
//     console.log(elementos);
//     var respuesta = true;
//     let n = elementos.length;
//     for (let i = 0; i < n; i++) {
//         let tipo = elementos[i].getAttribute("data-valida"); //es lo mismo que elementos[i].data-valida.value
//         var aux=elementos[i][tipo]();
//         if (aux) {
//             elementos[i].classList.add("valido");
//             elementos[i].classList.remove("invalido");
//         } else{
//             elementos[i].classList.add("invalido");
//             elementos[i].classList.remove("valido");
//         }
//         respuesta = respuesta&&aux;
//     }

//     return respuesta;
// }

HTMLFormElement.prototype.valida=function () {
    var elementos = this.querySelectorAll("input[data-valida], select[data-valida]");
    var respuesta = true;
    var errores = [];
    let n = elementos.length;
    for (let i = 0; i < n; i++) {
        let tipos = elementos[i].getAttribute("data-valida").split(' ');
        var aux = true;
        for (let j = 0; j < tipos.length; j++) {
            let tipo = tipos[j];
            let error = elementos[i][tipo]();
            aux = aux && (error == '');
            if (error != '') {
                errores.push(error);
            }
            if (aux) {
                elementos[i].classList.add("valido");
                elementos[i].classList.remove("invalido");
            } else{
                elementos[i].classList.add("invalido");
                elementos[i].classList.remove("valido");
            }
        }
        respuesta = respuesta && aux;
    }

    return errores.length == 0 ? true : errores;
}

HTMLSelectElement.prototype.select=function(){
    var respuesta = false;
    var name = this.name;
    if (this.form[name].value!="-1") {
        respuesta = true;
    }
    return respuesta;
}

