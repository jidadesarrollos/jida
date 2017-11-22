$(document).ready(function () {
    'use strict';

    if ($('[data-selectall]').length > 0) {

        var $seleccionador = $('[data-selectall]');

        $seleccionador.on('click', function () {

            console.log("click");
            var $this = $(this);
            var seleccion = $this.data('selectall');
            $(seleccion).each(function () {
                this.checked = $this.prop('checked');
            });

        });

        $($seleccionador.data('selectall')).on('click', function () {

            var selectorAll = $seleccionador.data('selectall');

            if ($(selectorAll + ':checked').lenght == $seleccionador.length) {
                $seleccionador.prop('checked', true);
            } else {
                $seleccionador.prop('checked', false);
            }

        });
    }

});
