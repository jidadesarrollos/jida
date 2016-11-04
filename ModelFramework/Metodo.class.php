<?PHP 
/**
 * Clase Modelo de metodos de un objeto
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category
 * @version
 */

namespace Jida\ModelFramework;
use Jida\BD as BD;
class Metodo extends BD\DataModel{
    
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
    var $metodo;
	/**
	 * Descrición de la funcionalidad del metodo
	 * 
	 * Descripcion breve de la funcionalidad que sirva para entendimiento
	 * de un usuario.
	 * @var $descripcion
	 * @access public
	 */
	 var $descripcion;
	 
	 protected $pk="id_metodo";
     protected $tablaBD="s_metodos";
	 
	 
	/**
     * Registra un metodo en base de datos
     * @method guardarMetodo
     */
	function guardarMetodo($data=""){
		if(!empty($data) and is_array($data)){
			$this->establecerAtributos($data);
			return $this->salvar();
		}
	}
	/**
	 * Verifica que los metodos de una clase se encuentren registrados en base de datos
     * 
     * Si los metodos del controlador no existen los registra, sin en la BD existen metodos
     * que ya no están disponibles en el controlador estos son eliminados
	 * 
	 * @param array $metodos Arreglo de metodos actuales de la clase
	 * @param int $idObjeto ID del objeto (clase) al que pertenecen los metodos
	 * @return boolean true
	 */
	function validarMetodosExistentes($metodosClase,$idObjeto){
		if(is_array($metodosClase)){
			
			$query = "select metodo from $this->tablaBD where id_objeto = $idObjeto";
			$result = $this->bd->ejecutarQuery($query);
			
			if($this->bd->totalRegistros>0){
				$metodosNuevos = array();
				$metodosEnBD = array();
				while($data = $this->bd->obtenerArrayAsociativo()){
					$metodosEnBD[] = $data['metodo'];
				}
				
				foreach($metodosClase as $key =>$metodo){
					if(!in_array($metodo, $metodosEnBD)){
						$nuevoMetodo = new Metodo();
						$nuevoMetodo->guardarMetodo(['id_objeto'=>$idObjeto,'metodo'=>$metodo]);
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
					$this->eliminar($metodosEliminados, 'metodo');
				}
			}else{
				$this->registrarMultiplesMetodos($metodosClase, $idObjeto);
			}
			
		}else{
			throw new Exception("Se espera un arreglo con los metodos de la clase", 1);
			
		}

	}//final funcioon
	
	
	/**
	 * Registra multiples metodos de un mismo objeto en base de datos
	 * 
	 * @param array $metodos Arreglo contenedor de los metodos
	 * @param int $idObjeto IDentificador del objeto al que pertenecen los metodos
	 */
	private function registrarMultiplesMetodos($metodos,$idObjeto){
		$insert= "insert into $this->tablaBD (id_objeto,metodo,descripcion) values ";
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
	}
}
