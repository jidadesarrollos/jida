<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;
use Jida\Modelos\JidaControl as JidaControl;
use Jida\Helpers as Helpers;
use Jida\RenderHTML\Formulario as Formulario;
use Jida\Modelos\User as User;
use Jida\RenderHTML\Vista as Vista;
use Exception;
class JadminController extends JController{
    /**
     * objeto modelo jidaControl
     * @access private
     * @var object $jctrl
     */
    private $jctrl;


    function __construct(){
        parent::__construct();
        $this->url = "/jadmin/";
        $this->jctrl = new JidaControl();
    }
    function index(){
        $this->layout('jadminIntro');
		$this->tituloPagina = "Jida Framework - Formularios";

        $jctrl= new JidaControl();
        if(Helpers\Sesion::checkLogg()){
            $a=0;
        }
        if($jctrl->testBD()){

            if(Helpers\Sesion::checkPerfilAcceso('jidaAdministrador')){

                redireccionar("/jadmin/forms/");
            }else{

                $form =  new Formulario('Login',null,null,2);
                $form->valueSubmit="Iniciar Sesi&oacute;n";
                $form->nombreSubmit="btnJidaAdminLogin";
                $form->action=$this->url;
                $form->valueBotonForm="Iniciar Session";
                if($this->post("btnJidaAdminLogin")){
                    $validacion = $form->validarFormulario($_POST);
                    if($validacion===TRUE){
                        $User = new User();
                        $clave = $this->post('clave_usuario');
                        $checkUser = $User->validarLogin($this->post('nombre_usuario'),$clave);
                        if($checkUser===FALSE){
                           Formulario::msj('error',"Usuario o clave invalidos");
                        }else{
                            /*** Habilitar datos de usuario*/
                            $perfiles = $User->getPerfiles();
                            Helpers\Sesion::sessionLogin();
                            Helpers\Sesion::set('usuario',$checkUser);
                            Helpers\Sesion::set('Usuario',$User);
                            Helpers\Sesion::set('usuario','perfiles',$perfiles);

                            /*fin variables de usuario*/
                            Vista::msj('formularios', 'info', 'Bienvenido '.$User->nombre_usuario,'/jadmin/forms/');
                            $this->redireccionar('/jadmin/forms/');
                        }


                    }else{
                        Helpers\Sesion::set('__msjForm', Helpers\Mensajes::mensajeError("Datos invalidos"));

                    }
                }
            }//fin validación post


            $this->dv->formLoggin = $form->armarFormulario();

        }else{
		
            $this->dv->testBD= "La conexión a base de datos no ha podido establecerse";
        }
        $tablasBD = $jctrl->obtenerTablasBD();



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

	function tstforms(){
		$form = new Jida\Form('GestionUsuarios');
		$form->boton('borrar','Borrar');
		$form->titulo('Formulario de prueba');
		//$form->generarInputs(['nombre','apellido','cedula']);


		$this->data('form',$form);

	}

}
