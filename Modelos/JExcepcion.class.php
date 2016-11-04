<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

namespace Jida\ModelFramework;
class JExcepcion{
	private $excepcion;


    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct(Exception $e){
    	$this->excepcion = $e;
	
    }
	function trazaString(){
		return $this->excepcion->getTraceAsString();
	}
	function exception(){
		return $this->excepcion;
	}
	function codigo(){
		return $this->excepcion->getCode();
	}
	function mensaje(){
		return $this->excepcion->getMessage();
	}
	function linea(){
		return $this->excepcion->getLine();
	}
	function traza(){
		return $this->excepcion->getTrace();
	}
	private function procesarExcepcion()
	{
		
			
		foreach ($this->excepcion as $key => $property) {
			if(property_exists(__CLASS__, $property)){
				$this->{$property} = $property;
			}
		}	
	}
}
