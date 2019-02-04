<?php
/**
 * @see \Jida\Manager\Vista\Layout
 */

namespace Jida\Manager\Vista\Layout;

use Jida\Manager\Estructura;
use Jida\Manager\Vista\Tema;
use Jida\Medios\Debug;

Trait Gestor {

    private static function _procesarUbicacion($archivo, $tipo) {

        if (strpos($archivo, 'modulo') !== false) {
            $archivo = str_replace('modulo', Estructura::$urlModulo . "/htdocs/$tipo/", $archivo);

        }
        elseif (strpos($archivo, 'tema')) {
            $archivo = str_replace('tema', Tema::$url, $archivo);
        }

        return $archivo;
    }

    public function incluirJS($librerias, $modulo = false) {

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $libreria) {
            array_push($this->_js, self::_procesarUbicacion($libreria, "js"));
        }

    }

    public function incluirJSAjax($librerias, $modulo = false) {

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $libreria) {
            array_push($this->_jsAjax, self::_procesarUbicacion($libreria, "js"));
        }


    }

    public function incluirCSS($librerias, $modulo = false) {

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $libreria) {
            array_push($this->_js, self::_procesarUbicacion($libreria, "css"));
        }

    }
}