<?php
/**
 * TODO: Poder incluir archivos js y css desde el htdocs principal, desde el tema implementado o desde el modulo llamado
 *
 */

namespace Jida\Manager\Vista;

use Jida\Manager\Estructura;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

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

    function incluir($plantilla) {

        $directorio = Tema::$directorio;

        if (!Directorios::validar("$directorio/$plantilla.php")) {
            Debug::imprimir(["No existe el directorio $directorio/$plantilla.php"], true);
        }
        include_once "$directorio/$plantilla.php";
    }
}