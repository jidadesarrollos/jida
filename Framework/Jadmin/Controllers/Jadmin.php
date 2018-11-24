<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

use Jida\Medios\Sesion;
use Jida\Modelos\Usuario;
use Jida\Render\Formulario;

class Jadmin extends JControl {

    function __construct() {

        parent::__construct();

    }

    function index() {

        $user = Sesion::$usuario;

        if (!$user->permisos->es('jadmin')) {
            return $this->_inicioSesion();
        }

    }

    private function _inicioSesion() {

        $this->layout('login');
        $this->vista('login');

    }

}
