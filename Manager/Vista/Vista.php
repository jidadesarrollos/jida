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

use Jida\Helpers as Helpers;
use Jida\Manager\Estructura as Estructura;
use Exception as Excepcion;

class Vista {

    private $_ce = 10009;
    private $_directorio;
    private $_nombre;
    private $_data;

    static public $padre;
    private $_DIRECTORIOS = [

        'jida' => 'Jadmin',
        'app'  => ''
    ];

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

    function obtener () {

        $padre = self::$padre;
        $controlador = $padre::$controlador;

        $directorio = Estructura::$directorio;
        $vista = (!!Estructura::$metodo) ? Estructura::$metodo : Estructura::NOMBRE_VISTA;
        $vista = (!!$controlador->vista()) ? $controlador->vista() : $vista;

        $vista = $directorio . "/" . $vista;

        if (strpos($vista, '.php') === false) {
            $vista .= ".php";
        };

        if (!file_exists($vista)) {
            throw new Excepcion('L1a vista solicitada no existe: ' . $vista, $this->_ce . '1');
        }

        return $vista;
    }
}