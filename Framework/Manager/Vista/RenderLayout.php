<?php
/**
 * TODO: Poder incluir archivos js y css desde el htdocs principal, desde el tema implementado o desde el modulo llamado
 *
 */

namespace Jida\Manager\Vista;

use Jida\Manager\Estructura;

Trait RenderLayout {

    public function incluirJS($librerias, $modulo = false) {

        if ($modulo === true) {
            $modulo = Estructura::$rutaModulo;
        }
        else {

            $modulo = $this->_urlTema;
        }

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $valor) {
            array_push($this->_js, "$modulo/$valor");
        }

    }

    public function incluirCSS($librerias, $modulo = false) {

        if ($modulo == true) {
            $modulo = Estructura::$rutaModulo;
        }
        else {
            $modulo = $this->_urlTema;
        }

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($librerias as $indice => $valor) {
            array_push($this->_css, "$modulo/$valor");
        }

    }

}