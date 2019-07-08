<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 30/3/2019
 * Time: 17:03
 */

namespace Jida\Manager\Url\Handlers;

use Jida\Manager\Url\Handler;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;

class Metodo extends Handler {

    function validacion() {
        return self::$aplica = true;
    }

    function definir() {

        $metodo = $this->url->proximoParametro();

        if (!$metodo or !method_exists(Estructura::$controlador, $metodo)) {
            $this->url->reingresarParametro($metodo);
            $metodo = 'index';
        }

        Estructura::$metodo = $metodo;
        $this->url->metodo = $metodo;

    }

}