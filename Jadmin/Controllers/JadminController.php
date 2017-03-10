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
        
        if(defined('DEFAULT_JADMIN'))
            $this->redireccionar(DEFAULT_JADMIN);
        
        //$jctrl= new JidaControl();
        // if($this->validarSesion())
			// $this->redireccionar($this->obtUrl('forms'));
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


}
