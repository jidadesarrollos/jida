<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category
 * @version
 */

 
class Objeto extends DBContainer{
    
    
    /**
     * Identificador unico numerico del  objeto
     * @var $id_objeto
     */
    var $id_objeto;
    /**
     * Nombre del Objeto
     * @var $objeto
     */
    var $objeto;
    /**
     * Numero identificador del componente al que pertenece el objeto
     * @var int $id_componente
     */
     var $id_componente;
    function __construct($id=""){
        $this->nombreTabla="s_objetos";
        $this->clavePrimaria="id_objeto";
        parent::__construct(__CLASS__,$id);
    }
    
    /**
     * Registra un objeto en base de datos
     */
    function setObjeto($data){
    	$this->establecerAtributos($data, __CLASS__);
		if(!$this->obtenerObjetoByName()){
			$accion =  $this->salvarObjeto(__CLASS__,$data);
			return $accion;	
		}else{
			
			return array('ejecutado'=>0,'msj'=>Mensajes::mensajeError("Ya existe el objeto <strong>$this->objeto </strong>"));
		}
		
        
    }
	
	/**
	 * Busca un objeto con el nombre con el nombre requerido
	 * @method obtenerObjetoByName
	 * @access private
	 * @param string $name Nombre del objeto
	 * @return array $result Resultado objenido, FALSE en caso de no existir el objeto
	 */
	private function obtenerObjetoByName($name=""){
		if(!empty($name)) $this->objeto = $name;
		$query = "select * from $this->nombreTabla where objeto = \"".$this->objeto."\"";
		$result = $this->bd->ejecutarQuery($query);
		
		if($this->bd->totalRegistros>0){
			return $result;
		}else{
			return false;
		}
		
	}
    
    /**
     * Registra los perfiles que tienen acceso a un objeto determinado
     * 
     * @param array $perfiles Perfiles con acceso al objeto
     * @param string $metodo ID del metodo al que se asigna el permiso
     * @return boolean true or false 
     */
    function asignarAccesoPerfiles($perfiles){
        try{    
            $insert="insert into s_objetos_perfiles values ";
            $i=0;
            
            $componente = new Componente($this->id_componente);
            
#            $componente->validarAccesoComponente($perfiles);
            
            foreach ($perfiles as $key => $idPerfil) {
                if($i>0)$insert.=",";
                $insert.="(null,$idPerfil,$this->id_objeto)";
                $i++;
            }
            
            $delete = "delete from s_objetos_perfiles where id_objeto=$this->id_objeto";
            $this->bd->ejecutarQuery($delete);
            $this->bd->ejecutarQuery($insert);
            return array('ejecutado'=>1);
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
    }
}


?>