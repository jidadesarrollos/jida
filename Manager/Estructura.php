<?php
/*
 * Codigo de Error: 2
 * TODO: Repasar definicion de propiedades directorios y rutas.
 */

namespace Jida\Manager;

use Jida\Manager\Rutas\Arranque;
use Jida\Medios\Debug;

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
     *
     * @var string $dominio
     */
    static $dominio;

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
    private static $directorioJida;

    static public $namespace;
    static public $modulo;
    static public $controlador;
    static public $nombreControlador;
    static public $metodo;
    static public $parametros = [];
    static public $idioma;
    /**
     * @var string $urlRuta sección de la URL correspondiente al dominio
     */
    static $urlBase;
    /**
     * @var string urlRuta Seccion de la url posterior al dominio
     */
    static $urlRuta;
    /**
     * @var $urlHtdocs url publica para archivos clientes
     */
    static $urlHtdocs;
    /**
     * @var string $urlModulo URL publica del modulo en ejecución.
     */
    static $urlModulo;
    static public $urlJida;
    /**
     * @var subdirectorio donde se encuentra ubicado el jida.
     */
    public static $rutaJida;
    static public $ruta;
    static public $rutaAplicacion;
    static public $rutaModulo;

    private static $ubicacionJida;

    /**
     * @return string
     * @throws \Exception
     */
    static private function _obtenerDirectorio($directorioJida, $directorioApp) {

        self::$rutaJida = $directorioJida;
        self::$directorio = $directorioApp;

        self::$rutaAplicacion = $directorioApp . "/Aplicacion";
        self::$ubicacionJida = implode(array_filter(
            explode(DIRECTORY_SEPARATOR, str_replace($directorioApp, "", $directorioJida))
        ), "/");

        return $directorioJida;

    }

    static function path() {

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
    static function url() {

        if (!self::$url)
            self::procesar();

        return self::$url;

    }

    static function procesar($directorioJida, $directorioApp) {

        try {
            self::_obtenerDirectorio($directorioJida, $directorioApp);
            self::$directorioJida = $directorioJida;

            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

            unset($_GET['url']);

            $url = str_replace([
                '.php',
                '.html',
                '.htm'
            ], '', $url);

            $url = explode('/', $url);

            self::$partes = array_filter($url, function ($var) {
                return !!$var;
            });

            $pathDominio = str_replace(["index.php", "index"], "", $_SERVER['PHP_SELF']);

            $url = implode("/", $url);
            if ($url === 'index') $url = '';

            self::$urlBase = "//" . rtrim($_SERVER['SERVER_NAME'] . $pathDominio, "/");
            self::$dominio = self::$urlBase;
            self::$urlRuta = rtrim($pathDominio, '/');
            self::$urlHtdocs = self::$urlBase . '/htdocs/';
            self::$urlJida = self::$urlBase . "/" . self::$ubicacionJida;

            self::$url = rtrim(self::$dominio . "/$url", "/");

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
    static public function definir(Arranque $arranque) {

        self::$ruta = $arranque::$ruta;

    }

    static function imprimir() {

        $reflection = new \ReflectionClass(__CLASS__);
        $properties = $reflection->getProperties();

        echo "<pre>";
        foreach ($properties as $key => $prop) {
            echo $prop->getName() . ": ";
            print_r($prop->getValue());
            echo "<br>";
        }
        echo "<pre/>";

    }
}