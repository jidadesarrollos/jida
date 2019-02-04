<?php

namespace App\Config;

use Jida\Configuracion\Config;

class Configuracion extends Config {

    var $logo = 'default/htdocs/images/logo.png';

    const NOMBRE_APP = 'Jonathan Jacobsen';

    const URL_BASE = '';
    const URL_ABSOLUTA = '';
    const PATH_JIDA = "Framework";

    const ENVIAR_EMAIL_ERROR = false;
    const EMAIL_SOPORTE = 'soporte@jidadesarrollos.com';

    var $mensajes = [
        'error'  => 'alert alert-danger',
        'suceso' => 'alert alert-success',
        'alert'  => 'alert alert-warning',
        'info'   => 'alert alert-info'
    ];

    var $tema = 'jacobsen';

    var $idiomas = [
        'es' => 'Español'
    ];

    static $modulos = [
        'Categorias',
        'Proyectos'
    ];

    const REDIMENSION_IMAGEN = [
        '150x150',
        '400x400',
        '800x800',
        '1200x1200'
    ];

    function __construct() {

        $this->definir('configMensajes', $this->mensajes);
        $this->definir('tema',
            [
                'configuracion' => $this->tema
            ]);

        /**
         * @deprecated 0.6
         */
        $GLOBALS['Configuracion'] = $this;
        self::$modulos['app'] = 'app';

        /**
         * @since 0.6
         */
        $GLOBALS['JIDA_CONF'] = $this;

    }

    private function definir($variable, $valor) {

        $GLOBALS[$variable] = $valor;

    }

    public function inicio() {

    }

    static function obtener() {

    }

}