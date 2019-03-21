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

        $controlador = $this->_padre->url->proximoParametro();

        if (empty($controlador)) {
            $controlador = $this->_default();
        }
        else {

            $clase = $this->_validarNombre($controlador, 'upper');
            $nombreControl = Estructura::$namespace . $clase;

            if (class_exists($nombreControl)) {
                $controlador = $clase;
            }
            else {
                $this->_padre->url->reingresarParametro($controlador);
                $controlador = $this->_default();

            }

        }

        Estructura::$controlador = $controlador;

    }

}