<?php


class TablaSelector extends Selector{
		
	private $filas=[];
	private $totalFilas;
	private $htmlFilas;
	private $htmlCols;
	private $tHead;
	private $tBody;
	private $tFooter;
	private $dataTabla;
	private $dataThead;
	private $dataTfoot;
	var $selector = "TABLE";
	
	function __construct($data,$dataThead=[],$dataTfoot=""){
		parent::__construct();
		if(count($data)<1) throw new Exception("La informacion pasada a la tabla no es valida", 1);
		
		$this->totalFilas = count($data);
		$this->dataTabla = $data;
		$this->dataThead = $dataThead;
		$this->dataTfoot = $dataTfoot;
		if(count($dataThead)>0){
			if(count($dataThead) !=$this->obtTotalColumnas())
				throw new Exception("Los titulos de la tabla no coinciden con el contenido", 1);
			$this->validarTHead();	
		}
		
			
		$this->crearFilas();
			
	}
	
	function tHead($data){
		$this->dataThead = $data;
		$this->tHead = new Selector('THEAD');
		$this->validarTHead();
		$this->tHead->fila = new FilaSelector($this->dataThead);
		
	}
	private function validarTHead(){
		$this->tHead = new Selector('THEAD');
		$this->tHead->Fila = new FilaSelector($this->dataThead,'TH');
		
	}
	function obtTotalColumnas(){
		return count($this->dataTabla[0]);
	}

	/**
	 * Crea las filas de la tabla
	 * @method
	 */
	private function crearFilas(){
		
		foreach ($this->dataTabla as $idFila => $ColumnasFila) {
			$this->filas[$idFila]= new FilaSelector($ColumnasFila);
		}	
	}
	
	
	function generar(){
			$this->crearTHead();
			$this->crearTBody();
			$this->crearTFooter();
			
			return $this->render();
	}
	
	private function crearTHead(){
		if(is_object($this->tHead)){
			
			$this->innerHTML(
			$this->tHead->innerHTML($this->tHead->Fila->renderizar())->render()
			);
		}
			
		
	}
	private function crearTBody(){
		foreach ($this->filas as $key => $fila) {
			$this->innerHTML .= $fila->renderizar();
		}
		
		return $this;
	}
	private function crearTFooter(){
		
	}
	
	/**
	 * Ejecuta una funcion sobre una columna de la tabla
	 * @method funcionColumna
	 */
	function funcionColumna($columna,$funcion="",$fila=""){
		
		foreach ($this->filas as $key => $fila) {
				$keys = array_keys($fila->columnas);
			if(!array_key_exists($columna,$keys)) 
			throw new Exception("La columna indicada no existe en la vista",4);
			
				$fila->columnas[$keys[$columna]]->ejecutarFuncion($funcion);
		}
		return $this;
	}
	/**
	 * Inserta una columna al final de la tabla
	 * @method insertarColumna
	 */
	function insertarColumna($funcion){
		foreach ($this->filas as $key => $fila) {
			$fila->agregarColumna($funcion);
		}
		return $this;
	}
	
}
