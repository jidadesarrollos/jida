<?PHP 


class UsersController extends Controller{
	
	
	function __construct(){
        $this->url='/jadmin/users/';
        parent::__construct();
        $this->layout="jadmin.tpl.php";
        
    }
    
	function index(){
	    
		$vista = $this->vistaUser();
		$this->data['vista'] = $vista->obtenerVista();
			
	}
    /**
     * Genera el grid de visualización de usuarios
     * @method vistaUser
     * @access protected
     * @param $url =
     */
    protected function vistaUser($url=null){
        if(empty($url)){
            $url = $this->url;
        }
        $query = "select id_usuario,nombre_usuario \"Nombre Usuario\", fecha_creacion \"Fecha Creaci&oacute;n\",
                    activo \"Activo\",ultima_session \"&Uacute;ltima Sesi&oacute;n\",  b.estatus
                 from s_usuarios a 
                 join s_estatus b on (a.id_estatus=b.id_estatus)";
        
        $vista = new Vista($query,$GLOBALS['configPaginador'],"Usuarios");
        $vista->tipoControl=2;
        $vista->setParametrosVista($GLOBALS['configVista']);
        $vista->filaOpciones=array(  0=>array('a'=>array(
                                                            'atributos'=>array( 'class'=>'btn',
                                                                                'title'=>'Asignar perfiles de acceso',
                                                                                'href'=>"/jadmin/users/asociar-perfiles/usuario/{clave}"
                                                                                ),
                                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-edit'))))))
                                                ;
        $vista->acciones=array('Registrar'=>array('href'=>$url.'/set-usuario'),
                               'Modificar'=>array('href'=>$url.'/set-usuario','data-jvista'=>'seleccion','data-jkey'=>'u'),
                                '<span class="fa fa-trash-o"></span>'=>array('href'=>$url.'/eliminar-usuario','data-jvista'=>'seleccion',
                                                                'data-multiple'=>'true','data-jkey'=>'u')                                
                                );
        return $vista;
    }

	/**
     * Muestra formulario de gestión de usuarios
     * 
     * El metodo puede ser configurado en controladores que hereden de UsersControllers por
     * medio de los parametros que solo son pasados al ser llamado explicitamente
     * @param string $url Url del metodo que hereda
     * @param $externo Consulta sql para filtrar los perfiles a mostrar
     * @param $idVista Id de la vista en la cual mostrar mensaje de suceso
     * @param $urlVista Url de la vista a la cual redireccionar
     * @method setUsuario
     */
	function setUsuario($url="",$externo="",$idVista='usuarios',$urlVista=""){
	    $urlVista =(empty($urlVista))?$this->url:$urlVista;
	    $id ="";
	    if(isset($_GET['u']) and $this->getEntero($_GET['u']))
	       $id = $_GET['u'];
        
	    $datosForm =  $this->formGestionUser($id,$url,$externo);
        $form=& $datosForm['form'];
        $form->tituloFormulario="Gesti&oacute;n de Usuarios";
        if(isset($_POST['btnRegistroUsuarios'])):
            $_POST['clave_usuario']=md5($_POST['clave_usuario']);
            if($datosForm['guardado'] and $datosForm['guardado']['ejecutado']==1){
                $msj = 'El usuario '.$_POST['nombre_usuario']." ha sido creado exitosamente";
                
                Vista::msj($idVista, 'suceso', $msj,$urlVista);
            }else{
                
                Session::set('__msjForm',Mensajes::crear('error',"No se ha podido registrar el usuario, vuelva a intentarlo"),false);
            }
        endif;
        $this->data['form'] = $form->armarFormulario();
        
	}
    /**
     * Devuelve el formulario para gestion de usuarios
     * 
     * Devuelve el html del usuario configurado con el action del form hacia un metodo 'set-componente' del
     * controlador en el cual sea llamado
     * @method formGestionUser
     * @param int $tipoform
     * @param $campoUpdate
     * @return array $form Arreglo asociativo con dos posiciones 'guardado' result del save de DBContainer 'form' Objeto Formulario
     */
    protected function formGestionUser($campoUpdate="",$metodo='set-usuario',$externo=""){
        $metodo = (empty($metodo))?'set-usuario':$metodo;
        $tipoForm=(!empty($campoUpdate))?2:1;
        $form = new Formulario(array('RegistroUsuarios','PerfilesAUsuario'),$tipoForm,$campoUpdate,2);
        
        $form->externo['id_perfil']=(!empty($externo))?$externo:"select id_perfil, nombre_perfil from s_perfiles";
        $form->valueBotonForm=(!is_null($campoUpdate))?'Actualizar Datos':'Registrar Usuario';
        $form->action=$this->url.'/'.$metodo;
        $retorno=array('guardado'=>'','form'=>'');
        if(isset($_POST['btnRegistroUsuarios'])):
            $validacion  = $form->validarFormulario();
            
            if($validacion===TRUE){
                
                $user = new User();
                $user->validacion=1;
                $_POST['clave_usuario'] = md5($_POST['clave_usuario']);
                $guardado = $user->salvar($_POST,true);
                if($guardado['ejecutado']==1){
                    $user->id_usuario=$guardado['idResultado'];
                    $user->asociarPerfiles($_POST['id_perfil']);
                }else{
                    
                }
                $retorno['guardado']=$guardado;
                 
            }else{
                Debug::mostrarArray($validacion);
                $retorno['guardado'] =$validacion; 
            }
            
        endif;
        $retorno['form']=$form;
        return $retorno;
    }
	    /**
     * Devuelve el formulario para gestion de usuarios
     * 
     * Devuelve el html del usuario configurado con el action del form hacia un metodo 'asociar-perfiles' del
     * controlador en el cual sea llamado
     * @method formAsignacionPerfiles
     * @param int $tipoform
     * @param $campoUpdate Id del usuario al que se asignaran los perfiles
     */
    protected function formAsignacionPerfiles($campoUpdate=""){
        
        $tipoForm=(!empty($campoUpdate))?2:1;
        $form = new Formulario('PerfilesAUsuario',$tipoForm,$campoUpdate,2);
        $form->valueBotonForm='Asignar Perfiles';
        $form->action=$this->url.'asociar-perfiles';    
        $retorno=array('form'=>'');
        $retorno['form']=$form;
        return $retorno;
    }
    /**
     * Realiza el proceso de registro de usuarios
     * 
     * La data a registrar debe haber sido validada previamente
     *
     * @method registrarPerfilesDeUsuario
     * @param object $formulario Objeto Formulario de Perfiles a Usuario instanciado
     * @param mixed $user Objeto instanciado de usuario o en su defecto el id del usuario
     */
    protected function registrarPerfilesDeUsuario($form,$user,$perfiles){
        if(!is_object($user)){
            $user = new User($user);
            $user->asociarPerfiles($perfiles);
        }
        $ejct = $user->asociarPerfiles($perfiles);
       if( $ejct['ejecutado']==1){
           return true;
       }else{
           return false;
       } 
    }
	function asociarPerfiles(){
        
        if(isset($_GET['usuario']) and $this->getEntero($_GET['usuario'])!=""){
            
            $form = new Formulario('PerfilesAUsuario',2,Globals::obtGet('usuario'),2);
            $user = new User($this->getEntero(Globals::obtGet('usuario')));
            
            $form->action=$this->url."asociar-perfiles/usuario/".Globals::obtGet('usuario');
            $form->valueSubmit="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar perfiles al usuario $user->nombre_usuario";
            
            if(isset($_POST['btnPerfilesAUsuario'])){
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    $accion = $user->asociarPerfiles(Globals::obtPost('id_perfil'));
                    if($accion['ejecutado']==1){
                        Session::set('__idVista', 'componentes');
                        $msj = Mensajes::mensajeSuceso('Asignados los perfiles al usuario '.$user->nombre_usuario);
                        Session::set('__msjVista',$msj);
                        redireccionar($this->url);
                    }else{
                        
                        $msj = Mensajes::mensajeError("No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                        Session::set('__msjForm', $msj);
                    }
                }else{
                    Session::set('__msjForm',Mensajes::mensajeError("No se han asignado perfiles"));
                }
            }
            $this->data['form'] =$form->armarFormulario();
        }else{
            Session::set('__msjVista',Mensajes::mensajeError("Debe seleccionar un usuario"));
            Session::set('__idVista','usuarios');
            redireccionar($this->url);  
        }
        
    }//fin función
	
	
	 
	function cierresesion($url=""){
	    if(empty($url)){
	    	 $url='/jadmin/';
		}
	    if(Session::destroy()){
	       redireccionar($url);    
	    }
        
	}
}//fin metodo
?>