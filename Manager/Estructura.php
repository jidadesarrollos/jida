<?php


namespace Jida\Manager;


use Jida\Configuracion\Config;
use Jida\Helpers\Debug;

class Estructura {

    public $_ce = 10009;

    const DIR_APP = 'Aplicacion';
    const DIR_JIDA = 'Framework';

    const NOMBRE_VISTA = 'index';

    private static $url;

    static function path () {

        $actual = explode(DS, __DIR__);
        $posicion = array_search(self::DIR_JIDA, $actual);
        $directorio = implode("/", array_chunk($actual, $posicion)[0]);

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