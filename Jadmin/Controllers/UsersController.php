<?PHP 
namespace Jida\Jadmin\Controllers;
use Jida\Helpers as Helpers;
use Jida\RenderHTML as RenderHTML;
class UsersController extends JController{
    protected $urlCierreSession="/jadmin/";
    var $layout = 'jadmin.tpl.php';    
	function __construct(){
        parent::__construct();
        //$this->modelo = new User();

        $this->url='/jadmin/users/';
    }
    
	function index(){
	    
		$vista = $this->vistaUser();
		if(defined('MODELO_USUARIO') and class_exists(MODELO_USUARIO)){
			$clase = MODELO_USUARIO;
			$this->modelo = new $clase();
		}
        $this->vista="vistaUsuarios";
		$this->dv->vista = $vista->obtenerVista();
			
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
        
        $vista = new RenderHTML\Vista($query,$GLOBALS['configPaginador'],"Usuarios");
        $vista->tipoControl=2;
        $vista->setParametrosVista($GLOBALS['configVista']);
        $vista->filaOpciones=[  0=>['a'=>[
                        'atributos'=>[ 'class'=>'btn',
                                            'title'=>'Asignar perfiles de acceso',
                                            'href'=>"/jadmin/users/asociar-perfiles/usuario/{clave}"
                                            ],
                        'html'=>['span'=>['atributos'=>['class' =>'glyphicon glyphicon-edit']]]]]];
        $vista->acciones=
        ['Registrar'=>
            ['href'=>$url.'/set-usuario'],
       'Modificar'=>
            ['href'=>$url.'/set-usuario','data-jvista'=>'seleccion','data-jkey'=>'u'],
                '<span class="fa fa-trash-o"></span>'=>
                ['href'=>$url.'/eliminar-usuario',
                'data-jvista'=>'seleccion',
                'data-multiple'=>'true',
                'data-jkey'=>'u']                                
                ];
        return $vista;
    }

	/**
     * Muestra formulario de gestión de usuarios
     * 
     * @internal El metodo puede ser configurado en controladores que hereden de UsersControllers por
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
	    if($this->getEntero($this->get('u')))
	       $id = $this->get('u');
        
	    $datosForm =  $this->formGestionUser($id,$url,$externo);
        $form=& $datosForm['form'];
        $form->tituloFormulario="Gesti&oacute;n de Usuarios";
        if($this->post('btnRegistroUsuarios')):
            $_POST['clave_usuario']=md5($this->post('clave_usuario'));
            if($datosForm['guardado'] and $datosForm['guardado']['ejecutado']==1){
                $msj = 'El usuario '.$this->post('nombre_usuario')." ha sido creado exitosamente";
                RenderHTML\Vista::msj($idVista, 'suceso', $msj,$urlVista);
            }else{
                Helpers\Sesion::set('__msjForm',Mensajes::crear('error',"No se ha podido registrar el usuario, vuelva a intentarlo"),false);
            }
        endif;
        $this->data['form'] = $form->armarFormulario();
		$this->dv->form = $form->armarFormulario();
	}
    /**
     * Devuelve el formulario para gestion de usuarios
     * 
     * @internal Devuelve el html del usuario configurado con el action del form hacia un metodo 'set-componente' del
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
        if($this->post('btnRegistroUsuarios')):
            $validacion  = $form->validarFormulario();
            
            if($validacion===TRUE){
                $user = new User();
                $user->validacion=1;
                $_POST['clave_usuario'] = md5($this->post('clave_usuario'));
                if($user->salvar($_POST)->ejecutado()){
                    $user->asociarPerfiles($_POST['id_perfil']);
                }
                $retorno['guardado']=['ejecutado'=>$user->getResult()->ejecutado()];
            }else{
                $retorno['guardado'] =$validacion; 
            }
            
        endif;
        $retorno['form']=$form;
        return $retorno;
    }
	    /**
     * Devuelve el formulario para gestion de usuarios
     * 
     * @internal Devuelve el html del usuario configurado con el action del form hacia un metodo 'asociar-perfiles' del
     * controlador en el cual sea llamado
     * @method formAsignacionPerfiles
     * @param int $tipoform
     * @param $campoUpdate Id del usuario al que se asignaran los perfiles
     */
    protected function formAsignacionPerfiles($campoUpdate="",$perfiles=""){
        
        $tipoForm=(!empty($campoUpdate))?2:1;
        $form = new Formulario('PerfilesAUsuario',$tipoForm,$campoUpdate,2);
        $form->valueBotonForm='Asignar Perfiles';
        $form->action=$this->urlController().'asociar-perfiles';
        
        if(!empty($perfiles) and is_array($perfiles)){
            $form->externo['id_perfil']="select id_perfil,perfil from s_perfiles where id_perfil in (".implode(",", $perfiles).") order by perfil";    
        }else{
            $form->externo['id_perfil']="select id_perfil,perfil from s_perfiles where id_perfil order by id_perfil";
        }
        $retorno=array('form'=>'');
        $retorno['form']=$form;
        return $retorno;
    }
    /**
     * Realiza el proceso de registro de usuarios
     * 
     * @internal La data a registrar debe haber sido validada previamente
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
        if($this->getEntero($this->get('usuario'))){
            $form = new Formulario('PerfilesAUsuario',2,$this->get('usuario'),2);
            $user = new User($this->getEntero($this->get('usuario')));
            $form->action=$this->url."asociar-perfiles";
            $form->valueSubmit="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar perfiles al usuario $user->nombre_usuario";
            
            if($this->post('btnPerfilesAUsuario')){
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    $accion = $user->asociarPerfiles($this->post('id_perfil'));
                    if($accion['ejecutado']==1){
                        RenderHTML\Vista::msj('componentes', 'suceso','Asignados los perfiles al usuario '.$user->nombre_usuario,$this->urlController());
                        #redireccionar($this->url);
                    }else{
                        Formulario::msj('error',"No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                    }
                }else{
                    Formulario::msj('error',"No se han asignado perfiles");
                }
            }
            $this->data['form'] =$form->armarFormulario();
			$this->dv->form = $form->armarFormulario();
        }else{
            RenderHTML\Vista::msj('usuarios', 'error',"Debe seleccionar un usuario",$this->urlController());
            
            redireccionar($this->url);  
        }
        
    }//fin función
    function cierresesion($url=""){
	    if(Helpers\Sesion::destroy()){
	    	if(Helpers\Sesion::get('Usuario') instanceof MODELO_USUARIO)
				Helpers\Sesion::get('Usuario')->cerrarSesion();
			if(empty($url)) $url = $this->urlCierreSession;
	    	$this->redireccionar($url);  
	    }
	}
    /**
     * Verifica los datos para iniciar sesion
     * 
     * @internal Verifica los datos del usuario y si el mismo existe registra la sesion y lo habilita
     * caso contrario retorna falso
     * @method validarInicioSesion
     */
    protected function validarInicioSesion($usuario,$clave){
        $data = $this->modelo->validarLogin($usuario, $clave);
        
        if($data){
            $this->crearSesionUsuario();
            return true;
        }else 
            return false;
    }//fin metodo
    
    /**
     * Registra la sesion de un usuario
     * 
     * @internal Crea la variable de Sesion Usuario con el usuario en sesión actual
     * @method crearSesionUsuario
     */
    protected function crearSesionUsuario(){
        Helpers\Sesion::sessionLogin();
        Helpers\Sesion::set('Usuario',$this->modelo);
        //Se guarda como arreglo para mantener soporte a aplicaciones anteriores
        if(isset($data))
        Helpers\Sesion::set('usuario',$data);
        return $this;
    }
    /**
     * Retorna un Objeto Formulario para Formulario Login
     * 
     * @method obtenerFormulariologin
     * @return object $form
     * @see Formulario
     */
    function formularioLogin($called=FALSE){
        if($called){
            if(Helpers\Sesion::get('FormLoggin') and Helpers\Sesion::get('FormLoggin') instanceof Formulario){
                $form = Helpers\Sesion::get('FormLoggin');
                 
            }else{
                $form = new Formulario('Login',1,null,2);
                $form->tituloFormulario = "Iniciar Sesi&oacute;n";
                $form->valueBotonForm="Iniciar Sesi&oacute;n";
            }        
            
            return $form;
        }else{
            $this->_404();
        }
    }
    
    
    /**
     * Devuelve un formulario para modificar la contrasenia de un usuario
     * @method formCambioContrasenia
     * @return object $form Objeto Tipo Formulario
     * @see Formulario
     */
    protected function formCambioContrasenia(){
        
        $form = new Formulario('CambioClave',1,null,2);
        
        return $form;
        
    }
    /**
     * Crea una clave aleatoria
     * @method generarContrasenia
     * @param int $length Tamaño de la cadena, por defecto 30 
     */
    protected function generarContrasenia($length = 30) {
       $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
       $string = substr( str_shuffle( $chars ), 0, $length );
       return $string;
    }
}