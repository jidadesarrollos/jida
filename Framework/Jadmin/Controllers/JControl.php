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
use Jida\Render\Menu;

class JControl extends Controlador {

    function __construct() {

        parent::__construct();

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

    function phpInfo() {

        echo phpinfo();
        exit;

    }

}