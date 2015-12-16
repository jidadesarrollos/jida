<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

class AccionVistaSelector extends Selector{
    /**
	 * @var object Span Objeto Selector Span dentro del objeto Accion
	 */
	var $span;
	private $dataAccion;
	private $nombreAccion;
	/**
	 * 
	 */
	function __construct($inner,$data,$selector = "a"){
		
		parent::__construct($selector);
		$this->innerHTML($inner);
		$this->dataAccion = $data;
		if(array_key_exists('span', $data)){
			$this->span = new Selector('span');
			$this->span->attr($data['span']);
			unset($this->dataAccion['span']);
		}
		$this->armarValores();
		
	}
	function nombreAccion(){
		return $this->nombreAccion;
	}
	/**
	 * @method armarValores
	 */
	private function armarValores(){
		$this->nombreAccion = String::lowerCamelCase($this->innerHTML());
		$this->attr('id',String::lowerCamelCase("accion ".$this->innerHTML()));
		
		$this->attr($this->dataAccion);
	}
	function render(){
		if($this->span instanceof Selector){
			if(empty($this->innerHTML())){
				$this->innerHTML($this->span->render().$this->innerHTML());
			}
			
		}
		
		return parent::render();
	}
}
