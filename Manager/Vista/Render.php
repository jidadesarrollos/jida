<?php

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;

Trait Render {

    /**
     * permite incluir otra plantilla o layout.
     *
     * Toma la url relativa
     */
    private function incluir () {

    }

    public function __call ($metodo, $argumentos = []) {

        if (!method_exists($this, $metodo)) {
            $msj = "El metodo pedido no existe: " . $metodo;
            throw new \Exception($msj, self::$_ce . 4);
        }
    }

    public function __get ($propiedad) {

        if (!property_exists($this, $propiedad)) {

            $configuracion = Config::obtener();
            if (!property_exists($configuracion, $propiedad)) {
                $msj = "La propiedad pedida no existe: " . $propiedad;
                throw new \Exception($msj, self::$_ce . 4);
            }

            return $configuracion::$propiedad;

        }

    }
}