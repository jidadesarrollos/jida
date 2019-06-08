<?php
/**
 * Trait para manejo de funciones generales
 *
 * @internal Provee un conjunto de funcionalidad que son reutilizables tanto en Framework
 * como en la Aplicacion
 *
 */

namespace Jida\Core;

use Jida\Medios\Debug;

trait ObjetoManager {

    protected function copiarAtributos($clase) {

        if (is_object($clase)) {
            $atributos = get_object_vars($clase);
        }

        else {
            $atributos = get_class_vars($clase);
        }

        foreach ($atributos as $key => $value) {
            $this->{$key} = $value;
        }
        Debug::imprimir([$this, $clase], true);
    }

    /**
     * Establece los atributos de una clase.
     *
     * @internal Valida si los valores pasados en el arreglo corresponden
     * a los atributos de la clase en uso y asigna el valor correspondiente
     *
     * @access protected
     * @param array @arr Arreglo con valores
     * @param mixed @clase Instancia de la clase
     *
     */
    protected function establecerAtributos($arr, $clase = '') {

        if (empty($clase)) {
            $clase = $this->_clase;
        }

        if (is_object($clase)) {
            $atributos = get_object_vars($clase);
        }
        else {
            $atributos = get_class_vars($clase);
        }

        foreach ($atributos as $k => $valor) {
            if (is_object($arr) and property_exists($arr, $k)) {
                $this->$k = $arr->$k;
            }
            else if (is_array($arr) and isset($arr[$k]))
                $this->$k = $arr[$k];
        }

    }

    /**
     * Retorna el namespace de un nombre de clase pasado
     *
     * @method obtNamespace
     * @param string $clase Nombre de la clase a evaluar
     * @param string $namespace Namespace de la clase que consulta, puede no ser la misma
     * @return mixed $partes Namespace armado
     *
     */
    private function obtNamespace($clase, $namespace = "") {

        if ($this->tieneNamespace($clase)) {

            $partes = explode('\\', $clase);
            array_pop($partes);

            return implode('\\', $partes);

        }

    }

    /**
     * Verifica si el nombre de clase pasado contiene un namespace
     * @method tieneNamespace
     *
     * @param {string} $clase Nombre de la clase
     */
    private function tieneNamespace($clase) {
        if (strrpos($clase, '\\') !== false)
            return true;
        return false;
    }

    /**
     * Retorna el nombre de la clase sin el namespace
     *
     * @param string $clase
     * @return obtClaseNombre
     */
    private function obtClaseNombre($clase) {

        if ($this->tieneNamespace($clase)) {

            $exp = explode('\\', $clase);

            return array_pop($exp);

        }

        return $clase;

    }

    private function addAtributos($array) {

        if (is_array($array) or is_object($array)) {

            foreach ($array as $key => $value) {
                $this->{$key} = $value;
            }

        }

    }

}