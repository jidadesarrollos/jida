(function ($) {
    'use strict';

    let pageDiv = $('#eliminar-media-page');

    let btn = $('#btnEliminarMedia').get(0);
    let page = pageDiv.get(0);
    let id = pageDiv.attr('data-id');
    //let $form = $(page.querySelector('form'));

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

    function eliminarFoto(id) {

        $.ajax({
            'url': `/jadmin/proyectos/media/eliminar/${id}/1`,
            'type': 'post',
            'data': {'ejecutar': true},
            'dataType': 'json'

        }).done(respuesta => {

            if (!respuesta.estatus) {
                imprimirMensaje('error', 'No se ha podido eliminar la foto, intente nuevamente.');
                return;
            }

            imprimirMensaje('success', 'Foto eliminada');

        });

    }

    //$form.on('jida:form.validado', enviarForm);

    btn.addEventListener('click', function () {
        eliminarFoto(id)
    });

})(jQuery);