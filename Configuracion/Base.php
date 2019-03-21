<?php

namespace Jida\Configuracion;

use Jida\Manager\Estructura;
use Jida\Medios\Directorios;

class Base {

    static function constantes() {

        define('DS', DIRECTORY_SEPARATOR);
        define("PS", PATH_SEPARATOR);
        define('MODELO_USUARIO', '\Jida\Modelos\User');

        /**
         * @constant MANEJADOR_PARAMS
         * Mantiene el funcionamiento del pase de parametros en las URLs entre las distintas versiones del Framework
         * TRUE para utilizar el manejo actualizado desde V-1.4
         * FALSE para versiones inferiores
         * @deprecated
         */
        if (!defined('MANEJADOR_PARAMS'))
            define('MANEJADOR_PARAMS', true);
        if (!defined('NIVEL_ORM'))
            define('NIVEL_ORM', 1);

        if (!defined('PREFIJO_TABLA'))
            define('PREFIJO_TABLA', true);
        if (!defined('PREFIJO_RELACIONAL'))
            define('PREFIJO_RELACIONAL', "r");

        if (!defined('PLURAL_ATONO')) {
            define('PLURAL_ATONO', 's');
        }
        if (!defined('PLURAL_CONSONANTE')) {
            define('PLURAL_CONSONANTE', 'es');
        }

        if (!defined('FECHA_CREACION')) {
            define('FECHA_CREACION', true);
        }
        if (!defined('FECHA_MODIFICACION')) {
            define('FECHA_MODIFICACION', true);
        }
        if (!defined('CODIFICAR_HTML_BD'))
            define('CODIFICAR_HTML_BD', false);

        /**
         * Entornos
         */
        define('dev', 'dev');
        define('prod', 'prod');
        set_time_limit(180);

    }

    static function path() {

        define('ROOT', Estructura::path());
        define('DIR_FRAMEWORK', Estructura::$rutaJida);
        define('DIR_APP', Estructura::$rutaAplicacion);

        if (Directorios::validar(Estructura::$rutaAplicacion . 'index.php')) {
            include_once DIR_APP . DS . 'index.php';
        }

    }
}
