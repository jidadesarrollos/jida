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

    private function controlador() {

        $parametro = $this->url->proximoParametro();
        $namespace = Estructura::$namespace;

        if (!$parametro) {
            $resp = (Estructura::$modulo) ? Definicion::objeto(Estructura::$modulo) : 'Index';
            return $resp;

        }

        $controlador = Definicion::objeto($parametro);

        if (!class_exists("$namespace\\$controlador")) {
            $this->url->reingresarParametro($parametro);
            $controlador = Estructura::$modulo;
        }

        return $controlador;

    }

    function definir() {

        $namespace = Estructura::$namespace;
        $controlador = $this->controlador();

        if (!class_exists("$namespace\\$controlador")) {
            Debug::imprimir(["No existe el objeto", "$namespace\\$controlador"]);
        }

        Estructura::$controlador = "{$namespace}\\{$controlador}";
        Estructura::$nombreControlador = $controlador;
        $this->url->controlador = $controlador;

    }

}