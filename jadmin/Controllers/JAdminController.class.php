<?PHP
 
/**
 * Clase Controladora del administrador del Framework
 * 
 * 
 */
class JAdminController extends controller{
    /**
     * objeto modelo jidaControl
     * @access private
     * @var object $jctrl
     */
    private $jctrl;
    
    
    function __construct(){
        
        $this->jctrl = new JidaControl();
        
        $this->footer="login/FLogin.php";
        $this->header="login/HLogin.php";
        $this->url = "/jadmin/";
        
    }
    function index(){
		$this->tituloPagina = "Jida Framework - Formularios";        
        $jctrl= new JidaControl();
        if($jctrl->testBD()){
            $dataArray['testBD'] = "Conexión establecida";
            
            $form =  new Formulario('Login');
            $form->valueSubmit="Iniciar Sesi&oacute;n";
            $form->nombreSubmit="btnJidaAdminLogin";
            $form->action=$this->url;
            if(isset($_POST["btnJidaAdminLogin"])){
                
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    
                    $User = new UsuarioAplicacion();
                    $clave = md5(Globals::obtPost('clave_usuario'));
                    $checkUser = $User->validarLogin(Globals::obtPost('nombre_usuario'),$clave);
                    if($checkUser===FALSE){
                        Session::set('__msjForm',Mensajes::mensajeError("Usuario o clave invalidos"));
                    }else{
                        /*** Habilitar datos de usuario*/
                        $perfiles = $User->getPerfiles();
                        Session::sessionLogin();
                        Session::set('usuario',$checkUser);
                        Session::set('usuario','perfiles',$perfiles);
                        
                        /*fin variables de usuario*/
                        Session::set('__msjVista',Mensajes::mensajeInformativo('Bienvenido '.$User->nombre_usuario));
                        Session::set('__idVista','formularios');
                        redireccionar('/jadmin/forms/');
                    }
                    Arrays::mostrarArray($checkUser);
                      
                }else{
                    Session::set('__msjForm', Mensajes::mensajeError("Datos invalidos"));
                    Arrays::mostrarArray($validacion);
                }
            }//fin validación post
            
            
            $dataArray['formLoggin'] = $form->armarFormulario();
        
        }else{
            $dataArray['testBD'] = "La conexión a base de datos no ha podido establecerse";
        }
        $tablasBD = $jctrl->obtenerTablasBD();
        $this->data = $dataArray;
        
    }

    private function crearTablasJida(){
        
        
        $jctrl = new JidaControl();
        if($jctrl->crearTablasBD()){
            
            $_SESSION['__msj'] = Mensajes::mensajeSuceso("Tablas Creadas");
            echo "<h3>Ejecutando script... espere un momento...</h3>";
            sleep(3);
            redireccionar('/jadmin/');
            
        }else{
            $_SESSION['__msj'] = Mensajes::mensajeError("Error");
            
        }
    }
  

   
}
?>