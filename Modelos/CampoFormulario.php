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
class CampoFormulario{

    use Core\ObjetoManager;
    
    var $label;
    var $name;
    var $eventos;
    var $opciones;
    var $orden;
    var $placeholder;
    var $class;
    var $data_atributo;
    var $visibilidad;
    var $id;
    var $type;
    
    function __construct($campo=""){
        
        if(!empty($campo) and is_object($campo)){
            $this->establecerAtributos($campo,$this);        
        }
        
    }

}//fin clase