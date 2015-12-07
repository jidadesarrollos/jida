<?php
/**
 * Clase para manejo de Vistas Dinamicas
 * 
 */
 class JVista extends DataModel{
 	
	
	private $dataVista;
	private $acciones=[];
	private $formulario;
	
	function __construct($ejecucion,$title){
		$dataConsulta = explode(".", $ejecucion);
		if(count($dataConsulta)>1){
			$object = new $$dataConsulta[0]();
			if(method_exists($object, $dataConsulta[1])){
				$this->dataVista = $object->$$dataConsulta[1];	
			}else
				throw new Exception("No existe el metodo pasado", 1);
				
			
		}else{
			$object = new $$ejecucion();
			$this->dataVista = $object->consulta()->obt();
		}
	}
	
	
		
	
 }//fin clase