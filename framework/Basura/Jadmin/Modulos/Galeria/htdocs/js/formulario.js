$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

var guardarMedia = function () {
//	console.log("en guardar media");
    var $form = $('#formGestionObjetoMedia');
    var $btn = $('#btnGestionObjetoMedia');
    var data = {'btnGestionObjetoMedia': true, 'id_objeto_media': $btn.data('id')};

    var dataS = $form.serializeArray();

    for (key in dataS) {

        data[dataS[key].name] = dataS[key].value;
    }

    // var formData = new FormData(document.getElementById('formGestionObjetoMedia'));
    jd.ajax({
        data: {
            url: '/jadmin/galeria/editar-media',
            data: data,
            type: 'POST',
            method: 'post',
            dataType: 'json',
        },
        done: function (resp) {
            console.log("after call");
            $('.alert').remove();
            alert = (resp.ejecutado) ? 'alert-success' : 'alert-warning';
            $form.before('<div class="alert ' + alert + '">' + resp.msj + '</div>');

        }
    });
};

(function ($) {
    'use strict';
    var $btn = $('#btnGestionObjetoMedia');
    $btn.jValidador({post: guardarMedia});

})(jQuery);
