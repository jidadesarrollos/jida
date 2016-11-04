<?php

namespace Jida;
trait GeneradorCodigo{
	use GeneradorArchivo;

	function abrirPHP(){
		return "<?php\n";
	}
	/**
	 * Genera un bloque inicial de codigo
	 * @method docBlock
	 * @param string $titulo,
	 * @param string $contenido,
	 * @param array $tags
	 */
	function docBlock($titulo="",$contenido="",$tags=[]){
		$doc ="/**\n";
		if(!empty($titulo)) 	$doc.=" * $titulo \n";
		if(!empty($contenido)) 	$doc.=" * $contenido\n";
		$doc.=$this->tags($tags);
		$doc.="\n*/\n\n";
		return $doc;
	}
	private function tags($tags){
		$doc="";
		foreach ($tags as $key => $value) {

			$doc.=" * @".$key;
			if(is_array($value)){
				if(array_key_exists('type', $value)) $doc.= " ".$value['type']." ";
				if(array_key_exists('desc', $value)) $doc.= " ".$value['desc']." ";
				if(array_key_exists('name', $value)) $doc.= " ".$value['name']." ";
			}else{
				$doc.=" $value \n";
			}
		}//fin foreach-------------

		return $doc;
	}

	function cadena($string){
		return '\''.$string.'\'';
	}
	/**
	 * Crea la documentacion de una constante
	 * @method docConstante
	 * @var string $nombre Nombre de la constante
	 * @var string $type Tipo de dato
	 * @var string $descripcion  Descripcion de la constante
	 */
	function docConstante($nombre,$type,$descripcion){
		$doc = "/**\n *";
		$doc.=" @constante $nombre $descripcion \n */";
		return $doc;
	}

	function constante($nombre,$valor,$type,$descripcion){
		$constante = $this->docConstante($nombre, $type, $descripcion);
		$constante.="\n".'define(\''.$nombre.'\',\''.$valor.'\');'.$this->saltodeLinea();
		return $constante;
	}
	/**
	 * Crea la estructura de un arreglo
	 *
	 * @method definirArray
	 * @param $name Nombre del arreglo
	 * @param array $valores Arreglo de valores a escribir en el arreglo creado
	 * @return string $array String de código creado
	 */
	function definirArray($name,$valores=[]){
		$array =$name."=[\n";
		$ini=0;


		foreach ($valores as $key => $value) {
			if(is_array($value)){
				if($ini>0)
					$array.=",\n";
				$this->valorArray($key,$array,$value);
				++$ini;
			}else{
				$tam = strlen($key);
				$tabs=3;
				if($ini>0)$array.=",\n";
				if($tam>7)--$tabs;
				$array.="\t".$this->cadena($key)."".$this->tab($tabs)."=>".$this->tab(2).$this->cadena($value);
				++$ini;
			}

		}
		$array.="\n\r];";
		return $array;
	}

	private function valorArray($key,&$array,$value,$margen=2){
			$margen=2;
			$array.=$this->tab($margen-1).$this->cadena($key)."=>[\n";
			$ini2=0;

			foreach ($value as $key2 => $value2) {

				if(is_array($value2)) $this->valorArray($array, $value2,$margen+1);
				else{
					$tam = strlen($key2);
					$tabs=3;
					if($ini2>0)$array.=",\n";
					if($tam>7)--$tabs;
					$array.=$this->tab($margen).$this->cadena($key2)."".$this->tab($tabs)."=>".$this->tab(2).$this->cadena($value2);
					++$ini2;
				}
			}
			$array.="\r".$this->tab($margen-1)."]";
	}

	/**
	 * Permite agregar una funcion
	 * @method definirFuncion
	 *
	 */
	protected function crearFuncion($nombre,$params=[],$ambito,$contenido=""){

		$arrayAmbito=['public','private','protected','static'];
		$funcion = "";
		if(!in_array($ambito, $arrayAmbito)) $ambito="";
		if(!empty($ambito)) $funcion.=$ambito." ";
		$funcion =
			"function ".String::lowerCamelCase($nombre)."(";
		if(count($params)>0) $funcion.= implode(",", $params);
		$funcion.=")".$this->apertura();
		if(!empty($contenido)){
			$funcion .= call_user_func($contenido);

		}
		$funcion .= $this->saltodeLinea().$this->tab(1).$this->cierre();

		return $funcion;
	}

	protected function apertura(){	return "{\n\t"; }
	protected function cierre(){   	return "}"; }

	protected function cerrarPHP(){
		 return "\r?>";
	}
}
