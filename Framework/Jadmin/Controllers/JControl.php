<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 19/11/2018
 * Time: 10:41
 */

namespace Jida\Jadmin\Controllers;

use App\Config\Configuracion;
use Jida\Core\Controlador;
use Jida\Medios\Sesion;
use Jida\Render\Formulario;
use Jida\Render\Menu;
use Jida\Modulos\Usuario\Usuario;

class JControl extends Controlador {

    protected $_perfiles = ['jadmin'];

    function __construct() {

        parent::__construct();

        $this->data('nombreApp', "Jida");
        $this->layout('jadmin');
        $urlBase = Configuracion::URL_BASE;
        $nombreApp = Configuracion::NOMBRE_APP;

        $usuario = Sesion::$usuario;

        if (!$usuario->permisos->es($this->_perfiles)) {
            $this->_login();
        }

        $menu = new Menu('Jadmin');

        $this->data([
            'menu'      => $menu->render(),
            'urlBase'   => $urlBase,
            'nombreApp' => $nombreApp
        ]);

    }

    function phpInfo() {

        echo phpinfo();
        exit;

    }

    protected function _login() {

        $this->layout('login');
        $this->vista('login');

        $formLogin = new Formulario('jida/Login');
        $formLogin->boton('principal', 'Iniciar sesión');

        if ($this->post('btnLogin')) {

            $usuario = $this->post('nombre_usuario');
            $clave = $this->post('clave_usuario');

            if ($formLogin->validar() and Usuario::iniciarSesion($usuario, $clave)) {
                $this->redireccionar('jadmin');
            }

            Formulario::msj('error', 'Datos incorrectos');

        }

        $this->data([
            'formulario' => $formLogin->render()
        ]);

        return $this;

    }

}