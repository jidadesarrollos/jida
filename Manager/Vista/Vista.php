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
use Jida\Medios;

class Vista {

    use Archivo, Render;

    static private $_ce = 10009;
    static public $padre;
    static public $directorio;
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

        $ruta = Estructura::$rutaModulo;

        $nombre = Estructura::$nombreControlador;
        $archivoVista = strtolower("$nombre/$vista");
        $vista = "{$ruta}/Vistas/{$archivoVista}";

        if (strpos($vista, '.php') === false) {
            $vista .= ".php";
        }

        if (!file_exists($vista)) {
            throw new Excepcion('La vista solicitada no existe: ' . $vista, $this->_ce . '1');
        }

        return $this->_obtenerContenido($vista);

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