<?php
/**
 * Procesador de recursos para el layout
 *
 * @author Julio Rodriguez
 */

namespace Jida\Manager\Vista\Layout;

use Jida\Manager\Excepcion;
use Jida\Manager\Vista\Meta;
use Jida\Medios\Debug;
use Jida\Render\Selector;

Trait Procesador {

    private $_head = [
        "link",
        "meta"
    ];

    /**
     * Imprime las etiquetas link registradas en la configuración del tema
     *
     */
    private function _imprimirCSS($librerias, $modulo) {

        return $this->_css($librerias, $modulo);

    }

    private function _css($librerias, $modulo) {

        $html = "";
        $arregloCSS = $this->_css;

        if (!property_exists($librerias, $modulo)) {
            return false;
        }

        $librerias = $librerias->{$modulo};

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }
        else {
            foreach ($this->_css as $indice => $valor) {
                $librerias->{$indice} = $valor;
            }
        }

        foreach ($librerias as $clave => $libreria) {

            if (is_object($libreria)) {
                continue;
            }

            $urlLibreria = str_replace('{tema}', self::$_urlTema, $libreria);

            if (strpos($urlLibreria, "http") === false) {
                $urlLibreria = implode("/", array_filter(explode("/", $urlLibreria)));
                $urlLibreria = "//$urlLibreria";
            }

            $html .= Selector::crear('link',
                [
                    'href' => $urlLibreria,
                    'rel'  => 'stylesheet',
                    'type' => 'text/css'
                ],
                null,
                2);

        }

        return $html;

    }

    /**
     * Imprime las etiquetas script registradas en la configuración del tema
     *
     */
    private function _imprimirJS($librerias, $modulo) {

        return $this->_js($librerias, $modulo);

    }

    private function _js($librerias, $modulo) {

        $html = "";
        $arregloJS = $this->_js;

        if (!property_exists($librerias, $modulo)) {
            return false;
        }

        $librerias = $librerias->{$modulo};

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }
        else {
            foreach ($arregloJS as $indice => $valor) {
                $librerias->{$indice} = $valor;
            }
        }

        foreach ($librerias as $clave => $libreria) {

            if (is_object($libreria)) {
                continue;
            }

            $urlLibreria = str_replace('{tema}', self::$_urlTema, $libreria);

            if (strpos($urlLibreria, "http") === false) {
                $urlLibreria = implode("/", array_filter(explode("/", $urlLibreria)));
                $urlLibreria = "//$urlLibreria";
            }

            $html .= Selector::crear('script',
                ['src' => $urlLibreria],
                null,
                2);

        }

        return $html;

    }

    /**
     * Imprime las etiquetas links registradas en la configuración del tema
     *
     */
    private function _link($etiquetas) {

        $html = "";
        foreach ($etiquetas as $etiqueta => $contenido) {

            $configuracion = [];
            if (is_object($contenido)) {

                if (!property_exists($contenido, 'href')) {
                    $msj = "El tema " . $this->_tema . " tiene mal configurado el tag link $etiqueta";
                    Excepcion::procesar($msj, self::$_ce . 6);
                }

                $configuracion = (array)$contenido;
            }
            else {
                $configuracion = ['href' => $contenido, 'rel' => $etiqueta];
            }

            $urlTema = self::$_urlTema;
            $configuracion['href'] = str_replace('{tema}', $urlTema, $configuracion['href']);

            $html .= Selector::crear('link', $configuracion, null, 2);

        }

        return $html;

    }

    private function _imprimirHead($configuracion, $modulo) {

        $html = "";
        if (property_exists($configuracion, "link")) {
            $html .= $this->_link($configuracion->link, $modulo);
        }

        if (property_exists($configuracion, "css")) {
            $html .= $this->_css($configuracion->css, $modulo);
        }

        return $html;

    }

    /**
     * @deprecated
     * @see imprimirMeta
     */
    public function printHeadTags() {

        $msj = "El metodo printHeadTags se encuentra en desuso, por favor reemplazar por imprimir meta";
        Excepcion::procesar($msj, self::$_ce . 3);

    }

    public function imprimirMeta() {

        return Meta::imprimir($this->_data);

    }

}