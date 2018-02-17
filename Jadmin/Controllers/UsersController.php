<?PHP

namespace Jida\Jadmin\Controllers;

use Jida\Helpers as Helpers;
use Jida\Render as Render;
use Jida\Modelos as Modelos;
use Jida\Core\UsuarioManager as UsuarioManager;

class UsersController extends JController {

    use UsuarioManager;

    protected $urlCierreSession = "/jadmin/";

    var $layout = 'jadmin.tpl.php';

    var $manejoParams = TRUE;

    function __construct() {

        parent::__construct();

        if (defined('MODELO_USUARIO') and class_exists(MODELO_USUARIO)) {
            $clase = MODELO_USUARIO;
            $this->modelo = new $clase();
        }

        $this->url = '/jadmin/users/';
    }

    function cambioClave() {

        $this->dv->usarPlantilla('form');
        $form = new Render\Formulario('jida/CambioClave');

        $form->titulo("Cambio de Clave");
        $form->boton('principal', 'Cambiar Clave');
        $user = Helpers\Sesion::obt('Usuario');

        if ($this->post('btnCambioClave')) {

            $claveActual = md5($this->post('clave_actual'));

            if ($claveActual == $user->clave_usuario) {
                if ($this->post('nueva_clave') == $this->post('confirmacion_clave')) {

                    $user->clave_usuario = md5($this->post('nueva_clave'));

                    if ($user->salvar()) {

                        $msj = Helpers\Mensajes::crear('suceso', 'La clave se ha cambiado exitosamente');
                        Helpers\Sesion::editar('__msj', $msj);
                        Helpers\Sesion::editar('Usuario', $user);

                        if (defined('DEFAULT_JADMIN')) {
                            $this->redireccionar(DEFAULT_JADMIN);
                        } else {
                            $msj = Helpers\Mensajes::crear('suceso', 'La clave se ha cambiado exitosamente');
                            Helpers\Sesion::editar('__msj', $msj);

                            $this->redireccionar($this->obtUrl('jadmin.index'));
                        }

                    }

                } else {
                    $form::msj('error', 'Las claves ingresadas no coinciden');
                }

            } else {
                $form::msj('error', 'La clave ingresada no coincide con su clave actual');
            }

        }

        $this->data([
            'form' => $form->render()
        ]);
    }

    function index() {

        $vista = $this->vistaUser();
        $this->vista = "vistaUsuarios";
        $this->dv->vista = $vista->obtenerVista();

    }

    function setUsuario($idUser = '') {
        $this->_setUsuario($idUser);
    }

    function asociarPerfiles($idUser = '') {
        $this->_asociarPerfiles($idUser);
    }

    function cierresesion($url = '') {
        $this->_cierresesion($url);
    }

    function eliminarUsuario($idUser = '') {

        if ($this->_eliminarUsuario($idUser)) {
            $tipo = 'suceso';
            $msj = 'Usuario eliminado exitosamente';
        } else {
            $tipo = 'error';
            $msj = 'El usuario no ha podido ser eliminado, por favor intente de nuevo';
        }

        Render\JVista::msj('usuarios', $tipo, $msj, $this->obtUrl('index'));
    }
}
