<?PHP
 
/**
 * Clase Controladora del administrador del Framework
 * 
 * 
 */
class JadminController extends Controller{
    /**
     * objeto modelo jidaControl
     * @access private
     * @var object $jctrl
     */
    private $jctrl;
    
    
    function __construct(){
        
        $this->jctrl = new JidaControl();
        $this->layout="jadmin.tpl.php";
        $this->url = "/jadmin/";
        
    }
    function index(){
        $this->layout="unaColumna.tpl.php";
		$this->tituloPagina = "Jida Framework - Formularios";        
        $jctrl= new JidaControl();
        if(Session::checkLogg()){
            $a=0;
        }
        if($jctrl->testBD()){
            
            if(Session::checkPerfilAcceso('jidaAdministrador')){
                redireccionar("/jadmin/forms/");
            }else{
                
                $form =  new Formulario('Login',null,null,2);
                $form->valueSubmit="Iniciar Sesi&oacute;n";
                $form->nombreSubmit="btnJidaAdminLogin";
                $form->action=$this->url;
                $form->valueBotonForm="Iniciar Session";
                if(isset($_POST["btnJidaAdminLogin"])){
                    $validacion = $form->validarFormulario($_POST);
                    if($validacion===TRUE){
                        $User = new User();
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
                        
                          
                    }else{
                        Session::set('__msjForm', Mensajes::mensajeError("Datos invalidos"));
                        
                    }
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
    
    
    function json(){
        if(isset($_GET['file'])){
            if(file_exists(framework_dir.'json/validaciones.json')){
                $data = file_get_contents(framework_dir.'json/validaciones.json');
                respuestaAjax($data);    
            }else{
                throw new Exception("No se consigue el archivo solicidado o no existe", 1);
                
            }
        }else{
            throw new Exception("Pagina no encontrada", 404);
            
        }   
    }
    function phpInfo(){
        echo phpinfo();
        exit;

    } 
    
    function testPost(){
        echo "<h3>Testing post</h3>";
        if(isset($_POST['prueba'])){
            Arrays::mostrarArray($_POST);
        }
        exit;
    }
    
    function testingCurl(){
             
        $curl = new Curl('http://dev.electron.local/jadmin/test-post');
        echo $curl->post(array('prueba'=>'vemos algo','otra'=>'algo distinto','dime'=>'algo más'));
        exit;   
    }
    
    function testingFiles(){
        $cadena="hola mundo";
        $cadena1="\t\t\t\t\thola mundo\t\t\t";
        echo strspn($cadena1, "\t")."<hr>";
        echo strlen($cadena)."<hr>";
        echo strlen($cadena1)."<hr>";exit;
        Arrays::mostrarArray(file(framework_dir.'Jadmin/Controllers/JadminController.class.php'));exit;
        $file = new PHPFile(app_dir.'Controller/algo/','test.php');
        if($file->crear()){
            echo "aki se creo<hr>";    
        }else{
            echo "no se creo<hr>";
        }
        exit;
    }   
}
?>