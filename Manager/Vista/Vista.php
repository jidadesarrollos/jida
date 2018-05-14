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
     * Define el directorio de la vista solicitada
     *
     * Verifica la ubicación de la vista a partir de los componentes de la url
     *
     * @method _obtenerDirectorio
     *
     */
    private function _obtenerDirectorio () {

        $padre = self::$padre;
        $arranque = $padre::$Padre;

        $directorio = Estructura::path();
        $directorio .= ($arranque::$ruta !== 'jida') ? "/" . Estructura::DIR_APP : "/" . Estructura::DIR_JIDA;

        if (!!$arranque->modulo and !$arranque->jadmin) {
            $directorio .= "/Modulos/" . ucfirst($arranque->modulo);
        }

        if ($arranque->jadmin) {

            $directorio .= "/" . $this->_DIRECTORIOS['jida'];

            if(!!$arranque->modulo) {
                $directorio .= "/Modulos/" . ucfirst($arranque->modulo);
            }

        }

        self::$directorio = $directorio . "/Vistas/" . strtolower($arranque::$controlador);

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
        $arranque = $padre::$Padre;
        $controlador = $padre::$controlador;

        if (!self::$directorio) {
            $this->_obtenerDirectorio();
        }


        $vista = (!!$arranque::$metodo) ? $arranque::$metodo : Estructura::NOMBRE_VISTA;
        $vista = (!!$controlador->vista) ? $controlador->vista : $vista;

        $vista = self::$directorio . "/" . $vista;

        if (strpos($vista, '.php') === false) {
            $vista .= ".php";

        };

        if (!file_exists($vista)) {
            throw new Excepcion('La vista solicitada no existe: ' . $vista, $this->_ce . '1');
        }


        return $vista;
    }
}