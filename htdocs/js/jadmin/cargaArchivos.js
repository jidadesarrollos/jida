(function () {

    $('#cargaArchivo').jCargaFile({
        'multiple': 'multiple',
        'name': 'cargaArchivo',
        'url': '/jadmin/galeria/carga-form',

        'postCarga': function (resp) {

        }
    });

})(jQuery);
