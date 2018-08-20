<?php

namespace App\Config;

use Jida\Configuracion\Config;

class Configuracion extends Config {

    var $logo = '/default/htdocs/images/logo.png';

    const URL_BASE = '';
    const URL_ABSOLUTA = '';
    const PATH_JIDA = "jida";

    var $idiomas = [
        'es' => 'Español'
    ];
    var $mensajes = [
        'error'  => 'alert alert-danger',
        'suceso' => 'alert alert-success',
        'alert'  => 'alert alert-warning',
        'info'   => 'alert alert-info'
    ];

    var $modulos = [
        'contacto'  => 'Contacto',
        'clientes'  => 'Clientes',
        'reseller'  => 'Reseller',
        'correos'   => 'Correos',
        'servicios' => 'Servicios',
        'empresas'  => 'Empresas',
        'panel'     => 'Panel',
        'testing'   => 'Testing',
        'recursos'  => 'Recursos',
        'usuarios'  => 'Usuarios',
        'instagram' => 'Instagram'
    ];

    var $tema = 'preview';

    function __construct () {

        $this->definir('configMensajes', $this->mensajes);
        $this->definir('tema',
                       [
                           'configuracion' => $this->tema
                       ]);

        /**
         * @deprecated 0.6
         */
        $GLOBALS['Configuracion'] = $this;
        $this->modulos['app'] = 'app';

        /**
         * @since 0.6
         */
        $GLOBALS['JIDA_CONF'] = $this;

    }

    private function definir ($variable, $valor) {

        $GLOBALS[$variable] = $valor;

    }

    public function inicio () {

        $GLOBALS['_CSS'] = \App\Config\Cliente\CSS::archivos();
        $GLOBALS['_JS'] = \App\Config\Cliente\JS::archivos();
        $GLOBALS['configJVista'] = [
            'nroFilas' => 5,
        ];

    }

    static function obtener () {

    }
}