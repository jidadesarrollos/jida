<?php

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;

Trait Render {



    public function __call($metodo, $argumentos = []) {

        if (!method_exists($this, $metodo)) {
            $msj = "El metodo pedido no existe: " . $metodo;
            throw new \Exception($msj, self::$_ce . 4);
        }
    }

    public function __get($propiedad) {

        if (!property_exists($this, $propiedad)) {

            $configuracion = Config::obtener();
            if (is_object($this->_data) and property_exists($this->_data, $propiedad)) {
                return $this->_data->{$propiedad};
            }
            if (property_exists($configuracion, $propiedad)) {
                return $configuracion::$propiedad;
            }

            //$msj = "La propiedad pedida no existe: " . $propiedad;
            return null;
            // throw new \Exception($msj, self::$_ce . 4);

        }

    }
}