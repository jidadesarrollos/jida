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
    /**
     * @var string $url Url completa solicitada
     */
    static $url;
    /**
     * Estracto de la url correspondiente al dominio
     * @var string $dominio
     */
    static $dominio;
    /**
     * @var string $urlRuta sección de la URL correspondiente al dominio
     */
    static $urlBase;
    /**
     * @var string urlRuta Seccion de la url posterior al dominio
     */
    static $urlRuta;
    /**
     * @var string $directorio Directorio raiz en el que se ubica el proyecto
     */
    static $directorio;
    /**
     * @var array $partes Arreglo de partes de la url
     */
    static $partes;
    /**
     * Carpeta o directorio en donde se encuentra el JidaFramework.
     *
     * @var string $directorioJida
     */
    static $directorioJida;

    static private function _obtenerDirectorio () {

        $actual = explode(DS, __DIR__);
        $conf = Config::obtener();

        $posicion = array_search($conf::PATH_JIDA, $actual);
        $inverso = array_reverse($actual);

        $parte = array_splice($inverso, $posicion);
        $directorio = implode("/", array_reverse($parte));

        self::$directorio = $directorio;
        Debug::imprimir();

        return $directorio;

    }

    static function path () {

        if (!self::$directorio)
            self::_obtenerDirectorio();

        return self::$directorio;

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

        if (!self::$url)
            self::procesar();

        return self::$url;

    }

    static function procesar ($directorioJida) {
        try {
            self::_obtenerDirectorio();
            self::$directorioJida = $directorioJida;

            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

            unset($_GET['url']);

            $url = str_replace([
                                   '.php',
                                   '.html',
                                   '.htm'
                               ],
                               '',
                               $url);

            $url = explode('/', $url);

            self::$partes = array_filter($url,
                function ($var) {

                    return !!$var;
                });

            $pathDominio = str_replace("index.php", "", $_SERVER['PHP_SELF']);

            self::$urlBase = $_SERVER['SERVER_NAME'] . $pathDominio;
            self::$dominio = self::$urlBase;
            $url = implode("/", $url);
            self::$urlRuta = $pathDominio;

            self::$url = self::$dominio . $url;
        }catch (Excepcion $e) {
            Debug::imprimir($e);
        }


    }

}