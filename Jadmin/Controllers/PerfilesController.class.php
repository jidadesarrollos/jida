<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version 0.1
 */

 
class PerfilesController extends Controller{
    
	/**
	 * Funcion constructora
	 */
    function __construct($id=""){
        parent::__construct();
		$this->url="/jadmin/perfiles/";
        $this->layout="jadmin.tpl.php";
        
    }
	
	function index(){
		$this->tituloPagina = "Lista de Perfiles";
		$this->vista="vistaPerfiles";
		$qVista = "select id_perfil,nombre_perfil  \"Perfil\" from s_perfiles";
		$vista = new Vista($qVista,$GLOBALS['configPaginador'],'Perfiles');
		$vista->setParametrosVista($GLOBALS['configVista']);
		
		$vista->acciones=array(
                                'Registrar'=>array('href'=>$this->url.'set-perfiles'),
                                'Modificar'=>array('href'=>$this->url.'set-perfiles',
                                                                'data-jvista'=>'seleccion',
                                                                'data-multiple'=>'true','data-jkey'=>'perfil'),
                                );
		$this->data['vistaPerfiles'] = $vista->obtenerVista();
	}
	
	/**
     * Procesar un perfil
     * @method process
     */
	function setPerfiles(){
	    
	    $pk="";$tipoForm=1;
        if(isset($_GET['id']) and $this->getEntero($_GET['id'])){
            $pk=$_GET['id'];$tipoForm=2;
        }
        
        $form=new Formulario('Perfiles',$tipoForm,$pk,2);
        $form->action=$this->url."set-perfiles/";
        $form->tituloFormulario="Gesti&oacute;n de Perfiles";
        if(isset($_POST['btnPerfiles'])){
            $msj = 'No se ha podido registrar el perfil, vuelva a intenarlo';
            $validacion = $form->validarFormulario();
            if($validacion===TRUE){
                $perfil = New Perfil($pk);
                $_POST['clave_perfil'] = String::upperCamelCase($_POST['nombre_perfil']);
                $guardado = $perfil->salvar($_POST);
                if($guardado['ejecutado']){
                    $msj = "El perfil <strong>$perfil->nombre_perfil</strong> ha sido registrado exitosamente";
                    Vista::msj('perfiles', 'suceso', $msj,'/jadmin/perfiles/');
                }else{
                    if($guardado['unico']==1){
                        $msj = "El perfil <strong>$_POST[nombre_perfil]</strong> ya se encuentra registrado";
                    }
                }
            }
            Formulario::msj('error', $msj);            
        }
        $this->data['form']=$form->armarFormulario();
	}//final funcion
	
}


?>