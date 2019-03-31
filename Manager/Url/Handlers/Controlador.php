<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 30/3/2019
 * Time: 17:03
 */

namespace Jida\Manager\Url\Handlers;

use Jida\Manager\Url\Definicion;
use Jida\Manager\Url\Handler;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;

class Controlador extends Handler {

    function validacion() {
        return true;
    }

    function definir() {

        $parametro = $this->url->proximoParametro();
        $controlador = Definicion::objeto($parametro);
        $namespace = Estructura::$namespace;

        if (!class_exists("$namespace\\$controlador")) {
            $this->url->reingresarParametro($parametro);
            $controlador = Estructura::$modulo;
        }

        if (!class_exists("$namespace\\$controlador")) {
            Debug::imprimir(["No existe el objeto", "$namespace\\$controlador"]);
        }

        Estructura::$controlador = "{$namespace}\\{$controlador}";
        Estructura::$nombreControlador = $controlador;
        $this->url->controlador = $controlador;
    }

}