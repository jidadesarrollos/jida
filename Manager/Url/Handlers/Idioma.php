<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 30/3/2019
 * Time: 17:03
 */

namespace Jida\Manager\Url\Handlers;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Manager\Url\Handler;
use Jida\Medios\Debug;

class Idioma extends Handler {

    function validacion() {

        $config = Config::obtener();
        return $config::MULTIIDIOMA;

    }

    private function controlador() {

    }

    function definir() {

        $config = Config::obtener();
        $parametro = $this->url->proximoParametro();
        $idioma = $config::IDIOMA_DEFAULT;

        if (isset($config->idiomas[$parametro])) {
            $idioma = $parametro;
        }
        else {
            $this->url->reingresarParametro($parametro);
        }

        Estructura::$idioma = $idioma;
        return $idioma;

    }

}