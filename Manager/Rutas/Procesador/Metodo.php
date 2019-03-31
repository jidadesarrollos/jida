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

    private function _metodoDefault($controlador) {
        $metodo = 'index';

        if (!$this->_validarMetodo($controlador, 'index')) {
            $msj = 'El controlador ' . $controlador . ' debe poseer un metodo index';
            Excepcion::procesar($msj, self::$_ce . 0002);
        }
        return $metodo;
    }

    public function _metodo() {

        $metodo = $this->_padre->url->proximoParametro();
        $controlador = Estructura::$namespace . Estructura::$controlador;

        $default = true;

        if ($metodo) {

            $default = false;
            $metodo = $this->_validarNombre($metodo, 'lower');

            if (!$this->_validarMetodo($controlador, $metodo)) {

                $this->_padre->url->reingresarParametro($metodo);
                $default = true;

            }

        }

        if ($default) $metodo = $this->_metodoDefault($controlador);

        Estructura::$metodo = $metodo;

        return true;

    }
}