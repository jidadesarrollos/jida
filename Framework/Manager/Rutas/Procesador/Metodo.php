<?php

namespace Jida\Manager\Rutas\Procesador;

use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Medios\Debug;

Trait Metodo {

    private function _validarMetodo($controlador, $metodo) {

        try {

            $reflection = new \ReflectionClass($controlador);

            if (method_exists($controlador, $metodo) and $reflection->getMethod($metodo)->isPublic()) {

                return true;
            }

            return false;
        }
        catch (\Exception $e) {
            Excepcion::capturar($e);
        }

    }

    public function _metodo() {

        $posMetodo = $this->_padre->proximoParametro();
        $metodo = 'index';
        $controlador = Estructura::$namespace . Estructura::$controlador;
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

            if (!$this->_validarMetodo($controlador, 'index')) {
                $msj = 'El controlador ' . $controlador . ' debe poseer un metodo index';
                Excepcion::procesar($msj, self::$_ce . 0002);
            }


        }

        Estructura::$metodo = $metodo;

        return true;

    }
}