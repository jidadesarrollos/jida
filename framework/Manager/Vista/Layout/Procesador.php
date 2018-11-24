<?php
/**
 * Procesador de recursos para el layout
 *
 * @author Julio Rodriguez
 */

namespace Jida\Manager\Vista\Layout;

use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Manager\Vista\Meta;
use Jida\Render\Selector;

Trait Procesador {

    private $_head = [
        "link",
        "meta"
    ];

    private function _imprimirJS ($librerias) {

        $html = "";
        $path = "/" . Estructura::$urlBase;

        foreach ($librerias as $clave => $libreria) {

            $urlLibreria = $libreria;
            if (strpos($libreria, "http") === false) {
                $urlLibreria = implode("/", array_filter(explode("/", $path . $libreria)));
                $urlLibreria = "//$urlLibreria";
            }

            $html .= Selector::crear('script',
                ['src' => $urlLibreria],
                null,
                2);

        }

        return $html;

    }

    private function _css ($librerias) {

        $html = "";
        $path = "/" . Estructura::$urlBase;
        $path = "/" . Estructura::$urlBase;

        foreach ($librerias as $clave => $libreria) {

            $urlLibreria = $libreria;

            if (strpos($libreria, "http") === false) {
                $urlLibreria = implode("/", array_filter(explode("/", $path . $libreria)));
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

    private function _imprimirCSS ($librerias) {

        return $this->_css($librerias);

    }

    /**
     * Imprime las etiquetas links registradas en la configuración del tema
     *
     */
    private function _link ($etiquetas) {

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

    private function _imprimirHead ($configuracion) {

        $html = "";
        if (property_exists($configuracion, "link")) {
            $html .= $this->_link($configuracion->link);
        }

        if (property_exists($configuracion, "css")) {
            $html .= $this->_css($configuracion->css);
        }

        return $html;

    }

    /**
     * @deprecated
     * @see imprimirMeta
     */
    public function printHeadTags () {

        $msj = "El metodo printHeadTags se encuentra en desuso, por favor reemplazar por imprimir meta";
        Excepcion::procesar($msj, self::$_ce . 3);

    }

    public function imprimirMeta () {

        return Meta::imprimir($this->_data);
    }

}