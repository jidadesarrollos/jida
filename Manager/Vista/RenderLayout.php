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
     * @see Layout\Procesador
     * @since 1.4
     * @param $lenguajes
     * @param string $modulo Si es pasado, la funcion buscara imprimir solo los valores del key correspondiente.
     * @return string $libsHTML renderización HTML de los tags de inclusión de las librerias.
     * @throws Excepcion
     */
    function imprimirLibrerias($lenguajes, $modulo = "") {

        $configuracion = self::$_configuracion;

        $lenguajes = (is_string($lenguajes)) ? (array)$lenguajes : $lenguajes;
        $retorno = "";

        //Debug::imprimir([$configuracion]);
        foreach ($lenguajes as $lenguaje) {
            if (!in_array($lenguaje, ['head', 'jsAjax']) and !isset($configuracion->{$lenguaje}))
                return null;
            
            switch ($lenguaje) {
                case 'head':
                    $retorno = $this->_imprimirHead($configuracion, $modulo);
                    break;
                case 'js':
                    $retorno = $this->_imprimirJS($configuracion->{$lenguaje}, $modulo);
                    break;
                case 'jsAjax':
                    $retorno = $this->_imprimirJS([], $modulo, true);
                    break;
                case 'css':
                    $retorno = $this->_imprimirCSS($configuracion->{$lenguaje}, $modulo);
                    break;
            }
        }

        return $retorno;

    }
}