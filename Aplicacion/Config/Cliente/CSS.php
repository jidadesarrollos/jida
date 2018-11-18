<?php

namespace App\Config\Cliente;

use App\Config\Configuracion;

/**
 * Archivos CSS Requeridos
 * Los archivos definidos en el primer nivel del arreglo serán incluidos
 * siempre sin importar el ambiente de la aplicacion. Si se desea especificar archivos solo para un ambiente,
 * se debe definir una clave con el nombre del ambiente.
 */
class CSS {

    private $css = [
        [
            'href' => Configuracion::URL_BASE . "/htdocs/img/favicon/favicon-32x32.png",
            'rel'  => 'shortcut icon'
        ],
        'dev'    => [
            'bootstrap' => Configuracion::URL_BASE . '/htdocs/bower_components/bootstrap/dist/css/bootstrap.min.css',
            'principal' => Configuracion::URL_BASE . '/Aplicacion/Layout/default/htdocs/css/principal.css'
        ],
        'prod'   => [

        ],
        'jadmin' => [
            'bootstrap'    => Configuracion::URL_BASE . '/htdocs/bower_components/bootstrap/dist/css/bootstrap.min.css',
            'font-awesome' => Configuracion::URL_BASE . '/Framework/htdocs/css/font-awesome.min.css',
            'admin'        => Configuracion::URL_BASE . '/Framework/htdocs/css/dist/jadmin.css'
        ]
    ];

    function css () {

        return $this->css;

    }

    static function archivos () {

        $clase = new CSS();
        return $clase->css();

    }

}
