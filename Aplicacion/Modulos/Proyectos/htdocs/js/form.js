(function ($) {
    'use strict';

    const RUTAS = {
        'EDITAR': '/jadmin/proyectos/media/gestion',
        'ELIMINAR': '/jadmin/proyectos/media/eliminar',
        'ENVIO': '/jadmin/proyectos/media/carga'
    };

    let $btn = $('#btnCargaImagen');
    let $totalImagenes = $('#total-imas');
    let $galeria = $('.jida-galeria-media');
    let urlEnvio = $btn.data('url-envio');

    function postCarga(respuesta) {

        $('#spanCargaImg').remove();
        if (respuesta.error) {
            $listaImagenes.before(`<div class="alert alert-warning">${respuesta.msj}</div>`);
            $('.jcargafile').remove();

            return;
        }
        let total = $totalImagenes.data('total') + parseInt(respuesta.data.length);
        $totalImagenes.attr('data-total', total);
        $totalImagenes.html(total);

        function renderizar(key, item) {

            if (key in respuesta.data) {

                let $item = $(item);
                let img = JSON.parse(respuesta.data[key].meta_data);
                let parametros = respuesta.ids[key];

                $item.attr('data-imagen', img.sm);
                $item.attr('data-parametros', parametros);
                $item.removeClass('jcargafile');

            }

        }

        $('.jcargafile').each(renderizar());

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

    function crearModal(evento) {

        let target = evento.currentTarget;
        let accion = target.dataset.accion;
        let ruta = (accion === 'gestion') ? RUTAS.EDITAR : RUTAS.ELIMINAR;

        let id = target.closest('.item').dataset.id;

        function procesarRespuesta(respuesta) {

            bootbox.dialog({'message': respuesta});
        }

        $.ajax({
            'url': `${jida.url.base}${ruta}/${id}`

        }).done(procesarRespuesta);

    }

    $galeria.on('click', '[data-accion]', evento => crearModal(evento));

})(jQuery);