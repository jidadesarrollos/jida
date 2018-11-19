<?PHP
/**
 * Definición de la clase
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package
 * @category Controller
 * @version 0.1
 */

namespace Jida\Jadmin\Controllers;

use Jida\Configuracion\Config;
use Jida\Render as Render;
use Jida\Modelos as Modelos;

use Jida\Componentes\InstagramManager as InstagramManager;

class Instagram extends JController {

    var $layout = "jadmin.tpl.php";
    var $manejoParams = true;

    function __construct () {

        parent::__construct();

        $configuracion = Config::obtener();
        $this->data(['nombreApp' => $configuracion::NOMBRE_APP]);

    }

    function index () {

        if ($this->post('btnPermisosInstagram')) {
            $ig = new InstagramManager();
            $ig->autenticar();
        }

        $this->data(['urlForm' => $this->obtUrl('index')]);
    }

    /**
     * Metodo donde se redirecciona para establecer la conexion con la API
     * y generar el Access Token a utilizar en las consultas
     */
    function permisos () {

        if ($this->get('code')) {

            $this->data([
                            'urlForm' => $this->obtUrl('permisos'),
                            'codigo'  => $this->get('code')
                        ]);

        }
        else {
            if ($this->post('btnPermisosInstagram')) {

                $ig = new InstagramManager();
                $infoIG = $ig->solicitarAccessToken($this->post('codigo'));

                $redSocial = new Modelos\RedSocial();
                $redSocial->obtenerBy('instagram', 'identificador');

                $redSocial->access_token = $infoIG['access_token'];
                $redSocial->data = json_encode($infoIG['user']);

                $redSocial->salvar();

                Render\Formulario::msj('suceso',
                                       "Se han guardado los datos de Autenticación con Instagram exitosamente");
                $this->redireccionar($this->obtUrl('index'));

            }
            else {
                $this->_404();
            }
        }
    }

}
