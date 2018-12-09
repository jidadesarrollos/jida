<?php

namespace Jida\Manager\Rutas\Procesador;

use Jida\Manager\Estructura;
use Jida\Medios\Debug;

Trait Controlador {

    private function _default() {

        $modulo = Estructura::$modulo;

        if ($modulo) {
            $ctrlDefault = $modulo;
        }
        else {
            $ctrlDefault = (Estructura::$jadmin) ? 'Jadmin' : 'Index';
        }

        $default = ($modulo) ? $this->_validarNombre($modulo, 'upper') : $ctrlDefault;

        return $default;

    }

    public function _controlador() {

        $controlador = $this->_padre->proximoParametro();

        if (empty($controlador)) {
            $controlador = $this->_default();
        }
        else {

            $clase = $this->_validarNombre($controlador, 'upper');
            $nombreControl = $this->_namespace . $clase;

            if (class_exists($nombreControl)) {
                $controlador = $nombreControl;
            }
            else {
                $this->_padre->reingresarParametro($controlador);
                $controlador = $this->_default();

            }

        }

        Estructura::$controlador = $controlador;

    }

}