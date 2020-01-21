<?php
/**
 * TODO: Poder incluir archivos js y css desde el htdocs principal, desde el tema implementado o desde el modulo llamado
 *
 */

namespace Jida\Manager\Vista\Render;

use Jida\Manager\Vista\Tema;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

Trait Layout {

    function incluir($plantilla) {

        $directorio = Tema::$directorio;

        if (!Directorios::validar("$directorio/$plantilla.php")) {
            Debug::imprimir(["No existe el directorio $directorio/$plantilla.php"], true);
        }
        include_once "$directorio/$plantilla.php";
    }

    /**
     * Imprime las lirerias del lado cliente
     *
     * @param $lenguajes
     * @param string $modulo Si es pasado, la funcion buscara imprimir solo los valores del key correspondiente.
     * @return string $libsHTML renderización HTML de los tags de inclusión de las librerias.
     * @throws Excepcion
     * @see Layout\Procesador
     * @since 1.4
     */
    function imprimirLibrerias($lenguajes, $modulo = "") {

        $configuracion = self::$_configuracion;

        $lenguajes = (is_string($lenguajes)) ? (array)$lenguajes : $lenguajes;
        $retorno = "";

        foreach ($lenguajes as $lenguaje) {
            if (!in_array($lenguaje, ['head', 'jsAjax']) and !isset($configuracion->{$lenguaje}))
                return null;

            switch ($lenguaje) {
                case 'head':
                    $retorno = $this->_imprimirHead($configuracion, $modulo);
                    break;
                case 'js':
                    $retorno = $this->_js($configuracion->{$lenguaje}, $modulo);
                    break;
                case 'jsAjax':
                    $retorno = $this->_js([], $modulo, true);
                    break;
                case 'css':
                    $retorno = $this->_imprimirCSS($configuracion->{$lenguaje}, $modulo);
                    break;
            }
        }

        return $retorno;

    }

    /**
     * Agrega un css a la hoja de estilo global
     * @method addCss
     *
     * @param mixed $css Arreglo o string con ubicación del css
     * @param boolean $ambito TRUE si se desea usar la constante URL_CSS como ubicacion
     * @param string $ambito Usado para agregar css solo para prod o dev
     */
    function addCSS($css, $url = '') {

        $url = (empty($url)) ? $this->_urlCssBase : $url;

        if (is_string($css)) $css = [$css];

        foreach ($css as $i => $file) {
            $this->_css[] = $url . $file;
        }

    }
}