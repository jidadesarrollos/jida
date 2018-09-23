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
use Jida\Helpers as Helpers;
use Jida\Manager\Estructura as Estructura;

class Vista {

    use Archivo;
    private $_ce = 10009;
    static public $padre;
    static public $directorio;

    function __construct ($padre) {

        self::$padre = $padre;

    }

    /**
     * Retorna la ruta fisica de la plantilla solicitada
     *
     * @param $plantilla
     * @return bool|string
     */
    function rutaPlantilla ($plantilla) {

        $plantilla = $plantilla . ".php";

        $path = Estructura::path() . DS . Estructura::DIR_APP . DS . "plantillas";
        if (Helpers\Directorios::validar($path . DS . $plantilla)) {
            return $ruta = $path . DS . $plantilla;
        }

        $path = Estructura::path() . DS . Estructura::DIR_JIDA . DS . "plantillas";
        if (Helpers\Directorios::validar($path . DS . $plantilla)) {
            return $path . DS . $plantilla;
        }

        return false;

    }

    function obtener ($plantilla = "") {

        $padre = self::$padre;
        $controlador = $padre::$controlador;

        $vista = (!!Estructura::$metodo) ? Estructura::$metodo : Estructura::NOMBRE_VISTA;
        $vista = (!!$controlador->vista()) ? $controlador->vista() : $vista;

        $modulo = Estructura::$modulo;
        $modulo = (!$modulo && Estructura::$jadmin) ? "jadmin" : "index";

        $vista = Estructura::$rutaModulo . "/Vistas/$modulo/$vista";

        if (strpos($vista, '.php') === false) {
            $vista .= ".php";
        }

        if (!file_exists($vista)) {
            throw new Excepcion('L1a vista solicitada no existe: ' . $vista, $this->_ce . '1');
        }

        return $this->_obtenerContenido($vista);
    }

}