<?php
/**
 * Procesador de recursos para el layout
 *
 * @author Julio Rodriguez
 */

namespace Jida\Manager\Vista\Layout;

use Jida\Core\Selector;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Manager\Vista\Meta;
use Jida\Manager\Vista\OpenGraph;
use Jida\Manager\Vista\Tema;

Trait Procesador {

    private $_head = [
        "link",
        "meta"
    ];

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

    /**
     * Asigna el valor de las etiquetas Open Graph configurada para la página actual
     *
     * @method openGraph
     * @param $data arreglo que contiene el valor de las etiquetas personalizadas para open graph
     *
     */
    public function openGraph($data = []) {

        $this->_data->og = $data;
        return;

    }

    /**
     * Imprime las etiquetas link registradas en la configuración del tema
     *
     */
    private function _imprimirCSS($librerias, $modulo) {

        return $this->_css($librerias, $modulo);

    }

    private function _css($librerias, $modulo) {

        $html = "";

        if (!property_exists($librerias, $modulo)) {
            return false;
        }

        $librerias = $librerias->{$modulo};

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        foreach ($this->_css as $indice => $valor) {
            $librerias->{$indice} = $valor;
        }

        foreach ($librerias as $clave => $libreria) {

            if (is_object($libreria)) {
                continue;
            }

            $urlLibreria = str_replace('{tema}', Tema::$url, $libreria);

            if (strpos($urlLibreria, "http") === false) {

                $urlLibreria = implode("/", array_filter(explode("/", $urlLibreria)));

                if (strpos($urlLibreria, '{base}') === 0) {
                    $urlLibreria = str_replace('{base}', Estructura::$urlBase, $urlLibreria);
                }
                else {
                    $urlLibreria = '//' . $urlLibreria;
                }
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
    private function _imprimirJS($librerias, $modulo, $ajax = false) {

        return $this->_js($librerias, $modulo, $ajax);

    }

    private function _js($librerias, $modulo, $ajax) {

        $html = "";

        if (is_object($librerias) and !property_exists($librerias, $modulo)) {
            return false;
        }

        $librerias = is_object($librerias) ? $librerias->{$modulo} : new \StdClass();

        if (is_string($librerias)) {
            $librerias = (array)$librerias;
        }

        $libreriasJS = $ajax === true ? $this->_jsAjax : $this->_js;

        foreach ($libreriasJS as $indice => $valor) {
            $librerias->{$indice} = $valor;
        }

        foreach ($librerias as $clave => $libreria) {

            if (is_object($libreria)) {
                continue;
            }

            $html .= Selector::crear('script',
                ['src' => $this->_procesarRuta($libreria)],
                null,
                2);

        }

        return $html;

    }

    private function _procesarRuta($libreria) {

        if (strpos($libreria, "http") === false) {

            if (strpos($libreria, '{base}') !== false) {
                $libreria = str_replace('{base}', Estructura::$urlBase, $libreria);
            }
            else if (strpos($libreria, '{tema}') !== false) {
                $libreria = str_replace('{tema}', Tema::$url, $libreria);
            }
            $libreria = "//" . implode("/", array_filter(explode("/", $libreria)));

        }

        return $libreria;

    }

    private function _imprimirHead($configuracion, $modulo) {

        $html = "";

        if (property_exists($configuracion, "link")) {
            $html .= $this->_link($configuracion->link);
        }

        if (property_exists($configuracion, "css")) {
            $html .= $this->_css($configuracion->css, $modulo);
        }

        if (property_exists($this->_data, "og")) {
            $html .= OpenGraph::render($this->_data->og);
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

            $urlTema = "//" . self::$_urlTema;

            $configuracion['href'] = str_replace('{tema}', $urlTema, $configuracion['href']);
            $configuracion['href'] = str_replace('{base}', Estructura::$urlBase, $configuracion['href']);

            $html .= Selector::crear('link', $configuracion, null, 2);

        }

        return $html;

    }

}