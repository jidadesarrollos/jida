<?PHP 
/**
 * Clase Modelo de metodos de un objeto
 * 
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category
 * @version
 */

 
class Metodo extends DBContainer{
    
    
    /**
     * Identificador numerico del metodo
	 * 
	 * @var id_metodo 
	 * @access public
     */
    var $id_metodo;
    /**
	 * Identificador númerico unico del objeto al que pertenece el metodo
	 * 
	 * @var int $id_objeto
	 * @access public 
     */
    var $id_objeto;
	/**
	 * Nombre del metodo
	 * 
	 * @var string $nombre_metodo
	 * @access public 
     */
    var $nombre_metodo;
	/**
	 * Descrición de la funcionalidad del metodo
	 * 
	 * Descripcion breve de la funcionalidad que sirva para entendimiento
	 * de un usuario.
	 * @var $descripcion
	 * @access public
	 */
	 var $descripcion;
    function __construct($id=""){
        $this->nombreTabla="s_metodos";
        $this->clavePrimaria="id_metodo";
        parent::__construct(__CLASS__,$id);
    }
	
	function guardarMetodo($data=""){
		if(!empty($data) and is_array($data)){
			$this->establecerAtributos($data,__CLASS__);
			$accion = $this->salvarObjeto(__CLASS__);
			return $accion;
		}
	}
	/**
	 * Verifica que los metodos de una clase se encuentren registrados en base de datos
	 * 
	 * @param array $metodos Arreglo de metodos actuales de la clase
	 * @param int $idObjeto ID del objeto (clase) al que pertenecen los metodos
	 * @return boolean true
	 */
	function validarMetodosExistentes($metodosClase,$idObjeto){
		try{
			if(is_array($metodosClase)){
				
				$query = "select nombre_metodo from $this->nombreTabla where id_objeto = $idObjeto";
				$result = $this->bd->ejecutarQuery($query);
				//$data = $this->bd->obtenerDataCompleta($query);
				
				if($this->bd->totalRegistros>0){
					$metodosNuevos = array();
					$metodosEnBD = array();
					while($data = $this->bd->obtenerArrayAsociativo()){
						$metodosEnBD[] = $data['nombre_metodo'];
					}
					
					foreach($metodosClase as $key =>$metodo){
						if(!in_array($metodo, $metodosEnBD)){
							$nuevoMetodo = new Metodo();
							$nuevoMetodo->guardarMetodo(array('id_objeto'=>$idObjeto,'nombre_metodo'=>$metodo));
						}
					}
					/* Validar metodos que ya no existan*/
					$metodosEliminados =array();
					foreach($metodosEnBD as $key =>$metodo){
						if(!in_array($metodo, $metodosClase)){
							$metodosEliminados[]=$metodo;
						}
					}
					if(count($metodosEliminados)>0){
						$this->eliminarMetodos($metodosEliminados);
					}
				}else{
					$this->registrarMultiplesMetodos($metodosClase, $idObjeto);
				}
				
				
			}else{
				throw new Exception("Se espera un arreglo con los metodos de la clase", 1);
				
			}
		}catch(Exception $e){
			Excepcion::controlExcepcion($e);
		}
	}//final funcioon
	
	/**
	 * Elimina multiples metodos a partir del nombre
	 * 
	 * @param array $metodos Arreglo con los nombres de los metodos a eliminar 
	 * 
	 */
	private function eliminarMetodos($metodos){
			$this->eliminarMultiplesDatos($metodos, 'nombre_metodo');
	}
	/**
	 * Registra multiples metodos de un mismo objeto en base de datos
	 * 
	 * @param array $metodos Arreglo contenedor de los metodos
	 * @param int $idObjeto IDentificador del objeto al que pertenecen los metodos
	 */
	private function registrarMultiplesMetodos($metodos,$idObjeto){
		$insert= "insert into $this->nombreTabla (id_objeto,nombre_metodo,descripcion) values ";
		$i=0;
		foreach ($metodos as $key => $value) {
			if($i>0)
				$insert.=",";
			$insert.="($idObjeto,\"$value\",null)";
			$i++;
		}
		$this->bd->ejecutarQuery($insert);
	}//final funcion
	
	/**
	 * Registra los perfiles que tienen acceso a un metodo determinado
	 * 
	 * @param array $perfiles Perfiles con acceso al metodo
	 * @param string $metodo ID del metodo al que se asigna el permiso
	 * @return boolean true or false 
	 */
	function asignarAccesoPerfiles($perfiles){
		try{	
			$insert="insert into s_metodos_perfiles values ";
			$i=0;
			foreach ($perfiles as $key => $idPerfil) {
				if($i>0)$insert.=",";
				$insert.="(null,$this->id_metodo,$idPerfil)";
				$i++;
			}
			$delete = "delete from s_metodos_perfiles where id_metodo=$this->id_metodo";
			$this->bd->ejecutarQuery($delete);
			$this->bd->ejecutarQuery($insert);
			return array('ejecutado'=>1);
		}catch(Exception $e){
			Excepcion::controlExcepcion($e);
		}
	}
}


?>