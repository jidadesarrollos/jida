<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 30/3/2019
 * Time: 16:32
 */

namespace Jida\Manager\Url;

use Jida\Medios;

class Definicion {

    static private $instancia;

    /**
     * Ajusta el nombre de los Controladores y Metodos
     *
     * Realiza una modificación del string para crear nombres
     * de clases controladoras y metodos validas
     *
     * @method validarNombre
     * @param string $str Cadena a formatear
     * @param int $tipoCamelCase lower, upper
     *
     * @return string $nombre Cadena Formateada resultante
     */
    protected function _validarNombre($str, $tipoCamelCase) {

        if (empty($str)) {
            return false;
        }
        if ($tipoCamelCase == 'upper') {
            $nombre = str_replace(" ", "", Medios\Cadenas::upperCamelCase(str_replace("-", " ", $str)));
        }
        else {
            $nombre = str_replace(" ", "", Medios\Cadenas::lowerCamelCase(str_replace("-", " ", $str)));
        }

        return $nombre;

    }

    private static function obtener() {

        if (!self::$instancia) {
            self::$instancia = new Definicion();
        }

        return self::$instancia;

    }

    static function objeto($nombre) {

        $objeto = self::obtener();
        return $objeto->_validarNombre($nombre, 'upper');
    }

    static function metodo($nombre) {

        $objeto = self::obtener();
        return $objeto->_validarNombre($nombre, 'lower');

    }

}