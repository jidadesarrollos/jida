<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

use Jida\Medios\Sesion;
use Jida\Modulos\Usuario\Usuario;
use Jida\Render\Formulario;

class Jadmin extends JControl {

    function __construct() {

        parent::__construct();

    }

    function index() {

        $user = Sesion::$usuario;

        $this->redireccionar('http://localhost/jida/jadmin');

        if (!$user->permisos->es('jadmin')) {
            $this->_login();
        }

    }

    private function _login() {

        $this->layout('login');
        $this->vista('login');

        $formLogin = new Formulario('jida/Login');
        $formLogin->boton('principal', 'Iniciar sesión');

        if ($this->post('btnLogin')) {

            $usuario = $this->post('nombre_usuario');
            $clave = $this->post('clave_usuario');

            if ($formLogin->validar() and Usuario::inciarSesion($usuario, $clave)) {
                $this->redireccionar('/jadmin');
            }

            Formulario::msj('error', 'Datos incorrectos');

        }

        $this->data([
            'formulario' => $formLogin->render()
        ]);

        return $this;

    }

}
