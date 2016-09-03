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

 
class User extends DataModel{
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
    var $nombres;
    var $apellidos;
    var $correo;
    var $sexo;
    /**
     * @var boolean activo Indica si el usuario se encuentra conectado
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
     * Codigo creado para recuperación de clave
     * @var $codigo_recuperacion
     * @access public
     */
    var $codigo_recuperacion;
    /**
     * Url de la imagen del perfil
     * @var url $img_perfil
     */
    var $img_perfil;
    /**
     * Perfiles asociados al usuario
     * @var $perfiles
     * @access private
     */
    protected $perfiles=[];

    protected $tablaBD = "s_usuarios";
    protected $pk = "id_usuario";
    protected $unico =['nombre_usuario'];
    protected $registroUser = FALSE;
	
    /**
     * Verifica que los datos para iniciar session sean validos
     * 
     * 
     * @param mixed Nombre del Usuario
     * @param mixed clave  Clave del usuario sin convertir a md5
     * @param boolean [opcional] validacion Determina si se debe validar el campo validacion en bd
     * @return mixed array si la sesion es iniciada, false caso contrario 
     */
    function validarLogin($usuario,$clave,$validacion=true,$callback=null){
        $clave = md5($clave);
        
        $result = $this ->select()
                        ->filtro(['clave_usuario'=>$clave,'nombre_usuario'=>$usuario,'validacion'=>1])
                        ->fila();
        if($this->bd->totalRegistros>0){
            
            $this->establecerAtributos($result);
			
			$this->__obtConsultaInstancia($this->id_usuario);
			$this->obtenerDataRelaciones();
            $this->registrarSesion();
            $this->activo=1;
            $this->salvar();
            $this->obtenerPerfiles();
			
            return $result;
        }else{
            return false;
        }
    }
	private function  stringConsulta(){
		return "select 
			a.id_usuario_perfil AS id_usuario_perfil,
			a.id_perfil AS id_perfil,
			a.id_usuario AS id_usuario,
			c.nombre_usuario,
			c.nombres,
			c.apellidos,
			b.clave_perfil AS clave_perfil
		from
			s_usuarios_perfiles a
			join s_perfiles b ON (a.id_perfil = b.id_perfil)
			join s_usuarios c on (a.id_usuario = c.id_usuario)";
		
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
        if(count($this->perfiles)<1){
            $query = $this->stringConsulta()." where a.id_usuario=$this->id_usuario";
            $data  = $this->bd->ejecutarQuery($query);
            if(count($data)>1)  throw new Exception("No se han obtenido los perfiles del usuario", 1);
            while($perfil = $this->bd->obtenerArrayAsociativo($data))	
                $this->perfiles[]=$perfil['clave_perfil'];
            
        }
    }
    /**
     * Retorna los perfiles asociados al usuario inicializado
     * @method getPerfiles
     * @access public
     */
    function getPerfiles(){
        $this->obtenerPerfiles();
        return $this->perfiles;
    }
	/**
     * Registra la sesion del usuario
     * 
     * Registra la fecha actual como ultima sesion del usuario y cambia el
     * estatus activo a 1
     * @method registrarSesion
     * @return boolean
     */
	function registrarSesion(){
	    if(!empty($this->id_usuario)){
    		$this->salvar(['ultima_session'=>'current_timestamp','activo'=>1]);
            Session::sessionLogin();
		}else return false;
		
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
            $delete = "delete from s_usuarios_perfiles where id_usuario=$this->id_usuario;";
			
            $this->bd->ejecutarQuery($delete.$insert,2);
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
            $this->salvar();
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Registra un nuevo usuario y asigna los perfiles asociados
     * @method registrarUsuario
     * @param array $datos Arreglo con información del usuario (clave, nombre de usuario)
     * @param array $perfiles Perfiles que se asocian al usuario a registrar
     */
    function registrarUsuario($datos,$perfiles="",$validacion=TRUE){
        if(empty($perfiles)){
            throw new Exception("Debe asociarse al menos un perfil al usuario a registrar", 1);
            
        }
        $this->establecerAtributos($datos);
        if($validacion===TRUE){
        	$codigo =hash("sha256",FechaHora::timestampUnix().FechaHora::datetime());
	        $this->validacion=$codigo;    
	        $this->activo=0;
        }
        $this->id_estatus=(empty($this->id_estatus))?1:$this->id_estatus;
        if($this->salvar()->ejecutado()){
            $this->id_usuario=$this->resultBD->idResultado();
            
            $this->asociarPerfiles($perfiles); 
        }
        return ['idResultado'=>$this->resultBD->idResultado(),'ejecutado'=>$this->resultBD->ejecutado(),'unico'=>$this->resultBD->esUnico(),'validacion'=>$this->validacion];
    }
    /**
     * Verifica el codigo de activacion creado en el registro de un usuario
     * 
     * Si el codigo de activacion coincide con el de un usuario registrado, el 
     * valor es cambiado a 1 quedando el usuario activo
     * 
     * 
     * @method validarCodigoActivacion
     * @param md5 $codigo
     * @return boolean TRUE si el usuario es activado, FALSE sino coincide 
      */
    function validarCodigoActivacion($codigo){
        
        $data = $this->select()->filtro(['validacion'=>$codigo])->fila();
        if($this->bd->totalRegistros>0){
            $this->establecerAtributos($data);
            $this->activo=1;
            $this->validacion=1;
			$this->id_estatus=1;
            if($this->salvar()->ejecutado()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    function obtenerUsuarioByEmail($correo){
        $data  = $this->select()->filtro(['correo'=>$correo])->fila();
        
        if($this->bd->totalRegistros>0){
            
            $this->establecerAtributos($data);
            $this->registrarSesion();
            return true;
        }else return false;
        
    }
    /**
     * Cierra la sesión de un usuario
     * @method cerrarSesion
     * @param int $idUser Id del Usuario a cerrar sesion, si no es pasado se tomará el id instanciado
     */
    function cerrarSesion($idUser=""){
        if(empty($idUser))
            $idUser=$this->id_usuario;
        $this->activo=0;
        $this->salvar();
    }
	
	function agregarPerfilSesion($perfil){
		if(is_array($perfil)){
			$this->perfiles = array_merge($this->perfiles,$perfil);
		}
		$this->perfiles[]=$perfil;
		
	}
	function crearSesionUsuario(){
		Session::sessionLogin();
        Session::set('Usuario',$this);
        //Se guarda como arreglo para mantener soporte a aplicaciones anteriores
        if(isset($data))
        Session::set('usuario',$data);
        return $this;
	}
	
	function guardarSesion(){
		Session::set('Usuario',$this);
	}

}
    