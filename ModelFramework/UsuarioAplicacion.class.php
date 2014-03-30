<?PHP 
/**
 * Modelo de Usuario de la aplicacion
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * 
 * @category Model
 * @version 0.1 14-02-2014
 */

 
class UsuarioAplicacion extends DBContainer{
    
    
    /**
     * 
	 * 
	 * @var int $id_usuario Identificador del usuario
	 * @access public
     */
    var $id_usuario;
    
    /**
     *
	 * @var string $nombre_usuario Nombre del usuario 
	 * @access public 
     */
    var $nombre_usuario;
    /**
     *
     * @var string $clave_usuario Clave de acceso del usuario 
     * @access public 
     */
    var $clave_usuario;
    /**
     *
     * @var datetime $fecha_creado Fecha de creación de usuario 
     * @access public 
     */
    var $fecha_creacion;
    /**
     *
     * @var datetime $fecha_modificado Fecha de modificación del usuario 
     * @access public 
     */
    var $fecha_modificacion;
    /**
     * @var boolean activo
     */
     
    var $activo;
    /**
     * @var $id_estatus Estatus del usuario
     * @access public
     */
    var $id_estatus;
    /**
     * @var $ultima_sesion Fecha ultimo inicio de sesión
     * @access public
     */
    var $ultima_session;
    /**
     * Codigo encriptado usado para comprobar registro de usuario.
     * @var $validacion
     * @access public
     */
    var $validacion;
    /**
     * Perfiles asociados al usuario
     * @var $perfiles
     * @access private
     */
    protected $perfiles=array();
    function __construct($id=""){
        $this->nombreTabla="s_usuarios";
        $this->clavePrimaria="id_usuario";
        $this->unico=array('nombre_usuario');
        parent::__construct(__CLASS__,$id);
    }
    
    /**
     * Verifica que los datos para iniciar session sean validos
     */
    function validarLogin($usuario,$clave){
        try{
            $query = "select * from $this->nombreTabla where nombre_usuario='$usuario' and clave_usuario='$clave'";
			
            $result = $this->bd->ejecutarQuery($query);
            if($this->bd->totalRegistros>0){
                
                $datos = $this->bd->obtenerArrayAsociativo($result);
                $this->establecerAtributos($datos, __CLASS__);
                $this->obtenerPerfiles();
				
                return $datos;
            }else{
                return false;
            }
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
        
    }
    /**
     * Obtiene los perfiles asociados a un usuario de base de datos
     * @method obtenerPerfiles
     * @access private
     */
    private function obtenerPerfiles($idUser=""){
        if($idUser!=""){
            $this->id_usuario = $idUser;
        }
        $query = "select * from v_perfiles_usuario where id_usuario=$this->id_usuario";
        
        $data  = $this->bd->ejecutarQuery($query);
        while($perfil = $this->bd->obtenerArrayAsociativo($data)){
        	
            $this->perfiles[]=$perfil['clave_perfil'];
        }
    }
    /**
     * Retorna los perfiles asociados al usuario inicializado
     * @method getPerfiles
     * @access public
     */
    function getPerfiles(){
        return $this->perfiles;
    }
	
	function registrarSesion(){
		$query = "update s_usuarios set ultima_session =current_timestamp where id_usuario=$this->id_usuario";
		$this->bd->ejecutarQuery($query);
		
	}
	/**
	 * Registra los perfiles asociados a un usuario
	 * @method asociarPerfiles
	 * @param array $perfiles 
	 */
	function asociarPerfiles($perfiles){
		      
            $insert="insert into s_usuarios_perfiles values ";
            $i=0;
            foreach ($perfiles as $key => $idPerfil) {
                if($i>0)$insert.=",";
                $insert.="(null,$this->id_usuario,$idPerfil)";
                $i++;
            }
            
            $delete = "delete from s_usuarios_perfiles where id_usuario=$this->id_usuario";
            $this->bd->ejecutarQuery($delete);
            $this->bd->ejecutarQuery($insert);
            return array('ejecutado'=>1);
	}
    /**
     * Cambia la clave de un usuario
     * @method cambiarClave
     * @param $clave Clave actual insertada por el usuario, usada para validar
     * @param $nuevaClave Nueva clave a crear.
     */
    function cambiarClave($clave,$nuevaClave){
        $clave = md5($clave);
        if($clave===$this->clave_usuario){
            $this->clave_usuario = md5($nuevaClave);
            $this->salvarObjeto(__CLASS__);
            return true;
        }else{
            return false;
        }
    }
    
    function registrarUsuario($datos,$perfiles=""){
        if(empty($perfiles)){
            throw new Exception("Debe asociarse al menos un perfil al usuario a registrar", 1);
            
        }
        $this->establecerAtributos($datos);
        $this->fecha_creado=FechaHora::datetime();
        $this->fecha_modificacion=FechaHora::datetime();
        $codigo =hash("sha256",FechaHora::timestampUnix().FechaHora::datetime());
        $this->validacion=$codigo;
        $this->id_estatus=1;
        $this->activo=0;
        $guardado = $this->salvar();
        $guardado['codigo']=$codigo;
        if($guardado['ejecutado']==1){
            $this->id_usuario=$guardado['idResultado'];
            $this->asociarPerfiles($perfiles);
         
        }
        return $guardado;
    }
    
    function validarCodigoActivacion($codigo){
        $query = "select * from s_usuarios where validacion='$codigo'";
        $data = $this->bd->obtenerDataCompleta($query);
        
        if($this->bd->totalRegistros>0){
            $this->establecerAtributos($data[0]);
            
            $this->activo=1;
            $this->validacion=1;
            $guardado = $this->salvar();
            return $guardado;
        }else{
            return false;
        }
    }
}
?>