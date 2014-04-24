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
        $this->header='jadminDefault/header.php';
        $this->footer='jadminDefault/footer.php';
		$this->url="/jadmin/perfiles/";
        
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
	
	
	
	
}


?>