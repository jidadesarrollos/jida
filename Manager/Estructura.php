<?php
/*
 * Codigo de Error: 1
 */

namespace Jida\Manager;


use Jida\Configuracion\Config;
use Jida\Helpers\Debug;

class Estructura {

    static public $_ce = 10009;

    const DIR_APP = 'Aplicacion';
    const DIR_JIDA = 'Jida';

    const NOMBRE_VISTA = 'index';

    private static $url;

    static private function _obtenerDirectorio ($actual) {

        $actual = explode(DS, __DIR__);
        $conf = Config::obtener();

        $pathjida = $conf::PATH_JIDA;
        $posicion = array_search($pathjida, $actual);

        if ($posicion === false) {
            $msj = 'La ruta del Jida Framework no se encuentra definida correctamente, Ruta definida: ' . $pathjida;
            throw new \Exception($msj, self::$_ce . 1);
        }

        $band = true;
        $siguiente = $actual;
        $cont = 0;

        while ($band) {

            $siguiente = array_chunk($siguiente, $posicion)[1];
            $posicion = array_search($pathjida, $siguiente);

            if ($posicion === false) {
                Debug::imprimir("ak", true);
                $band = false;
            }
            else {
                $item = ($cont === 0) ? $actual : $siguiente;
                Debug::imprimir($item, "ak", $posicion, true);
                $siguiente = array_chunk($item, $posicion)[0];
            }

        }

        $directorio = implode("/", $siguiente);

        return $directorio;

    }

    static function path () {

        $directorio = self::_obtenerDirectorio();
        self::url();

        return $directorio;

    }

    /**
     * Retorna la url actual de la aplicación en ejecución
     *
     * @method url
     *
     * @since 0.6.1
     * @return mixed
     */
    static function url () {

        if (array_key_exists('REQUEST_URI', $_SERVER)) {
            self::$url = $_SERVER['REQUEST_URI'];
            $conf = Config::obtener();
            #Debug::imprimir($conf::URL_BASE, self::$url, true);

        }
        else {
            Debug::imprimir("No existe REQUEST_URI", true);
        }

        return self::$url;

    }

}