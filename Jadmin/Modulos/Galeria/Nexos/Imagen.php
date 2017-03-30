<?php

namespace Jida\Jadmin\Modulos\Galeria\Nexos;
use Jida\Jadmin\Modulos\Galeria\Controllers\GaleriaController as ParentController;

class Imagen extends ParentController{
	
	private $_img;
	private $_meta_data;
	function __construct(){
		parent::__construct();
		$this->_meta_data = new \stdClass();
	}
	function _instanciar($img){
		$this->_img = $img;
		
		if(!empty($this->_img->meta_data)){
			
			$this->_meta_data = json_decode($this->_img->meta_data);
		}
	}
	
	function data($tipo,$img=""){
			
		if(!empty($img)){
			$this->_instanciar($img);
		}
		
		if(property_exists($this->_meta_data, $tipo )){
			return $this->_meta_data->{$tipo};
		}
		return false;
		
	}
}
