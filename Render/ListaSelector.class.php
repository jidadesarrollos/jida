<?php
/**
 * 
 */
 
 class ListaSelector extends Selector{
 	
	
	protected $selector = "UL";
	protected $selectorItems = "LI";
	
	private $items =[];
	
	
	function __construct($numeroItems=0){
		parent::__construct($this->selector);
	}
	
	/**
	 * Agrega un item a la lista
	 * 
	 * El item agregado sera un objeto de Tipo Selector con valor de
	 * $selectorItem
	 * @method addItem
	 * @see $selectorItems
	 * @see Selector
	 */
	function addItem($contenido){
		$item = new Selector($this->selectorItems);
		$item->innerHTML($contenido);
		$this->items[] = $item;

		return end($this->items);	
	}
	
	function renderizar(){
		
		foreach ($this->items as $key => $item) {
			
			$this->innerHTML.=$item->render();
		}
		return $this->render();
	}
	
 }
