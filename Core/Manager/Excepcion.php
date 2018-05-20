
<?PHP
/**
 * Arranque de errores generales
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package Framework
 * @category controlador
 * @version 0.1 02/01/2014
 * @since 0.4
 */
namespace Jida\Core;
class ExcepcionController extends Controller{


    /**
     * @var object $excepcion Objeto con excepción capturada
     */
    var $excepcion;

    protected $layoutExcepcion="error.tpl.php";
	/**
	 * Define el modulo css a incrustar en la plantilla de error
	 * @var $moduloCss
	 * @since 1.4
	 */
	protected $moduloCss ="";
	/**
	 * Define el modulo JS a incrustar en la plantilla de error
	 * @var $moduloJS
	 * @since 1.4
	 */
	protected $moduloJS="";
	protected $libreriasCss;

    function __construct(JExcepcion $e){
        parent::__construct();
        $this->excepcion = $e;
		$this->dv->moduloCss = $this->moduloCss;


		$this->dv->moduloJS = $this->moduloJS;
        if($this->solicitudAjax()) {

            $this->layoutExcepcion = $this->layout = 'ajax.tpl.php';

        }

    }
    /**
	 * Retorna el layout a utilizar para las excepciones
	 * @method layout
	 */
	function obtLayout(){
		return $this->layoutExcepcion;
	}


}