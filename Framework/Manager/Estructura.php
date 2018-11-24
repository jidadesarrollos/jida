<?php
/*
 * Codigo de Error: 2
 * TODO: Repasar definicion de propiedades directorios y rutas.
 */

namespace Jida\Manager;

use Jida\Configuracion\Config;
use Jida\Medios\Debug;
use Jida\Manager\Rutas\Arranque;

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

    static public $namespace;
    static public $ruta;
    static public $rutaJida;
    static public $modulo;
    static public $controlador;
    static public $metodo;
    static public $jadmin;
    static public $rutaModulo;

    /**
     * @return string
     * @throws \Exception
     */
    static private function _obtenerDirectorio () {

        $actual = explode(DS, __DIR__);
        unset($actual[array_search('Manager', $actual)]);
        $conf = Config::obtener();
        $carpeta = $conf::PATH_JIDA;
        $cuenta = array_count_values($actual);

        if (!array_key_exists($carpeta, $cuenta)) {
            throw new \Exception("La carpeta especificada para el Jida no existe", self::$_ce . 2);
        }

        $inverso = array_reverse($actual);
        $posicion = array_search($carpeta, $inverso) + 1;

        $parte = array_splice($inverso, $posicion);
        $directorio = implode("/", array_reverse($parte));
        self::$rutaJida = $directorio . DS . $carpeta;
        self::$directorio = $directorio;

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
        }
        catch (Excepcion $e) {
            Debug::imprimir($e);
        }

    }

    /**
     * Define la estructura modular en ejecución del Framework
     *
     * Disponibiliza los valores del modulo, controlador y metodo
     * utilizados, así como el namespace y la ruta
     *
     * @param Arranque $arranque
     */
    static public function definir (Arranque $arranque) {

        self::$ruta = $arranque::$ruta;
        self::$modulo = $arranque::$modulo;
        self::$controlador = $arranque::$controlador;
        self::$metodo = $arranque::$metodo;
        self::$jadmin = $arranque->jadmin;

    }

}