<?php

namespace Jida\Manager\Rutas\Procesador;

use Jida\Helpers\Debug;

Trait Controlador {

    private function _default () {

        $padre = $this->_padre;
        $modulo = $padre::$modulo;

        if ($modulo) {
            $ctrlDefault = $modulo;
        }
        else {
            $ctrlDefault = ($padre->jadmin) ? 'Jadmin' : 'Index';
        }

        $default = ($modulo) ? $this->_validarNombre($modulo, 'upper') : $ctrlDefault;

        return $default;

    }

    public function _controlador () {

        $padre = $this->_padre;

        $controlador = $this->_padre->proximoParametro();

        if (empty($controlador))
            $controlador = $this->_default();

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

        $padre::$controlador = $controlador;

    }

}