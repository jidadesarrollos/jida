<?php

/**
 *
 *
 * Corresponde al numero de excepciones,
 * Actualizar a medida que se vayan agregando excepciones
 * Numero de error 1
 *
 */

namespace Jida\Manager\Vista;

use Exception as Excepcion;
use Jida\Configuracion\Config;
use Jida\Manager\Estructura as Estructura;
use Jida\Medios as Medios;

class Vista {

    use Archivo, Render;

    static private $_ce = 10009;
    static public $padre;
    static public $directorio;

    private $_tema;
    private $_data;

    function __construct($padre) {

        self::$padre = $padre;
        $conf = Config::obtener();
        $this->_tema = $conf->tema;

        $this->_data = $padre->data;

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

    function obtener($plantilla = "") {

        $controlador = \Jida\Manager::controlador();

        $vista = (!!Estructura::$metodo) ? Estructura::$metodo : Estructura::NOMBRE_VISTA;
        $vista = (!!$controlador->vista()) ? $controlador->vista() : $vista;

        $controlador = Estructura::$controlador;

        if (!$controlador) {
            $controlador = (!!Estructura::$jadmin) ? "jadmin" : "index";
        }

        $vista = Estructura::$rutaModulo . "/Vistas/" . strtolower("$controlador/$vista");

        if (strpos($vista, '.php') === false) {
            $vista .= ".php";
        }

        if (!file_exists($vista)) {
            throw new Excepcion('L1a vista solicitada no existe: ' . $vista, $this->_ce . '1');
        }

        return $this->_obtenerContenido($vista);
    }

}