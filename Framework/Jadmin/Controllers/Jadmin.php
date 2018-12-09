<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

class Jadmin extends JControl {

    public function index() {

        if (!$this->_usuario->permisos->es($this->_perfiles)) {
            $this->redireccionar('jadmin/login');
        }

    }

}
