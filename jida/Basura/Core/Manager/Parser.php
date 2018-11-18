<?php
/**
 * Objeto Encargado del Manejo de URLS
 * @author julio Rodriguez
 *
 */

namespace Jida\Core\Manager;

use Jida\Helpers as Helpers;

class Parser {
    private $_config = false;

    private $_claseConfiguracion = 'Config';
    private $_modulos = [];
    private $_Parsers = [];

    public function __construct ($modulos) {

        $this->_modulos = $modulos;

        if (class_exists('\App\Config\Url')) {
            $this->_config = new \App\Config\Url();
        }

        $this->_parserModulos();
    }

    private function _parserModulos () {

        Helpers\Debug::imprimir($this->_modulos);

        foreach ($this->_modulos as $key => $modulo) {

            if (strtolower($modulo) != "jadmin") {

                $this->_parserModulo($modulo);

            }

        }
    }//fin function

    private function _parserModulo ($modulo) {

        $clase = 'App\Modulos\\' . Helpers\Cadenas::upperCamelCase($modulo) . '\\' . $this->_claseConfiguracion;

        if (class_exists($clase)) {
            Helpers\Debug::imprimir($clase, true);
        }
        else {
            Helpers\Debug::imprimir("no existe " . $clase);
        }

    }
}
