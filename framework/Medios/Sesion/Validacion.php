<?php

namespace Jida\Medios\Sesion;

use Jida\Manager\Excepcion;

Trait Validacion {

    static private $_deprecados = [
        'set',
        'get',
        'sessionLogin',
        'checkAcceso'
    ];

    function __call($metodo, $parametros) {

        if (in_array($metodo, self::$_deprecados)) {
            Excepcion::procesar("La funcion llamada se encuentra deprecada", self::$_ce);
        }

    }
}
