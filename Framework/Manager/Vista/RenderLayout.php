<?php
/**
 * TODO: Poder incluir archivos js y css desde el htdocs principal, desde el tema implementado o desde el modulo llamado
 *
 */

namespace Jida\Manager\Vista;

use Jida\Medios\Debug;

Trait RenderLayout {

    public function incluirJS($archivos, $modulo = "") {

        if (!empty($modulo) and $modulo === 'tema') {
            $modulo = $this->_urlTema;
        }

        if (is_string($archivos)) {
            $archivos = (array)$archivos;
        }

        foreach ($archivos as $indice => $archivo) {
            array_push($this->_js, "$modulo/$archivo");
        }

    }

    public function incluirCSS($archivos, $modulo = "") {

        $modulo = Estructura::$urlBase;
        if (!empty($modulo) and $modulo === 'tema') {
            $modulo = $this->_urlTema;
        }

        if (is_string($archivos)) {
            $archivos = explode("", $archivos);
        }

        foreach ($archivos as $indice => $archivo) {
            array_push($this->_css, "$modulo/$archivos");
        }
    }

}