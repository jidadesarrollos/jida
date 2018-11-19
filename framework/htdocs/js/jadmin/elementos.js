(function ($) {
    var total = $("body").data('total');
    console.log("paso");

    if (!$("body").data('total'))
        $("body").data('total', 1);
    $('[data-jadmin="elementos"]').on('click', function () {
        var $this = $(this);
        var area = $this.data('area');
        console.log(area);
        $('body').data('area', area);
        var $elementContainer = $('#containerElementos');
        console.log($elementContainer);
        $elementContainer.addClass('open animated fadeInUpBig');

    });
    $('.area-elemento-container').on('click', '[data-jadmin="save-elemento"]', saveElemento);
    $('[data-jadmin="clone-form"]').on('click', function () {
        $this = $(this);
        $parent = $this.parent();
        $broda = $parent.find('.elemento-form form');

        addElementoArea($broda.clone(), $broda.parent().data('elemento'));
        $('#containerElementos').removeClass('fadeInUpBig').addClass('FadeOutDownBig');
        setTimeout(function () {
            $('#containerElementos').removeClass().addClass('seccion-elementos');
        }, 200);
    });

    console.log($('[data-jida="hide"]'));

    $('[data-jida="hide"]').on('click', function () {
        var $this = $(this);
        var $elemento = $($this.data('elemento'));
        console.log($elemento, $this.data('elemento'));

        $elemento.addClass('animate fadeOut');
        window.setTimeout(function () {
            $elemento.removeClass('open animated fadeOut');
        }, 200);
    });

})(jQuery);


function addElementoArea($ele, elemento) {
    console.log(elemento);


    if (typeof total == 'undefined') {
        total = 1;
        $('body').data('total', total);
    }
    var idForm = $ele.attr('id') + "-" + total;
    $ele.attr('id', idForm);
    $ele.find(':input').each(function (k, v) {
        $input = $(v);
        $input.attr('id', $input.attr('id') + "-" + total);
    });
    total = $("body").data('total');
    tpl = "";
    tpl += '<article class="contenedor-element"><section class="panel-heading" data-toggle="collapse" data-target="#ele-' + $input.attr('id') + "-" + total + '">';
    tpl += '<h4 class="area-elemento-nombre">' + elemento.nombre + '<span class="pull-right fa fa-chevron-down"></span></h4>';
    tpl += '</section>';
    tpl += '<section class="panel-body" id="ele-' + $input.attr('id') + "-" + total + '">';
    tpl += $ele.prop('outerHTML');
    tpl += '</section>';
    tpl += '<section class="panel-footer">';
    tpl += '<button class="btn btn-default" data-elemento="' + elemento.id + '" data-jadmin="save-elemento" data-form="' + idForm + '" onclick="saveElemento"><span class="fa fa-save"></span></button>';
    tpl += '</section></article>';
    area = $('body').data('area');
    $('#body-' + area).append(tpl);
    $("body").data('total', total + 1);
}

function saveElemento() {
    $this = $(this);
    $parent = $this.parents('form');
    var data = {};
    $form = $("#" + $this.data('form'));
    $inputs = $form.find(':input');
    console.log($inputs);
    $.each($inputs, function (k, input) {
        data[input.name] = input.value;
    });
    data.area = $('body').data('area');
    data.elemento = $this.data('elemento');
    data.btnGuardarElemento = true;
    console.log(data);
    $a = new jd.ajax({
        url: '/jadmin/elementos/guardar',
        parametros: data,

        funcionCarga: function () {
            console.log(this.respuesta);
        }
    });
    console.log($form, data);
}
