<?php
/**
 * Objeto Encargado del Manejo de URLS
 * @author julio Rodriguez
 * 
 */
 namespace Jida\Core\Manager;
 
 class Parser{
 	private $_config = FALSE;
	
 	public function __construct(){
 		if(class_exists('\App\Config\Url')){
 			$this->_config = new \App\Config\Url();
 		}
	}
 }
