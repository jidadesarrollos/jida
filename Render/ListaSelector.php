<?php
/**
 *
 */

namespace Jida\Render;
class ListaSelector extends Selector{


	protected $selector = "UL";
	protected $selectorItems = "LI";

	protected $items =[];


	function __construct($numeroItems=0,$attr=[]){
		parent::__construct($this->selector,$attr);
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

	function render(){
#		\Jida\Helpers\Debug::imprimir($this->items);		foreach ($this->items as $key => $item) {
			$this->addFinal($item->render());
		}
		// for($i=0;$i<count($this->items);++$i){
		// #foreach ($this->items as $key => $item) {
			// if(array_key_exists($i, $this->items))
				// $this->addFinal($this->items[$i]->render());
			// //$this->innerHTML.=$item->render();
		// }
		return parent::render();
	}

}
