<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

use Jida\Configuracion\Config;
use Jida\Modelos\JidaControl as JidaControl;
use Jida\Helpers as Helpers;
use Jida\RenderHTML\Formulario as Formulario;
use Jida\Modelos\User as User;
use Jida\RenderHTML\Vista as Vista;
use Exception;

class Jadmin extends JController {

    /**
     * objeto modelo jidaControl
     *
     * @access private
     * @var object $jctrl
     */
    private $jctrl;

    function __construct () {

        parent::__construct();

        $this->url = "/jadmin/";
        $this->jctrl = new JidaControl();

        $estructura = Config::obtener();
        $this->data([
                        'nombreApp' => $estructura::NOMBRE_APP
                    ]);
    }

    function index () {

        if (defined('DEFAULT_JADMIN')) {
            $this->redireccionar(DEFAULT_JADMIN);
        }

    }

    function dashboard () {

    }

    function json () {

        if (isset($_GET['file'])) {

            if (file_exists(DIR_FRAMEWORK .  DS . 'json/validaciones.json')) {
                $data = file_get_contents(DIR_FRAMEWORK . DS . 'json/validaciones.json');
                respuestaAjax($data);
            }
            else {
                throw new Exception("No se consigue el archivo solicidado o no existe", 1);
            }

        }
        else {
            throw new Exception("Pagina no encontrada", 404);
        }
    }

    function phpInfo () {

        echo phpinfo();
        exit;

    }

}
