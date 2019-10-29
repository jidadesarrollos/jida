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
use Jida\Medios\Debug;

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

    /***
     * Valida la url a integrar en los elementos.
     *
     * verifica la implementacion de las palabras claves tema o base. Tambien si la url pasada es una
     * url externa.
     *
     * @param $libreria
     * @return string
     */
    private function _getUrl($libreria) {

        if (strpos($libreria, "http") !== false) return $libreria;

        $url = str_replace('{tema}', Tema::$url, $libreria);
        $url = str_replace('{base}', Estructura::$urlBase, $url);
        $url = implode("/", array_filter(explode("/", $url)));

        return '//' . $url;

    }

    private function _css($librerias, $modulo) {

        $html = "\n";

        if (!property_exists($librerias, $modulo)) return false;

        $librerias = $librerias->{$modulo};

        if (is_string($librerias)) $librerias = (array)$librerias;

        foreach ($this->_css as $indice => $valor) $librerias->{$indice} = $valor;

        foreach ($librerias as $clave => $libreria) {

            if (is_object($libreria)) continue;

            $url = $this->_getUrl($libreria);
            $html .= "\t<link href=\"{$url}\" rel=\"stylesheet\" type=\"text/css\"/>\n";

        }

        return $html;

    }

    private function _js($librerias, $modulo, $ajax = false) {

        $html = "";

        if (is_object($librerias) and !property_exists($librerias, $modulo)) return false;

        $librerias = is_object($librerias) ? $librerias->{$modulo} : new \StdClass();

        if (is_string($librerias)) $librerias = (array)$librerias;

        $libreriasJS = $ajax === true ? $this->_jsAjax : $this->_js;

        foreach ($libreriasJS as $indice => $valor) $librerias->{$indice} = $valor;

        foreach ($librerias as $clave => $libreria) {

            if (is_string($libreria)) {
                $libreria = ['src' => $libreria];
            }
            else if (is_object($libreria)) {
                //module js support
                $libreria = json_decode(json_encode($libreria), true);
                if (!isset($libreria['src'])) {
                    Excepcion::procesar("No se ha definido src para $clave", self::$_ce . 10);
                }
            }

            $libreria['src'] = $this->_getUrl($libreria['src']);
            $attributes = "";

            foreach ($libreria as $attr => $value) $attributes .= " {$attr}=\"{$value}\"";
            $html .= "<script {$attributes}></script>\n\t\t";

        }

        return $html;

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
    private
    function _link($etiquetas) {

        $html = "";

        Debug::imprimir(["link", $etiquetas], true);

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