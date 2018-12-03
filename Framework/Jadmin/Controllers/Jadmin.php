<?PHP

/**
 * Clase Controladora del administrador del Framework
 *
 *
 */

namespace Jida\Jadmin\Controllers;

use Jida\Medios\Sesion;
use Jida\Render\Formulario;
use Jida\Modulos\Usuario\Usuario;

class Jadmin extends JControl {

    protected $_perfiles = ['jadmin', 'admin'];
    protected $_usuario;

    public function __construct() {
        parent::__construct();

        $this->_usuario = Sesion::$usuario;

    }

    public function index() {

        if (!$this->_usuario->permisos->es($this->_perfiles)) {
            $this->redireccionar('jadmin/login');
        }

    }

    public function login() {

        $this->layout('login');

        $formLogin = new Formulario('jida/Login');
        $formLogin->boton('principal', 'Iniciar sesión');

        if ($this->post('btnLogin')) {

            $usuario = $this->post('usuario');
            $clave = $this->post('clave');

            if ($formLogin->validar() and Usuario::iniciarSesion($usuario, $clave)) {
                $this->redireccionar('jadmin');
            }

            Formulario::msj('error', 'Datos incorrectos');

        }

        $this->data([
            'formulario' => $formLogin->render()
        ]);

    }

    public function logout() {

        Sesion::destruir();
        $this->redireccionar('jadmin');

    }

}
