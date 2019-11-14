<?php

namespace Jida\Core\Controlador;

use Jida\Manager\Excepcion;
use Jida\Medios\Debug;

Trait Getter {

    /**
     * @param $propiedad
     * @return mixed
     * @throws \Exception
     */
    function __get($propiedad) {

        //Debug::imprimir([$this]);
        if (property_exists($this->Layout, $propiedad)) {
            return $this->Layout->{$propiedad};
        }

        if (property_exists($this->_data, $propiedad)) {
            return $this->_data->{$propiedad};
        }

        $msj = "La propiedad $propiedad no existe";
        Excepcion::procesar($msj, self::$_ce . 2);

    }

    function __call($name, $arguments) {

        if (method_exists($this->Layout, $name)) {
            call_user_func_array([$this->Layout, $name], $arguments);
        }
        $msj = "No existe el metodo $name solicitado";
        Excepcion::procesar($msj, self::$_ce . 3);
    }
}
