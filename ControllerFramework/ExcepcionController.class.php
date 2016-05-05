
<?PHP 
/**
 * Controlador de errores generales
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category controlador
 * @version 0.1 02/01/2014
 */
class ExcepcionController extends Controller{

    
    /**
     * @var object $excepcion Objeto con excepción capturada
     */
    var $excepcion;
	
    private $layoutExcepcion="error.tpl.php";
    
    
    function __construct(JExcepcion $e){
        parent::__construct();
        $this->excepcion = $e;
        if($this->solicitudAjax()) {
            
            $this->layoutExcepcion = $this->layout = 'ajax.tpl.php';
        }
        
    }
    /**
	 * Retorna el layout a utilizar para las excepciones
	 * @method layout
	 */
	function layout(){
		return $this->layoutExcepcion;
	}

    
}