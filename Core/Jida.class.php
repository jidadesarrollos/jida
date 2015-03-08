<?php
/**
* Clase Padre Jida
* @author Julio Rodriguez
* @package
* @version
* @category
*/

class Jida{
    
    private $reflector;
    private function inicializarReflector($clase){
        $this->reflector = new ReflectionClass($clase);
        return $this;
    }
    function obtPropiedadesPublicas($clase){
        $propiedades = $this->inicializarReflector($clase)->getProperties(ReflectionProperty::IS_PUBLIC);
        
    }
    
    
}
