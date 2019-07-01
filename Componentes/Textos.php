<?php

namespace Jida\Componentes;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

class Textos {

    /**
     * Codigo de excepcion para el objeto
     *
     * @var $_ce ;
     */
    static private $_ce = 100;

    public function __construct() {

    }

    private function _cargarTextos($archivo, $ruta) {

        try {

            if (!strpos($archivo, ".json")) {
                $archivo = $archivo . ".json";
            }

        }
        catch (\Exception $e) {
            Debug::imprimir($e->getMessage() . $e->getCode(), $e->getTrace());
        }

    }

    private function _obtenerDirectorio($archivo) {

        $archivo = strtolower($archivo);
        $partes = array_filter(explode("/", $archivo));

        $modulo = array_shift($partes);

        if (count($partes) === 1) {
            return Estructura::$rutaAplicacion . "/Textos/" . $archivo;
        }

        if (count($partes) > 1) {
            $ruta = Estructura::$ruta . "/Textos/";
            $config = Config::obtener();
            $modulos = $config::$modulos;

            if (!in_array($modulo, $modulos)) {
                Excepcion::procesar("No existe el módulo pasado", self::$_ce . 2);
            }
            if ($modulo !== "app") {
                $ruta = Estructura::$rutaAplicacion . "/Modulos/" . ucfirst($modulo) . "/";
            }

            return $ruta = $ruta . implode("/", $partes);

        }

    }

}