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
use Jida\Modulos\Usuarios\Modelos\Usuario;
use Jida\Render\Formulario;
use Jida\Render\Menu;
use Jida\Render\Selector;

class JControl extends Controlador {

    protected $_perfiles = ['jadmin', 'administrador', 'admin'];
    protected $_usuario;

    function __construct() {

        parent::__construct();

        $this->_usuario = Sesion::$usuario;
        $ruta = strtolower("/" . Estructura::$modulo . '/' . Estructura::$metodo);

        if (!(strtolower($ruta) == "/usuario/login") and !Sesion::es($this->_perfiles)) {
            $this->redireccionar("/jadmin/usuario/login");
        }

        $this->_inicializar();
    }

    private function _inicializar() {

        $this->data('nombreApp', "Jida");
        $layout = ($this->solicitudAjax()) ? 'ajax' : 'jadmin';
        $this->layout($layout);

        $config = Config::obtener();
        $nombreApp = Configuracion::NOMBRE_APP;
        $urlBase = Estructura::$urlBase;
        $urlTema = Estructura::$urlJida . '/Jadmin/Layout/' . $config->temaJadmin . "/";

        $menu = new Menu('/jadmin/menu');
        $menu->addClass('navigation-left');

        $id_usuario = \Jida\Medios\Sesion::$usuario->obtener('id_usuario');
        $perfil = new Usuario($id_usuario);

        $img_perfil = isset($perfil->img_perfil) ? Estructura::$urlBase.$perfil->img_perfil: null ;

        $this->data([
            'menu'      => $menu->render(),
            'nombreApp' => $nombreApp,
            'urlBase'   => $urlBase,
            'urlTema'   => $urlTema,
            'imgPerfil' => $img_perfil
        ]);
    }

    public function logout() {

        Sesion::destruir();
        $this->redireccionar('/jadmin');
    }

    function phpInfo() {

        echo phpinfo();
        exit;
    }

    protected function _validarSesion() {

        $metodo = Estructura::$metodo;
        $aceptados = ['login', 'logout'];

        if (in_array($metodo, $aceptados)) {
            return true;
        }

        if (!Sesion::activa()) {
            $this->redireccionar('/jadmin/login');
        }
    }

}
