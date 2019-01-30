(function ($) {
    'use strict';

    let $btn = $('#btnCargaImagen');
    let $totalImagenes = $('#total-imas');
    let $galeria = $('.jida-galeria-media');
    let urlEnvio = $btn.data('url-envio');

    function postCarga(respuesta) {

        $('#spanCargaImg').remove();
        if (respuesta.error) {
            $listaImagenes.before(`<div class="alert alert-warning">${respuesta.msj}</div>`);
            $('.jcargafile').remove();
        }
        else {

            let total = $totalImagenes.data('total') + parseInt(respuesta.data.length);
            $totalImagenes.attr('data-total', total);
            $totalImagenes.html(total);

            $('.jcargafile').each(function (key, item) {

                if (key in respuesta.data) {

                    let $item = $(item);
                    let img = JSON.parse(respuesta.data[key].meta_data);

                    let parametros = respuesta.ids[key];
                    $item.attr('data-imagen', img.sm);
                    $item.attr('data-parametros', parametros);

                    $item.removeClass('jcargafile');

                }
            });
        }
    }

    function onload(e) {

        let image = new Image();
        let ele = e.currentTarget;
        let tplMensaje = '<span id="spanCargaImg" class="label label-info">Guardando Imagen...</span>';

        image.src = ele.result;
        image.className = 'responsive';

        $('#mensaje-carga').after(tplMensaje);

        $('#preview-img').html(image);

        let tpl = $('#imgTemplate').html();
        let render = Mustache.render(tpl, {'src': ele.result, 'alt': 'Imagen Preview'});

        $galeria.append(render);

    }

    $btn.jCargaFile({

        'name': 'imagen',
        'multiple': true,
        'parametros': {'modelo': 'este es el modelo'},
        'url': urlEnvio,
        'onLoad': onload,
        'postCarga': postCarga
    });

})(jQuery);