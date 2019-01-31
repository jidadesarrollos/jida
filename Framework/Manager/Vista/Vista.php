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
use Jida\Manager\Estructura as Estructura;
use Jida\Medios as Medios;

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

    public $url;

    function __construct($data) {

        $conf = Config::obtener();
        $this->_tema = $conf->tema;

        $this->_data = $data;

        $this->url = Estructura::$url;

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
            throw new Excepcion('La vista solicitada no existe: ' . $vista, $this->_ce . '1');
        }

        return $this->_obtenerContenido($vista);

    }

    function obtenerPlantilla($plantilla) {

        try {
            if (!Medios\Directorios::validar($plantilla)) {
                throw new \Exception('La plantilla no existe ' . $plantilla, self::$_ce . 2);
            }

            return $this->_obtenerContenido($plantilla);
        }
        catch (\Exception $e) {
            Medios\Debug::imprimir(
                ["Error vista",
                    $e->getCode(),
                    $e->getMessage()],
                true
            );
        }

    }

    function navegar($url) {
        return Estructura::$urlBase . $url;
    }

}