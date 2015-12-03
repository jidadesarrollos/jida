<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

class FilaSelector extends Selector{
    
	var $selector = "TR";
	private $selectorColumnas="TD";	
	var $columnas;
	var $dataColumnas;
	private $totalColumnas;
	function __construct($columnas,$selectorColumnas="TD"){
		parent::__construct();
		$this->dataColumnas = $columnas;
		$this->selectorColumnas = $selectorColumnas;
		$this->totalColumnas = count($this->dataColumnas);
		$this->crearColumnas();
	}
	
	
	private function crearColumnas(){
		foreach ($this->dataColumnas as $key => $col) {
			
			$this->columnas[$key] = new ColumnaSelector($this->selectorColumnas);
			
			$this->columnas[$key]->innerHTML($col);
		}
		
				
	}
	
	private function generarContenido(){
		foreach ($this->columnas as $key => $columna) {
			
			$this->innerHTML.=$columna->render();
			
		}
		
		return $this;	
	}
	
	function renderizar(){
		$html = $this->generarContenido()->render();
		return $html;
	}

}
