(function ($) {
    "use strict";

    if ($("[data-dependiente]").length > 0) {

        $("[data-dependiente]").each(function (valor, campo) {

            var $campo = $(campo);
            var padre = $campo.data('dependiente');
            var urlAccion = $campo.data('accion');
            var tpl = $('#optionTemplate').html();

            function limpiar($campo) {

                var render = Mustache.render(tpl, {
                    'value': '',
                    'option': 'Seleccione...'
                });
                var $hijos = $('[data-dependiente="' + $campo.attr('id') + '"]');
                $campo.html(render);
                if ($hijos.length > 0) {
                    limpiar($hijos);
                }

            };
            limpiar($campo);

            $("#" + padre).on('change', function () {

                var $padre, id, v, data;
                $padre = $(this);
                id = $padre.attr('id');
                v = $padre.val();
                data = new Object();

                data[id] = v;

                $.ajax({
                    'method': "GET",
                    'url': urlAccion + v,
                    'data': data,
                    'dataType': 'json'
                }).done(function (ajax) {

                    limpiar($campo);
                    if (ajax.respuesta) {

                        ajax.data.forEach(function (item, index) {

                            var keys = Object.keys(item);
                            var render = Mustache.render(tpl, {
                                'value': item[keys[0]],
                                'option': item[keys[1]]
                            });
                            $campo.append(render);

                        });

                    }
                });
            });
        });
    }

})(jQuery);