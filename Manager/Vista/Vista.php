<?php

/**
 *
 *
 * Corresponde al numero de excepciones,
 * Actualizar a medida que se vayan agregando excepciones
 * Numero de error 2
 *
 */

namespace Jida\Manager\Vista;

use Exception as Excepcion;
use Jida\Configuracion\Config;
use Jida\Core\Controlador\Control;
use Jida\Manager\Estructura;
use Jida\Manager\Rutas\Arranque;
use Jida\Manager\Textos;
use Jida\Manager\Vista\Render\Common;
use Jida\Medios;

class Vista {

    use Archivo, Common;

    static private $_ce = 10009;
    static public $padre;
    static public $directorio;
    static public $staticURl;
    /**
     * @var Tema
     */
    private $_tema;
    private $_data;

    public $textos;

    function __construct($controlador) {

        $conf = Config::obtener();
        $this->_controlador = $controlador;
        $this->_tema = $conf->tema;
        $this->_data = Data::obtener($controlador);
        $this->url = Estructura::$url;
        $this->urlBase = Estructura::$urlBase;
        $this->textos = Textos::obtener();
        $this->_directory();
    }

    private function _directory() {

        $ruta = Estructura::$rutaModulo;
        $nombre = strtolower(Estructura::$nombreControlador);
        self::$staticURl = Estructura::publicUrl();
        self::$directorio = "{$ruta}/Vistas/$nombre/";

    }

    /**
     * Retorna la ruta fisica de la plantilla solicitada
     *
     * @param $plantilla
     * @return bool|string
     */
    function rutaPlantilla($plantilla) {

        $plantilla = $plantilla . ".php";

        $path = Estructura::path() . DS . Estructura::DIR_APP . DS . "plantillas";

        if (Medios\Directorios::validar($path . DS . $plantilla)) {
            return $ruta = $path . DS . $plantilla;
        }

        $path = Estructura::path() . DS . Estructura::DIR_JIDA . DS . "plantillas";

        if (Medios\Directorios::validar($path . DS . $plantilla)) {
            return $path . DS . $plantilla;
        }

        return false;

    }

    /**
     * @param string $plantilla
     * @return string
     * @throws Excepcion
     */
    function obtener($plantilla = "") {

        /**
         * @var $controlador object Control
         * @see Control
         *
         */
        $controlador = $this->_controlador;
        $vista = (!!Estructura::$metodo) ? Estructura::$metodo : Estructura::NOMBRE_VISTA;
        $vista = (!!$controlador->vista()) ? $controlador->vista() : $vista;

        /**
         * Validamos si es un directorio
         */
        $directory = self::$directorio . $vista;
        if (is_dir($directory)) {
            $viewName = Medios\Directorios::validar(self::$directorio . "/view.php");
            $viewName = ($viewName) ? '/view' : '/' . $vista;
            $vista = $directory . $viewName;
        }
        else {
            $directory = self::$directorio;
            $vista = $directory . $vista;
        }

        $hasModule = Medios\Directorios::validar($directory . "/module.json");

        if (strpos($vista, '.php') === false) $vista .= ".php";

        if (!file_exists($vista)) {
            $msj = "La vista solicitada no existe: {$vista}";
            \Jida\Manager\Excepcion::procesar($msj, $this->_ce . '1');
        }

        $contenido = $this->_obtenerContenido($vista);

        if ($hasModule) {
            $contenido .= $this->_addClientModule($directory);
        }

        return $contenido;

    }

    private function _addClientModule($directorio) {

        $module = json_decode(file_get_contents($directorio . "/module.json"));
        $bundle = property_exists($module, 'bundle') ? $module->bundle : 'code';
        $file = self::$staticURl . "/$bundle";
        /**
         * Se agrega el doble slash para que la ruta sea incluida desde la raiz siempre.
         *
         */
        $file = "//" . implode("/", array_filter(explode('/', $file)));
        $config = Config::obtener();
        $version = $config::VERSION;
        return "\n\t\t<script type=\"module\" src=\"{$file}.js?v={$version}\"></script>";

    }

    function obtenerPlantilla($plantilla) {

        if (!Medios\Directorios::validar($plantilla)) {
            \Jida\Manager\Excepcion::procesar('La plantilla no existe ' . $plantilla, self::$_ce . 2);
        }

        return $this->_obtenerContenido($plantilla);

    }

    function error($code) {

        $tema = Tema::obtener();
        $dir = $tema::$directorio;

        $path = "$dir./errors/${code}.php";
        //validar si existe una vista para el error
        if (!Medios\Directorios::validar($path)) $path = Estructura::$rutaJida . "/plantillas/error/error.php";

        return $this->_obtenerContenido($path);

    }

}