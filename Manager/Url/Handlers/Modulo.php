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
use Jida\Manager\Url\Definicion;
use Jida\Manager\Url\Handler;
use Jida\Medios\Arrays;

class Modulo extends Handler {

    private $default = 'Index';

    function validacion() {
        if ($this->url->modulo) return false;
        return self::$aplica = true;
    }

    function definir() {

        $config = Config::obtener();
        $parametro = $this->url->proximoParametro();
        $modulo = Definicion::objeto($parametro);

        if (Estructura::$namespace && Estructura::$ruta) return;

        $ruta = Estructura::$rutaAplicacion;

        if (!$config::modulo($parametro)) {

            $this->url->reingresarParametro($parametro);
            Estructura::$namespace = "App\\Controllers";
            Estructura::$ruta = $ruta;
            Estructura::$modulo = $this->default;
            Estructura::$rutaModulo = $ruta;
            return;

        }

        Estructura::$modulo = $modulo;
        Estructura::$namespace = "App\\Modulos\\{$modulo}\\Controllers";
        Estructura::$ruta = $ruta;
        Estructura::$rutaModulo = "{$ruta}/Modulos/$modulo";

    }

}