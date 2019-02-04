(function ($) {
    'use strict';

    let btn = $('#btnFormularioMedia').get(0);
    let page = $('#gestion-media-page').get(0);
    let $form = $(page.querySelector('form'));

    const tplMensaje = `<div class="{{css}}">{{mensaje}}</div>`;
    const CSS_MENSAJES = {
        'error': 'alert alert-danger',
        'success': 'alert alert-success'
    };

    let btnCierre = page.querySelector('.btn-cierre');

    btnCierre.addEventListener('click', () => bootbox.hideAll());

    function imprimirMensaje(tipo, mensaje) {

        let alert = page.querySelector('.alert');
        if (alert) {
            alert.innerHTML = '';
            alert.insertAdjacentHTML('afterbegin', mensaje);
            return;
        }

        let titulo = page.querySelector('.titulo');
        let plantilla = Mustache.render(tplMensaje, {
            'mensaje': mensaje,
            'css': CSS_MENSAJES[tipo]
        });

        titulo.insertAdjacentHTML('afterend', plantilla);

    }

    function enviarForm(evento, form, boton) {

        let formData = new FormData(form);
        let $target = $(boton);
        formData.append('btnMedia', true);
        $target.attr({
            'value': 'Guardando...',
            'disabled': true
        });

        $.ajax({
            'url': form.action,
            'data': formAObjeto(formData),
            'type': 'post',
            'dataType': 'json'

        }).done(respuesta => {

            $target.attr({
                'value': 'Guardar',
                'disabled': false
            });

            if (!respuesta.estatus) {
                imprimirMensaje('error', 'No se ha podido guardar, intente nuevamente.');
                return;
            }

            imprimirMensaje('success', 'Datos guardados');

        });

    }

    $form.on('jida:form.validado', enviarForm);

    //btn.addEventListener('click', sendForm);

})(jQuery);