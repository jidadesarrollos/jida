<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

class GeneradorController extends GeneradorObjeto{
    
	
	function crearController($nombreController,$callback=""){
		$this->nombreObjeto($nombreController);
		$controller = 	
			$this->abrirPHP()
			. $this->docBlock
			. $this->definicion()
			. $this->definirPropiedades()
			. $this->obtMetodos();
		if(!empty($callback)) $controller.=call_user_func($callback);
		
		$controller.=$this->saltodeLinea().$this->cierre();
		$directorio = DIR_APP.$this->modulo."Controller/";
		if(!Directorios::validar($directorio)) 
			Directorios::crear($directorio);
		$this
		->crear($directorio.$this->nombreArchivo())
		->escribir($controller)
		->cerrar();
		 
	}
	
	 function nombreObjeto($objeto="",$prefijos=""){
        if(empty($objeto)){
            return $this->nombreObjeto;
        }else{
        	if(!empty($prefijos))$objeto=preg_replace($prefijos, "", $objeto);
			
            $nombre = $this->String->upperCamelCase(
                      $this->String->obtenerSingular(str_replace("_", " ", $objeto))
                    );  
            $this->nombreObjeto=$nombre."Controller";  
        }
        
        return $nombre."Controller";
    } 
	function nombreArchivo($nombre=""){
		if(!empty($nombre)) 
			$this->nombreController($nombre);
		return $this->nombreObjeto.".class.php";
	}
	function nombreController($nombre){
		$this->nombreObjeto = $this->nombreObjeto($nombre)."Controller";
		return $this;
	}
	function documentacion($titulo,$contenido,$tags){
		$this->docBlock = $this->docBlock($titulo,$contenido,$tags);
		return $this;
	}
	
	
	
	function obtExtends(){
		return $this->extends;
	}
	
	function metodoIndex($callback=""){
		Debug::string("hola");
		$this->agregarMetodo('index',null,null,$callback);
		return $this;
	}
	function agregarPropiedad($prop=[]){
		if(count($prop)>1){
			$this->propiedades = array_merge($this->propiedades,$prop);	
		}else{
			$this->propiedades[]=$prop;
		}
		return $this;	
	}
	function agregarMetodo($nombre,$params,$ambito,$callback=""){
		
		$this->metodos[] = $this->crearFuncion($nombre,null,null,$callback);
		
	}
	
}
