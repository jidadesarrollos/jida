<?php

namespace App\Config\Cliente;

use App\Config\Configuracion;

/**
 * Archivos JS Requeridos
 * Los archivos definidos en el primer nivel del arreglo serán incluidos siempre sin importar el ambiente de
 * la aplicacion. Si se desea especificar archivos solo para un ambiente, se debe definir una clave con el nombre del ambiente.
 */
class JS {

    private $js = [
        'dev'    => [
            'jq'    => Configuracion::URL_BASE . '/htdocs/bower_components/jquery/dist/jquery.min.js',
            'jq-ui' => Configuracion::URL_BASE . '/htdocs/bower_components/jquery-ui/jquery-ui.min.js',
            'bt'    => Configuracion::URL_BASE . '/htdocs/bower_components/bootstrap/dist/js/bootstrap.min.js'
        ],
        'prod'   => [

        ],
        'jadmin' => [
            'jq'       => Configuracion::URL_BASE . '/htdocs/bower_components/jquery/dist/jquery.min.js',
            'jq-ui'    => Configuracion::URL_BASE . '/htdocs/bower_components/jquery-ui/jquery-ui.min.js',
            'bt'       => Configuracion::URL_BASE . '/htdocs/bower_components/bootstrap/dist/js/bootstrap.min.js',
            'bootbox'  => Configuracion::URL_BASE . '/htdocs/bower_components/bootbox.js/bootbox.js',
            'jd-plugs' => Configuracion::URL_BASE . '/Framework/htdocs/js/dist/jd.plugs.js',
            'jadmin'   => Configuracion::URL_BASE . '/htdocs/js/jida/jadmin.js',
            'admin'    => Configuracion::URL_BASE . '/Framework/htdocs/js/jadmin/admin.js',
            'menu'     => Configuracion::URL_BASE . '/Framework/htdocs/js/libs/menu.js',
            'mustache' => Configuracion::URL_BASE . '/htdocs/bower_components/mustache.js/mustache.min.js',
        ],
    ];

    function js () {

        return $this->js;

    }

    static function archivos () {

        $class = new JS();
        return $class->js();

    }

}
