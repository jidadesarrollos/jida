<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

use Jida\Medios\Sesion;

class Jadmin extends JControl {

    function __construct() {

        parent::__construct();

    }

    function index() {

        $user = Sesion::$user;

        if (!$user->permisos->es('jadmin')) {
            return $this->_inicioSesion();
        }

    }

    private function _inicioSesion() {
        $this->vista('login');
        $this->data('formulario', 'formulario de inicio de sesion');
    }

}
