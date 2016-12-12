<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/
namespace Jida\Render;
use Jida\Core  		as Core;
use Jida\Helpers 	as Helpers;
class Paginador extends  ListaSelector{
	use Core\ObjetoManager;
	var $linkFinal 			=TRUE;
	var $paginasMostradas 	=2;
	var $linkInicio 		=TRUE;
	var $classItemActivo	="active";
	var $classItems 		="";
	var $classPaginador  	='paginacion';
	var $htmlUltimoItem		=">>";
	var $htmlPrimerItem 	="<<";
	var $paginas;
	var $paginaActual; 
	var $urlPaginacion;
	var $registros;
	var $manejoParams = TRUE;
	//var $items 				= [];
    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct($config){
    	
		$this->establecerAtributos($config,$this);
		parent::__construct(0,['class'=>$this->classPaginador]);
		
		if(empty($this->urlPaginacion)){
			//Helpers\Debug::imprimir(JD('URL'),true);
			$this->urlPaginacion = JD('URL');}
		$this->_configurarItems();
    }
	private function _configurarItems(){
		$primeraPaginaMostrada = $this->paginaActual - $this->paginasMostradas;
		$primeraPaginaMostrada =($primeraPaginaMostrada>0)?$primeraPaginaMostrada:1;
		
		$ultimaPaginaMostrada = $this->paginaActual+$this->paginasMostradas; 
		for ($i=0; $i < $this->paginas; $i++) {
			$pagina = $i+1; 
			$this->items[$pagina] = new Selector('li');
			$link = new Selector('a',['href'=>$this->_url($pagina)]);
			$link->innerHTML($pagina);
			$this->items[$pagina]->innerHTML($link->render());
			if($pagina==$this->paginaActual)
				$this->items[$pagina]->addClass($this->classItemActivo);
		}
		if($this->paginaActual>2){
			//Solo se agrega el "volver al principio" si hay más de una página entre la actual 
			//y la pagina 1
			$this->items[0] = new Selector('li');
			$link = new Selector('a',['href'=>$this->_url(1)]);
			$link->innerHTML($this->htmlPrimerItem);
			
			$this->items[0]->innerHTML($link->render());
			
		}
		if($this->paginaActual+2<$this->paginas){
			$linkFinal = new Selector('li');
			$linkFinal->innerHTML(Selector::crear('a',
			['href'=>$this->_url($this->paginas),'class'=>'ultimo-item'],$this->htmlUltimoItem));
			array_push($this->items,$linkFinal);
		}
		#sort($this->items);
		#Helpers\Debug::imprimir($this->items,true);
		#Helpers\Debug::imprimir($this->items,true);
	}
	private function _url($pagina){
		
		return $this->urlPaginacion.'?'.http_build_query(['pagina'=>$pagina]);
		
	}
	
}
