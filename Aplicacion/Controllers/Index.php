<?php
/**
 * Controlador por defecto
 */

namespace App\Controllers;

use Jida\Render as Render;
use Jida\Medios as Medios;

class Index extends App {

    function index () {
        Medios\Debug::imprimir("llego aca", true);
    }

}
