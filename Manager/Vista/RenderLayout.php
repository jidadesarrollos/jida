<?php
/**
 * TODO: Poder incluir archivos js y css desde el htdocs principal, desde el tema implementado o desde el modulo llamado
 *
 */

namespace Jida\Manager\Vista;

Trait RenderLayout {

    public function incluirJS ($archivos, $modulo = "") {

        if (!empty($modulo) and $modulo === 'tema') {
            $modulo = $this->_urlTema;
        }

        if (is_string($archivos)) {
            $archivos = explode("", $archivos);
        }

        foreach ($archivos as $indice => $archivo) {
            array_push($this->_js, "$modulo/$archivos");
        }

    }

    public function incluirCSS ($archivos, $modulo = "") {

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