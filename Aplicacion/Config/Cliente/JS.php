<?php

namespace App\Config\Cliente;

/**
 * Archivos JS Requeridos
 * Los archivos definidos en el primer nivel del arreglo serán incluidos siempre sin importar el ambiente de
 * la aplicacion. Si se desea especificar archivos solo para un ambiente, se debe definir una clave con el nombre del
 * ambiente.
 */
class JS {

    private $js = [
        'dev'    => [
            'modernizr'  => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/modernizr.custom.js',
            'jq'         => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/jquery.js',
            'bt'         => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/bootstrap.js',
            'custom'     => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/custom.js',
            'owl'        => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/owl-carousel/owl.carousel.js',
            'jscroll'    => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/jscroll/jquery.jscroll.js',
            'classie'    => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/classie.js',
            'pathLoader' => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/pathLoader.js',
            'main'       => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/javascripts/main.js',
        ],
        'prod'   => [
        ],
        'jadmin' => [
            'jq'       => URL_BASE . URL_BOWER . 'jquery/dist/jquery.min.js',
            'jq-ui'    => URL_BASE . URL_BOWER . 'jquery-ui/jquery-ui.min.js',
            'bt'       => URL_BASE . URL_BOWER . 'bootstrap/dist/js/bootstrap.min.js',
            'bootbox'  => URL_BASE . URL_BOWER . 'bootbox.js/bootbox.js',
            'jd-plugs' => URL_BASE . URL_HTDOCS_JADMIN . 'js/dist/jd.plugs.js',
            'admin'    => URL_BASE . URL_HTDOCS_JADMIN . 'js/jadmin/admin.js',
            'menu'     => URL_BASE . URL_HTDOCS_JADMIN . 'js/libs/menu.js',
            'moment'   => URL_BASE . URL_BOWER . 'moment/moment.js',
            'mustache' => URL_BASE . URL_BOWER . 'mustache.js/mustache.min.js',
            'jadmin'   => URL_BASE . URL_JS . 'jadmin.js',
        ]
    ];

    function js() {

        return $this->js;

    }

    static function archivos() {

        $class = new JS();

        return $class->js();

    }
}
