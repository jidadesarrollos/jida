/**
 * Verifica si un objeto Radio se encuentra seleccionado
 * @param string nombreRadio Atributo "NAME" del control
 */
function validarRadio(nombreRadio) {

    var nombreRadio = (nombreRadio);

    var control = "input[name=" + nombreRadio + "]";
    control = ($(control).length > 0) ? control : "input[name=\"" + nombreRadio + "[]\"]";
    var type = $(control).prop('type');
    var cont = 0;
    var dataSeleccionada = new Array;
    if ($(control + ":checked").length > 0) {
        radiosSeleccionados = $(control + ":checked");
        $.each(radiosSeleccionados, function () {
            dataSeleccionada.push(this.value);
            cont++;
        });//final foreach
        if (cont == 1) {

            return dataSeleccionada[0];
        } else {

            //return serializar(dataSeleccionada);
            return dataSeleccionada.join(",");
        }


    } else {
        return false;
    }


}//final función
function serializar(arr) {
    var res = 'a:' + arr.length + ':{';
    for (i = 0; i < arr.length; i++) {
        res += 'i:' + i + ';s:' + arr[i].length + ':"' + arr[i] + '";';
    }
    res += '}';
    return res;
}

/**
 * Crea un campo tinyMCE
 *
 * @param string nobreControl
 */

function armarTiny(nombreControl) {
    if (!nombreControl) {
        nombreControl = "textarea.tiny";
    }
    valoresTiny = {
        //mode : "exact",
        selector: nombreControl,
        language: 'es',
        plugins: [
            //"image contextmenu autolink"  //eliminados
            " link charmap print preview anchor",
            "searchreplace code fullscreen",
            "insertdatetime table paste"
        ],
        toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    };
    tinymce.init(valoresTiny);
}

function convertirByteAMb(bytes) {
    return parseInt(((bytes / 1024) / 1024).toFixed(2));
}

function scroll() {
    $("[data-scroll=true]").on('click', function (e) {
        e.preventDefault();

        var $target = $(this.hash);
        $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
        if ($target.length) {
            var targetOffset = $target.offset().top;
            $('html,body').animate({scrollTop: targetOffset}, 900);
            return false;
        }
    });
}




