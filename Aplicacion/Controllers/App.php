<?php

namespace App\Controllers;

use Jida\Core\Controlador\Control;
use Jida\Medios\Debug;
use Jida\Render\Menu;

class App extends Control {

    function __construct() {

        parent::__construct();

        $this->layout('default');

        $menu = new Menu('principal');

        $urlTema = '/Aplicacion/Layout/jacobsen/';

        $this->data([
            'menu'    => $menu->render(),
            'urlTema' => $urlTema
        ]);

    }

}
