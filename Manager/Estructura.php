<?php


namespace Jida\Manager;


class Estructura {

    public $_ce = 10009;

    const DIR_APP = 'Aplicacion';
    const DIR_JIDA = 'Framework';

    const NOMBRE_VISTA = 'index';

    static function path () {

        $actual = explode(DS, __DIR__);
        $posicion = array_search(self::DIR_JIDA, $actual);
        $directorio = implode("/", array_chunk($actual, $posicion)[0]);

        return $directorio;

    }

}