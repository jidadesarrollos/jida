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
	function process(){
	    $pk="";$tipoForm=1;
        if(isset($_GET['id']) and $this->getEntero($_GET['id'])){
            $pk=$_GET['id'];$tipoForm=2;
        }
        
        $form=new Formulario('Perfiles',$tipoForm,$pk,2);
        $this->data['form']=$form->armarFormulario();
	}//final funcion
	
}


?>