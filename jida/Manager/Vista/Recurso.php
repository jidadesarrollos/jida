<?php
/**
 * Manejador de recursos solicitados por la vista
 *
 * codigo error: 1
 */

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;

class Recurso {

    private $_ce = 100013;

    private static $instancia;


    static function obtener () {

        $configuacion = Config::obtener();
        $tema = $configuacion->tema;

        if (!self::$instancia) {
            self::$instancia = new Recurso();
        }

        return self::$instancia;

    }
}