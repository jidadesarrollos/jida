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
	
	/**
	 * Agrega una nueva columna al final de la fila
	 * 
	 * El contenido de la columna debe ser especificado por el desarrollador por
	 * medio de una función pasada como parametro. La función recibe el arreglo de columnas
	 * existentes
	 *
	 * @method agregarColumna
	 * @param function $funcion Funcion creada por el usuario. Debe retornar innerHTML.
	 */
	function agregarColumna($funcion){
		$nueva = new ColumnaSelector();
		$funcion($this,$nueva);
		array_push($this->columnas,$nueva);
	}
	
	

}
