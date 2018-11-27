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

        $user = Sesion::$usuario;

        if (!$user->permisos->es('jadmin')) {
            $this->redireccionar('login');
        }

    }

    function index() {

    }

    public function login() {

        $formLogin = new Formulario('jida/Login');
        $formLogin->boton('principal', 'Iniciar sesión');

        if ($this->post('btnLogin')) {

            if ($formLogin->validar()) {

                $usuario = new Usuario();
                $usuario->validarSesion($this->post('nombre_usuario'), $this->post('clave_usuario'));

            }
        }

        $this->data([
            'formulario' => $formLogin->render()
        ]);

    }

}
