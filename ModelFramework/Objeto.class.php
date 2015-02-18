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
    /**
     * @var string $descripcion Registra una descripcion o nombre entendible para el usuario
     */
    var $descripcion;
    /**
     * Clase constructora
     * @method __construct
     */
    function __construct($id=""){
        $this->nombreTabla="s_objetos";
        $this->clavePrimaria="id_objeto";
        parent::__construct(__CLASS__,$id);
    }
    
    /**
     * Registra un objeto en base de datos
     * 
     * @method setObjeto
     * @param array $data $_POST capturado del formulario
     * @return array $accion Arreglo $guardado en caso de que se realice el registro, arreglo con msj de error caso contrario
     * @see DBContainer::salvar
     */
    function setObjeto($data){
    	$this->establecerAtributos($data, __CLASS__);	
		$accion =  $this->salvar($data);
		return $accion;	
		
		
        
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
        
        $insert="insert into s_objetos_perfiles values ";
        $i=0;
        
        $componente = new Componente($this->id_componente);
        
        foreach ($perfiles as $key => $idPerfil) {
            if($i>0)$insert.=",";
            $insert.="(null,$idPerfil,$this->id_objeto)";
            $i++;
        }
        
        $delete = "delete from s_objetos_perfiles where id_objeto=$this->id_objeto";
        $this->bd->ejecutarQuery($delete);
        $this->bd->ejecutarQuery($insert);
        return array('ejecutado'=>1);
    
    }//fin función
    /**
     * 
     */
    function obtenerObjetosMetodos($config=""){
        $objetos="";
        if(is_array($config)){
            $objetos=[];
            foreach ($config as $key => $value) {
                $objetos[$key]=$value;
            }
        } 
        $objetos = $this->getTabla(null,$objetos,'descripcion','id_objeto');
        $idsObjetos = Arrays::obtenerKey('id_objeto', $objetos);
        
        $queryMetodos = "select id_metodo,metodo,descripcion,id_objeto from s_metodos where id_objeto in (".implode(",", $idsObjetos).") order by id_objeto";
        $resultMetodos = $this->bd->ejecutarQuery($queryMetodos);
        while ($metodo = $this->bd->obtenerArrayAsociativo()) {
            $objetos[$metodo['id_objeto']]['metodos'][$metodo['id_metodo']]=$metodo;
            
        }
        return $objetos;   
        
    }
}


?>