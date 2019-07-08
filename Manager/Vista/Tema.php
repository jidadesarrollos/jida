<?php

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;
use Jida\Core\ObjetoManager;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

class Tema {

    use ObjetoManager;
    static $directorio;
    static $url;

    private $_tema;
    static $configuracion;
    static private $_instancia;
    static private $_ce = 100014;

    private function __construct() {

        $this->_tema = Config::obtener()->tema;
        $this->_rutaDefault();
        $this->_configuracion();

    }

    private function _rutaDefault() {

        self::$directorio = Estructura::$rutaAplicacion . "/Layout/" . $this->_tema;
        self::$url = Estructura::$urlBase . "/Aplicacion/Layout/$this->_tema";

    }

    public function definir($configuracion) {

        if (isset($configuracion['directorio'])) self::$directorio = $configuracion['directorio'];
        if (isset($configuracion['url'])) self::$url = $configuracion['url'];
        if (isset($configuracion['tema'])) {
            $this->_tema = $configuracion['tema'];

            $this->_configuracion();
        }

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

        $conf = Config::obtener();
        $entorno = $conf::ENTORNO_APP;

        if (property_exists($configuracion, $entorno)) {

            foreach ($configuracion->{$entorno} as $propiedad => $valor) {
                $configuracion->{$propiedad} = $valor;
            }
            unset($configuracion->{$entorno});

        }
//        Debug::imprimir([$configuracion], true);
        self::$configuracion = $configuracion;

    }

    static function obtener() {

        if (!self::$_instancia) {
            self::$_instancia = new Tema();
        }

        return self::$_instancia;

    }

    static function propiedad($propiedad) {

        $conf = self::$configuracion;
        if (isset($conf->{$propiedad})) return $conf->{$propiedad};

    }

}