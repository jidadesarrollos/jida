<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

namespace Jida\Core\GeneradorCodigo;
use Jida\BD as BD;
use Jida\Helpers\Cadenas as Cadenas;
use Jida\Helpers as Helpers;
class GeneradorObjeto extends BD\DataModel{
    use GeneradorCodigo;
    protected $clase;
    protected $propiedades=[];
    protected $metodos=[];
    protected $traits=[];
    protected $interfaces=[];
    protected $String;
    protected $docBlock;
    protected $extends;
    private $Directorio;
	protected $modulo;
	var $extensionClass=TRUE;
    /**
     * @var dir $ubicacion Directorio donde se guardara el archivo del objeto
     */
    protected $ubicacion;
    /**
     * @var string $nombreObjeto Nombre del objeto
     */
    protected $nombreObjeto;
    /**
     * Funcion constructora
     * @method __construct
     */

    function __construct(){
        parent::__construct();
        $this->String= new Cadenas();
        $this->Directorio = new Helpers\Directorios();
        // $this->extension="class.php";
        $this->extension=".php";
    }

	function agregarExtend($nombreClase){
		$this->extends = $nombreClase;
		return $this;
	}
    /**
     * Retorna un string con la estructura de nombre de Un Objeto
     *
     * @method nombreObjeto
     * @param string $string Cadena de texto a convertir
     * @param mixed $prefijos Lista de prefijos que se desean ignorar para el nombre del objeto
     * @return string $nombre Cadena Resultante
     */
    function nombreObjeto($objeto="",$prefijos=""){
        if(empty($objeto)){
            return $this->nombreObjeto;
        }else{
        	if(!empty($prefijos))$objeto=preg_replace($prefijos, "", $objeto);

			$nombre = explode("_",$objeto);
				array_walk($nombre,function(&$valor,$clave){
					$valor = Cadenas::upperCamelCase(Cadenas::obtenerSingular($valor));
				});

			$nombre = implode("_", $nombre);
            $nombre = $this->String->upperCamelCase(
                      $this->String->obtenerSingular(str_replace("_", " ", $objeto))
                    );
            $this->nombreObjeto=$nombre;
        }

        return $nombre;
    }
	/**
	 * Crea un objeto 
	 */
    protected function crearClase(){
      if(!$this->Directorio->validar($this->ubicacion)) $this->Directorio->crear($this->ubicacion);
	  if($this->extensionClass)
      	$this->crear($this->ubicacion.$this->nombreObjeto.".class.php");
	  else {
		$this->crear($this->ubicacion.$this->nombreObjeto.".php");  
	  }
      $this->contenido='<?php'.$this->saltodeLinea();
      $this->contenido.=
          $this->docBlock
        . $this->saltodeLinea()
        . $this->definicion()
        . $this->saltodeLinea()
        . $this->definirPropiedades($this->propiedades)
        . $this->cerrarClase();

      //$this->saltodeLinea();
      //$this->definirVariables();
      //$this->saltodeLinea()->cerrarClase();

      $this->escribir()->cerrar();
    }

    function cerrarClase(){
        return "\n}//fin clase";


    }


    /**
     * Estructura las variables del objeto
     * @param array $vars Arreglo de variables del objeto, debe poseer los siguientes keys
     * 1. propiedad. 2. valor. 3. type. 4. doc. 5. Ambito
     *

     */
    function definirPropiedades($vars="",$doc=""){
    	if(empty($vars))
			$vars = $this->propiedades;
        $props="";

        foreach ($vars as $key => $prop) {
            $this->saltodeLinea();
            if(is_array($prop)){
                if(array_key_exists('doc', $prop))
                    $props.=$prop['doc']."\n";
                $props.=$this->tab();
                $ambito=(array_key_exists('ambito',$prop))?$prop['ambito']:'public';
                $props.=$ambito .' $'.$prop['propiedad'];
                if(array_key_exists("valor", $prop))
                    $props.="='".$prop['valor']."';\n";
                else
                    $props.=";\n";
            }else{
                $props.=$this->tab();
                $props.='var $'.$prop.";\n";
            }
        }
        return $props;
    }

    function definicion($extends=""){
    	if(empty($extends)) $extends=$this->extends;

        $def="class ";
        $def.=$this->nombreObjeto."";
        if(!empty($extends) and class_exists($extends))
        	$def.=$this->addExtends($extends);
        $def.=$this->apertura();
        return $def;
    }



    private function addExtends($extends){
        return " extends $extends";
    }

    protected function lineaDoc($content){
        $linea = " * ".$content."\n";
        return $linea;
    }

    function generarDocObjeto($intro,$content="",$tags=[]){
        $doc = "/**\n";
        $doc.=  $this->lineaDoc($intro)
                .$this->lineaDoc("")
                .$this->lineaDoc($content);
        if(count($tags)>0){
            foreach ($tags as $key => $value) {
                $linea = "@".$key." $value";
                $doc.=$this->lineaDoc($linea);
            }
        }
        $doc.="\n*/";
        return $doc;
    }

	function obtMetodos(){
		$lista="";

		if(!empty($this->metodos) and is_array($this->metodos)){
			for($i=0;$i<count($this->metodos);++$i){
				$lista.=$this->metodos[$i];
			}
		}
		return $lista;
	}
}
