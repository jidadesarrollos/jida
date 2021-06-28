<?php
/**
 * @see \Jida\Manager\Vista\Layout
 */

namespace Jida\Manager\Vista\Layout;

use Jida\Manager\Estructura;
use Jida\Manager\Vista\Tema;
use Jida\Medios\Debug;

trait Gestor {

    private static function _procesarUbicacion($archivo, $tipo) {

        $url = is_array($archivo) ? $archivo['src'] : $archivo;

        if (strpos($url, 'modulo') !== false) {
            $url = str_replace('modulo', Estructura::$urlModulo . "/htdocs/$tipo/", $archivo);
        }
        elseif (strpos($url, '{tema}') !== false) {
            $url = str_replace('{tema}', Tema::$url, $archivo);
        }

        if (is_array($archivo)) {
            $archivo['src'] = $url;
        }

        return $archivo;
    }

    public function incluirJS($librerias, $modulo = false) {

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $libreria) {
            $this->_js[$indice] = self::_procesarUbicacion($libreria, "js");
        }

    }

    public function incluirJSAjax($librerias, $modulo = false) {

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $libreria) {
            $this->_jsAjax[$indice] = self::_procesarUbicacion($libreria, "js");
        }

    }

    public function incluirCSS($librerias, $modulo = false) {

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $libreria) {
            $this->_css[$indice] = self::_procesarUbicacion($libreria, "css");
        }

    }

}