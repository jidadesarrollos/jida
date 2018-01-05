<?php

namespace JIDA\Inicio\Rutas;

use Jida\Helpers as Helpers;

class Procesador {

    protected $_padre;
    protected $_moduloValidado;
    protected $_default = 'Index';

    protected function _esModulo() {

    }

    function _esControlador($namespace, $controlador) {

        $band = true;

        if (!empty($controlador)) {

            $nombre = $this->_validarNombre($controlador, 'upper');
            $clase = $namespace . $nombre;
            $claseSufijo = $clase . 'Controller';
            Helpers\Debug::imprimir("Nombre Ajustado", $nombre);

        } else {
            $band = false;
        }

        if ($band and (class_exists($clase)) or class_exists($claseSufijo)) {
            $controlador = (class_exists($clase)) ? $clase : $claseSufijo;

        } else {

        }

    }

    /**
     * Ajusta el nombre de los Controladores y Metodos
     *
     * Realiza una modificación del string para crear nombres
     * de clases controladoras y metodos validas
     *
     * @method validarNombre
     * @param string $str           Cadena a formatear
     * @param int    $tipoCamelCase lower, upper
     *
     * @return string $nombre Cadena Formateada resultante
     */
    protected function _validarNombre($str, $tipoCamelCase) {

        if (!empty($str)) {
            if ($tipoCamelCase == 'upper') {
                $nombre = str_replace(" ", "", Helpers\Cadenas::upperCamelCase(str_replace("-", " ", $str)));
            } else {
                $nombre = str_replace(" ", "", Helpers\Cadenas::lowerCamelCase(str_replace("-", " ", $str)));
            }

            return $nombre;
        }

    }


}