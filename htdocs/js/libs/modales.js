/*
(function ($) {
    'use strict';

    $('[data-modal=true]').on('click', function (e) {

        e.preventDefault();
        var $this = $(this);
        var url = $this.attr('href');

        $.ajax({
            type: "GET",
            dataType: "html",
            url: url,
        }).done(function (data) {
            bootbox.dialog({'message': data});
        });

    });


})(jQuery);
*/