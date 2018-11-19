<?php
/**
 * Controlador por defecto
 */

namespace App\Controllers;

use Jida\Render as Render;
use Jida\Helpers as Helpers;

class Index extends App {

    function index () {
        Helpers\Debug::imprimir("llego aca", true);
    }

}
