<?php

/**
 * Clase Modelo para s_campos_f
 *
 *
 * @package Aplicacion
 * @category Modelo
 */

namespace Jida\Modelos;

use Jida\Core as Core;

class CampoFormulario {

    use Core\ObjetoManager;

    var $label;
    var $name;
    var $eventos;
    var $opciones;
    var $orden;
    var $placeholder;
    var $class;
    var $data;
    var $visibilidad;
    var $id;
    var $type;
    var $size;
    var $title;

    private $types = [
        1 => 'hidden',
        2 => 'text',
        3 => 'textarea',
        4 => 'password',
        5 => 'checkbox',
        6 => 'radio',
        7 => 'seleccion',
        8 => 'identificacion',
        9 => 'telefono'
    ];

    function __construct($campo = "") {

        if (!empty($campo) and is_object($campo)) {
            $this->establecerAtributos($campo, $this);
        }
        $a = new \stdClass();
        $a->test = 'algo';

            return $a;
    }

    /**
     * Retorna un tipo de campo
     *
     * @param int id identificador númerico del campo
     * @return string| boolean Nombre del tipo de campo.
     */
    function tipo($id) {

        if (array_key_exists($id, $this->types)) {
            return $this->types[$id];
        }

        return false;

    }

    /**
     * Retorna las propiedades publicas del campo en un arreglo
     * @return array
     */
    function obtenerPropiedades() {

        $reflector = new \ReflectionClass($this);
        $propiedades = $reflector->getPRoperties(\ReflectionProperty::IS_PUBLIC);
        $salida = [];
        foreach ($propiedades as $propiedad) {
            if(!$propiedad->isStatic()) {
                $salida[$propiedad->getName()] = $propiedad->getValue($this);
            }
        }

        return $salida;

    }

}//fin clase