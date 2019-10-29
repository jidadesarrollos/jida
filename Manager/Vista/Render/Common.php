<?php

namespace Jida\Manager\Vista\Render;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;

Trait Common {

    public function __call($metodo, $argumentos = []) {

        if (!method_exists($this, $metodo)) {
            $msj = "El metodo pedido no existe: " . $metodo;
            throw new \Exception($msj, self::$_ce . 4);
        }
    }

    public function __get($propiedad) {

        if (property_exists($this, $propiedad)) return null;

        $configuracion = Config::obtener();
        if (is_object($this->_data) and property_exists($this->_data, $propiedad)) return $this->_data->{$propiedad};

        if (property_exists($configuracion, $propiedad)) return $configuracion::$propiedad;

        return null;

    }

    /*
     * Concatena la urlBase con la url pasada
     *
     * @param string $url
     * @since 0.7.2
     */
    public function navegar($url = "") {

        if (Estructura::$idioma !== Config::IDIOMA_DEFAULT) {
            return Estructura::$urlBase . "/" . Estructura::$idioma . $url;
        }

        return Estructura::$urlBase . $url;

    }

    /*
     * Traduce una cadena recibida
     *
     * @param string $cadena Cadena string a buscar
     * @param string $ubicacion Ubicacion de la cadena dentro de la matriz
     */
    public function cadena($cadena, $ubicacion = "") {

        if (!$this->traductor) return false;

        if (empty($ubicacion)) $ubicacion = $this->ubicacion;

        if (!empty($ubicacion)) {
            if (array_key_exists($ubicacion, $this->textos) and array_key_exists($cadena, $this->textos[$ubicacion])) {
                return $this->textos[$ubicacion][$cadena];
            }

        }
        else if (array_key_exists($cadena, $this->textos)) return $this->textos[$cadena];

        return 'Indefinido';

    }

    /*
     * Traduce una cadena recibida
     *
     * @param string $cadena texto
     * @since 0.7.2
     */
    public function texto($cadena) {

        return $this->textos->texto($cadena);

    }

    /*
     * Funcion utilizada para cambiar de idioma
     *
     * @param string $idioma
     * @since 0.7.2
     */
    public function cambiarIdioma($idioma) {

        $config = Config::obtener();

        if (array_key_exists($idioma, $config->idiomas) and $idioma !== $config::IDIOMA_DEFAULT) {
            return Estructura::$urlBase . "/" . $idioma;
        }

        return Estructura::$urlBase;

    }

}