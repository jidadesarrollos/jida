<?php

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

class Tema {

    static $directorio;
    static $url;

    private $_tema;
    static $configuracion;
    static private $_instancia;
    static private $_ce = 100014;

    private function __construct() {

        $this->_tema = (!!Estructura::$jadmin) ? Config::obtener()->temaJadmin : Config::obtener()->tema;
        $this->_definirRuta();
        $this->_configuracion();

    }

    private function _definirRuta() {

        $rutaApp = Estructura::$rutaAplicacion . "/Layout/" . $this->_tema;
        $config = Config::obtener();
        if (Estructura::$jadmin and !Directorios::validar($rutaApp)) {

            //TODO: Manejar ruta para jadmin desde estructura
            self::$url = '//' . Estructura::$urlBase . '/' . $config::PATH_JIDA . '/Jadmin/Layout/' . $this->_tema;
            self::$directorio = Estructura::$rutaJida . "/Jadmin/Layout/$this->_tema";

            return true;

        }

        self::$directorio = Estructura::$rutaAplicacion . "/Layout/" . $this->_tema;
        self::$url = Estructura::$urlBase . "/Aplicacion/Layout/$this->_tema";

    }

    /**
     * Carga el tema del framework
     */
    private function _framework() {

        $config = Config::obtener();
        self::$url = '//' . Estructura::$urlBase . '/' . $config::PATH_JIDA . '/Jadmin/Layout/jadmin';
        self::$directorio = Estructura::$rutaJida . "/Jadmin/Layout/jadmin";
        $this->_leerConfiguracion(self::$directorio . DS . "tema.json");
        $layout = Layout::obtener();
        $layout->_leer();

    }

    /**
     * Obtiene la configuración del tema implementado
     *
     */
    private function _configuracion() {

        try {

            $archivoConfiguracion = self::$directorio . DS . "tema.json";

            if (!file_exists($archivoConfiguracion)) {
                $msj = "No se consigue el archivo de configuracion del tema $this->_tema en " . self::$directorio;
                $this->_framework();
                throw new \Exception($msj, 1);
            }

            $this->_leerConfiguracion($archivoConfiguracion);

        }
        catch (\Exception $e) {
            Excepcion::controller($e);
        }

    }

    private function _leerConfiguracion($archivoConfiguracion) {

        $configuracion = json_decode(file_get_contents($archivoConfiguracion));

        $entorno = Config::ENTORNO_APP;

        if (property_exists($configuracion, $entorno)) {

            foreach ($configuracion->{$entorno} as $propiedad => $valor) {
                $configuracion->{$propiedad} = $valor;
            }
            unset($configuracion->{$entorno});

        }

        self::$configuracion = $configuracion;

    }

    static function obtener() {

        if (!self::$_instancia) {
            self::$_instancia = new Tema();
        }

        return self::$_instancia;

    }
}