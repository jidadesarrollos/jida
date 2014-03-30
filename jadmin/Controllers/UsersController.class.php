<?PHP 


class UsersController extends Controller{
	
	
	  function __construct(){
        $this->url='/jadmin/users/';
        $this->header="jadminDefault/header.php";
        $this->footer="jadminDefault/footer.php";
    }
    
	function index(){
		$query = "select id_usuario,nombre_usuario \"Nombre Usuario\", fecha_creacion \"Fecha Creaci&oacute;n\",
					activo \"Activo\",ultima_session \"&Uacute;ltima Sesi&oacute;n\",  b.estatus
				 from s_usuarios a 
				 join s_estatus b on (a.id_estatus=b.id_estatus)";
		
		$vista = new Vista($query,$GLOBALS['configPaginador'],"Usuarios");
		$vista->setParametrosVista($GLOBALS['configVista']);
		$vista->filaOpciones=array(  0=>array('a'=>array(
                                                            'atributos'=>array( 'class'=>'btn',
                                                                                'title'=>'Asignar perfiles de acceso',
                                                                                'href'=>"/jadmin/users/asociar-perfiles/usuario/{clave}"
                                                                                ),
                                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-edit'))))))
                                                ;
		$this->data['vista'] = $vista->obtenerVista();
			
	}
	
	function registrarUsuario(){
		
	}
	
	 function asociarPerfiles(){
        try{
            if(isset($_GET['usuario']) and $this->getEntero($_GET['usuario'])!=""){
                            
                
                $form = new Formulario('PerfilesAUsuario',2,Globals::obtGet('usuario'));
                $user = new UsuarioAplicacion($this->getEntero(Globals::obtGet('usuario')));
                
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
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
    }
	
	
}
?>