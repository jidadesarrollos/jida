<?php
/**
 * Trait para manejo de funciones generales
 *
 * @internal Provee un conjunto de funcionalidad que son reutilizables tanto en Framework 
 * como en la Aplicacion
 *
 */
     
namespace Jida\Core;
use \Jida\Helpers\Debug as Debug;
trait ObjetoManager{
    
    
    /**
     * Establece los atributos de una clase.
     *
     * @internal Valida si los valores pasados en el arreglo corresponden 
     * a los atributos de la clase en uso y asigna el valor correspondiente
     *
     * @access protected
     * @param array @arr Arreglo con valores
     * @param instance @clase Instancia de la clase
     */
    protected function establecerAtributos($arr, $clase='') {
        
        if(empty($clase))
            $clase=$this->_clase;
        
        
        if(is_object($clase))
            $atributos = get_object_vars($clase);
        else
            $atributos = get_class_vars($clase);
        
                
        foreach($atributos as $k => $valor){
            if (isset($arr[$k]))
                $this->$k = $arr[$k];
        }
        // Debug::imprimir('$this',$this,true);
    }
	
}