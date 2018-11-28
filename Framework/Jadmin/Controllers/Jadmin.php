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

            if ($formLogin->validar()) {

                $usuario = new Usuario();

                if ($usuario->validarInicioSesion($this->post('nombre_usuario'), $this->post('clave_usuario'))) {
                    $this->redireccionar('/');
                }
                else {
                    Formulario::msj('error', 'Datos incorrectos');
                }

            }

        }

        $this->data([
            'formulario' => $formLogin->render()
        ]);

        return $this;

    }

}
