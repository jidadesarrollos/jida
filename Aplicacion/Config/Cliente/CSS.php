<?php

namespace App\Config\Cliente;

use Jida\Medios\Debug;

/**
 * Archivos CSS Requeridos
 * Los archivos definidos en el primer nivel del arreglo serán incluidos
 * siempre sin importar el ambiente de la aplicacion. Si se desea especificar archivos solo para un ambiente,
 * se debe definir una clave con el nombre del ambiente.
 */
class CSS {

    private $css = [
        [
            'href' => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/images/favicon-32x32.png',
            'rel'  => 'shortcut icon'
        ],
        'dev'    => [
            'bootstrap' => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/bootstrap.css',
            'font'      => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/font-family.css',
            'style'     => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/style.css',
            'global'    => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/global.css',
            'owl'       => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/owl-carousel/owl.carousel.css',
            'owl-theme' => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/owl-carousel/owl.theme.css',
            'animate'   => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/animate.css',
            'effect2'   => URL_BASE . URL_HTDOCS_TEMAS . 'default/assets/stylesheets/css/effect2.css',

        ],
        'prod'   => [
        ],
        'jadmin' => [
            'bootstrap'    => URL_BASE . URL_BOWER . 'bootstrap/dist/css/bootstrap.min.css',
            'font-awesome' => URL_BASE . URL_HTDOCS_JADMIN . 'css/font-awesome.min.css',
            'admin'        => URL_BASE . URL_HTDOCS_JADMIN . 'css/dist/jadmin.css',
        ]
    ];

    function css() {

        return $this->css;

    }

    static function archivos() {
        Debug::imprimir([\Jida\Manager\Vista\Tema::$url], true);
        $clase = new CSS();

        return $clase->css();

    }

}
