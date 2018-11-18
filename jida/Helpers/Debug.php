<?php
/**
 * Clase con funcionalidades generales que permiten al programador hacer tests
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package Framework
 * @category Helpers
 * @version 0.1
 */

namespace Jida\Helpers;

class Debug {

    /**
     * @internal Muestra el contenido de un arreglo envuelto en tag <pre>
     * @method mostrarArray
     * @access public
     * @since 0.1
     *
     */
    static function mostrarArray ($ar, $exit = true) {

        echo "\n<pre style=\"background:black;color:#dcdcdc\">\n";
        print_r($ar);
        echo "</pre>";
        if ($exit == true) {
            exit;
        }
    }

    /**
     * Muestra el contenido de las variables pasadas como parametros en bloques de impresion
     *
     * @param array $impresiones Arreglo de impresiones a realizar.
     * @param array $config Arreglo de configuraciones, tiene opciones para selector, corte y color de la impresion del arreglo
     */
    static function imprimir ($impresiones = [], $config = []) {

        if (!$impresiones) {
            return;
        }
        if (is_bool($config))
            $config = ['corte' => $config];
        $estandar = [
            'selector' => 'hr',
            'corte'    => false,
            'bg'       => 'white',
            'color'    => 'black'

        ];
        $traza = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];

        echo "
        <div style='font-size: 11px; color:red'>
            <div>" . $traza["file"] . ", Linea " . $traza['line'] . "</div>
        </div>
        ";

        $config = array_merge($estandar, $config);

        if (is_string($impresiones) or is_numeric($impresiones)) {
            $impresiones = explode("*", $impresiones);
        }

        foreach ($impresiones as $key => $impresion) {

            if (is_array($impresion) or is_object($impresion)) {
                echo "<pre style=\"background:" . $config['bg'] . "\">";
                print_r($impresion);
                echo "</pre>";

            }
            else if (is_string($impresion) or is_int($impresion)) {
                echo $impresion;
            }
            else if (is_bool($impresion)) {
                $booleano = ($impresion) ? "true" : "false";
                echo "bool: $booleano";
            }
            else {
                "ESTE NO ENCAJA $impresion";
            }
            if (array_key_exists('html', $config)) {
                echo $config['html'];
            }
            else {
                echo "<" . $config['selector'] . "/>";
            }

        }
        if ($config['corte']) {
            exit;
        }
    }

    /**
     * @internal Muestra el contenido de una variable String
     *
     * @access public
     * @since 0.1
     * @deprecated 0.6
     */
    static function string ($content, $exit = false, $tag = "hr") {

        if (!is_array($content)) {
            echo $content . "<$tag/>";
            if ($exit == true) {
                exit;
            }
        }
        else if (is_array($content) or is_object($content)) {
            self::mostrarArray($content, $exit);
        }
    }

    /**
     * @internal Muestra el contenido de una variable String
     * @method cadena
     * @access public
     * @since 0.6
     *
     */
    static function cadena ($content, $exit = false, $tag = "hr") {

        if (!is_array($content)) {
            echo $content . "<$tag/>";
            if ($exit == true) {
                exit;
            }
        }
        else if (is_array($content) or is_object($content)) {
            self::mostrarArray($content, $exit);
        }
    }

}

