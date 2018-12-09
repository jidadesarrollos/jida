<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 19/11/2018
 * Time: 10:41
 */

namespace Jida\Jadmin\Controllers;

use App\Config\Configuracion;
use Jida\Configuracion\Config;
use Jida\Core\Controlador;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;
use Jida\Medios\Sesion;
use Jida\Render\Menu;

class JControl extends Controlador {

    protected $_perfiles = ['jadmin', 'admin'];
    protected $_usuario;

    function __construct() {

        parent::__construct();
        $this->_usuario = Sesion::$usuario;
        $this->_inicializar();
        $esJadmin = $this->_usuario->permisos->es($this->_perfiles);

        if (Estructura::$metodo !== 'login' and !$esJadmin) {
            $this->redireccionar('/jadmin/login');

        }

    }

    private function _inicializar() {

        $this->data('nombreApp', "Jida");
        $this->layout('jadmin');

        $config = Config::obtener();
        $nombreApp = Configuracion::NOMBRE_APP;
        $urlBase = '//' . Estructura::$urlBase;
        $urlTema = $urlBase . $config::PATH_JIDA . '/Jadmin/Layout/' . $config->temaJadmin . "/";

        $menu = new Menu('Jadmin');

        $this->data([
            'menu'      => $menu->render(),
            'nombreApp' => $nombreApp,
            'urlBase'   => $urlBase,
            'urlTema'   => $urlTema
        ]);

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

    function phpInfo() {

        echo phpinfo();
        exit;

    }

}