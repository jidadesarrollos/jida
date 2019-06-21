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

    /**
     * Traduce una cadena recibida
     *
     * @param string $cadena Cadena string a buscar
     * @param string $ubicacion Ubicacion de la cadena dentro de la matriz
     */
    public function cadena($cadena, $ubicacion = "") {

        if (empty($ubicacion))
            $ubicacion = $this->ubicacion;

        #Debug::mostrarArray($this->textos);
        if (!empty($ubicacion)) {
            if (array_key_exists($ubicacion, $this->textos) and array_key_exists($cadena, $this->textos[$ubicacion]))
                return $this->textos[$ubicacion][$cadena];
        }
        else {

            if (array_key_exists($cadena, $this->textos))
                return $this->textos[$cadena];
        }

        return 'Indefinido';

    }

}