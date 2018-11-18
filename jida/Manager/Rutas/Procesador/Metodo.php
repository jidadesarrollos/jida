<?php

namespace Jida\Manager\Rutas\Procesador;

use Jida\Manager\Estructura;

Trait Metodo {

    private function _validarMetodo ($controlador, $metodo) {

        $reflection = new \ReflectionClass($controlador);
        $padre = $this->_padre;

        if (method_exists($controlador, $metodo) and $reflection->getMethod($metodo)->isPublic()) {
            $padre::$metodo = $metodo;

            return true;
        }

        return false;

    }

    public function _metodo () {

        $posMetodo = $this->_padre->proximoParametro();

        $padre = $this->_padre;

        $controlador = Estructura::$namespace . $padre::$controlador;
        $default = true;

        if ($posMetodo) {

            $default = false;
            $metodo = $this->_validarNombre($posMetodo, 'lower');

            if (!$this->_validarMetodo($controlador, $metodo)) {

                $this->_padre->reingresarParametro($posMetodo);
                $default = true;

            }

        }

        if ($default) {

            $metodo = 'index';
            if (!$this->_validarMetodo($controlador, 'index')) {
                throw new \Exception('El controlador ' . $controlador . ' debe poseer un metodo index',
                                     $this->_ce . '0002');
            }

        }

        $padre::$metodo = $metodo;

        return true;

    }
}