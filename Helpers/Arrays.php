<?php

/**
 * Clase Helper de Arreglos
 *
 * @package Framework
 * @subpackage Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 * @category [Helper]
 * @since 0.1
 */

namespace Jida\Helpers;

use stdClass;

class Arrays {

    /**
     * Filtra los registros de una matriz
     *
     * @internal Filtra los registros de una matriz dada a partir de los valores
     * de busqueda
     * @method filtro
     * @param array $matriz
     * @param mixed $filtro Arreglo o matriz de valores para realizar el filtro
     * @return array $array Nuevo Arreglo armado
     * @access public
     * @since 0.1
     *
     * @example
     *
     */
    static function filtro($matriz, $filtro) {
        $numeroFiltros = count($filtro);
        $array = [];
        foreach ($matriz as $key => $valores) {
            foreach ($filtro as $columna => $valor) {
                if (array_key_exists($columna, $valores) and $valores[$columna] == $valor)
                    $array[$key] = $valores;
            }
        }

        return $array;
    }

    /**
     * @internal Combina dos arreglos utilizando uno para la
     * estructura y otro para los valores
     * @param array $ar1 Arreglo con estructura a usar
     * @param array $ar2 Arreglo con valores a usar para llenar el array 1
     * @return array $ar1 arreglo inicial con valores insertados
     * @access public
     * @since 0.1
     *
     */
    static function combinar($ar1, $ar2) {
        foreach ($ar1 as $key => $value) {
            if (array_key_exists($key, $ar2)) {
                $ar1[$key] = $ar2[$key];
            }
        }

        return $ar1;
    }

    /**
     * Recorre un array recursivo buscando los valores solicitados
     *
     * @internal Recorre un arreglo de forma recursiva buscando todos los valores que coincidan
     * con una clave dada y retorna un nuevo arreglo ordenado con las posiciones relacionadas
     * a la clave
     * @param array $arr Arreglo a recorrer
     * @param string $busqueda Nombre o valor a buscar
     * @param string $filtro Campo de filtro en estructura del arreglo
     * @access public
     * @since 0.1
     *
     */
    static function obtenerHijosArray($arr, $busqueda, $filtro) {
        $nuevoArreglo = [];

        foreach ($arr as $key => $value) {
            if (array_key_exists($filtro, $value) and $value[$filtro] == $busqueda)
                $nuevoArreglo[] = $value;
        }

        if (count($nuevoArreglo) > 0)
            return $nuevoArreglo;
        else
            return [];
    }

    /**
     * @internal Devuelve un arreglo con los valores extraidos de una matriz
     * @method obtenerKey
     * @param string $key Clave a buscar en los arreglos u objetos de cada posición del arreglo a buscar
     * @param array $array Arreglo multidimensional a filtrar
     * @access public
     * @since 0.1
     *
     */
    static function obtenerKey($clave, $array, $mantenerKey = FALSE) {
        $arrayResult = array();
        if (!is_array($array) and !is_object($array))
            throw new Exception(" El objeto pasado $array no es un arreglo", 1);

        foreach ($array as $key => $fila) {

            if (is_object($fila)) {
                if (is_array($clave)) {
                    $datos = [];
                    foreach ($clave as $key => $value) {
                        if (property_exists($value, $fila))
                            $datos[$value] = $fila[$value];
                    }
                    if (!empty($datos)) $arrayResult[] = $datos;
                } else
                    if (property_exists($fila, $clave) and !empty($fila->$clave)) {
                        $arrayResult[] = $fila->$clave;
                    }
            } else
                if (!is_array($fila)) {

                    if (is_array($clave)) {

                        foreach ($clave as $id => $valor) {
                            if ($valor == $key)
                                $datos[$id] = $fila;
                        }
                    }

                } else {
                    if (is_array($clave)) {

                        $datos = [];
                        foreach ($clave as $key => $value) {
                            if (array_key_exists($value, $fila))
                                $datos[$value] = $fila[$value];
                        }

                        if (!empty($datos)) $arrayResult[] = $datos;
                    } else
                        if (array_key_exists($clave, $fila)) {
                            $arrayResult[] = $fila[$clave];
                        }
                }

        }//fin foreach

        if (!empty($datos)) $arrayResult[] = $datos;

        if (count($array) > 0)
            return $arrayResult;
        else
            return [];

    }

    /**
     * @internal Agrega una columna a todos los valores de una matriz
     * @method addColumn
     * @param array [,$arr] Arreglo a modificar
     * @param mixed $valores Arreglo o string de valores a insertar
     * @param boolean $usoKeyValores Si es TRUE se usaran las claves del vector como claves en las nuevas columnas de la matriz
     * @access public
     * @since 0.1
     *
     */
    static function addColumna($matriz, $valores, $usoKeyValores = FALSE) {

        if (is_array($valores)) {
            foreach ($matriz as $key => &$vector) {

                if (is_string($vector))
                    $vector = array($vector);

                foreach ($valores as $clave => $valor) {
                    if ($usoKeyValores == TRUE)
                        $vector[$clave] = $valor;
                    else
                        $vector[] = $valor;
                }
            }
        } else {
            foreach ($matriz as $key => &$vector) {
                if (is_string($vector))
                    $vector = array($vector, $valores);
                else
                    $vector[] = $valores;
            }
        }

        return $matriz;
    }

    /**
     * @internal Convierte un arreglo en un objeto tipo stdClass
     * @method convertirAObjeto
     * @param array $array Arreglo a convertir
     * @return object $objeto Arreglo convertido en objeto
     * @access public
     * @since 0.1
     *
     * @example ca
     *
     */
    static function convertirAObjeto($array) {
        $objeto = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) $objeto->$key = self::convertirAObjeto($value);
            else $objeto->$key = $value;
        }

        return $objeto;
    }

    static function ordenarMatriz($matriz, $campo, $inverso = FALSE) {

        $pivote = [];
        foreach ($matriz as $key => $fila) {

            $pivote[$fila[$campo]] = $fila;

        }

        Debug::imprimir($pivote, true);

    }

}
