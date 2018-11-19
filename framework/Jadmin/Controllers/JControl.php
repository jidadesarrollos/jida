<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 19/11/2018
 * Time: 10:41
 */

namespace Jida\Jadmin\Controllers;

use Jida\Core\Controlador;
use App\Config\Configuracion;
use Jida\Configuracion\Config;
use Jida\Medios as Medios;
use Jida\Render as Render;

class JControl extends Controlador {

    function __construct () {

        parent::__construct();
        $this->data('nombreApp', "Jida");
        $this->layout('jadmin');
        $urlBase = Configuracion::URL_BASE;
        $nombreApp = Configuracion::NOMBRE_APP;
        $menu = new Render\Menu('Jadmin');
        $this->data([
                        'menu'      => $menu->render(),
                        'urlBase'   => $urlBase,
                        'nombreApp' => $nombreApp
                    ]);
    }

    function phpInfo () {

        echo phpinfo();
        exit;

    }

    protected function _formularioInicioSesion () {

        $configuracion = Config::obtener();

    }

}