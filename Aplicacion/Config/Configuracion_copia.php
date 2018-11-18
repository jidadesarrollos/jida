<?php

namespace App\Config;

use Jida\Configuracion\Config;

class Configuracion extends Config {

    var $logo = 'default/htdocs/images/logo.png';

    const NOMBRE_APP = 'Aplicación Jida';

    const URL_BASE = '';
    const URL_ABSOLUTA = '';
    const PATH_JIDA = "Framework";

    const ENVIAR_EMAIL_ERROR = false;
    const EMAIL_SOPORTE = 'jcontreras@jidadesarrollos.com';

    var $mensajes = [
        'error'  => 'alert alert-danger',
        'suceso' => 'alert alert-success',
        'alert'  => 'alert alert-warning',
        'info'   => 'alert alert-info'
    ];

    var $tema = 'default';

    var $idiomas = [
        'es' => 'Español'
    ];

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