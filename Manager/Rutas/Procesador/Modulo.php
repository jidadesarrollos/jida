<?php

namespace Jida\Manager\Rutas\Procesador;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Manager\Rutas\Jadmin;
use Jida\Medios\Debug;

Trait Modulo {

    var $ruta;
    var $url;
    private $_namespaces = [
        'app'    => 'App\\Controllers\\',
        'modulo' => 'App\\Modulos\\',

    ];
    private $_namespace;

    private function definir() {

        $modulo = Estructura::$modulo;
        $directorio = Estructura::$directorio;
        $ds = DS;

        if (!$modulo) {

            Estructura::$ruta = "${directorio}{$ds}Aplicacion";
            Estructura::$rutaModulo = "${directorio}{$ds}Aplicacion";
            Estructura::$namespace = $this->_namespaces['app'];
            return;

        }

        $rutaModulo = "{$directorio}{$ds}Aplicacion{$ds}Modulos{$ds}{$modulo}";
        $url = "/Aplicacion/Modulos/{$modulo}";
        Estructura::$namespace = $this->_namespaces['modulo'];
        Estructura::$urlModulo = $url;
        Estructura::$rutaModulo = $rutaModulo;

    }

    protected function _modulo() {

        $padre = $this->_padre;

        $parametro = $padre->url->proximoParametro();
        $url = "";
        $modulo = $this->_validarNombre($parametro, 'upper');
        $modulos = $padre->modulos;

        if ($modulo and (isset($modulos[$parametro]))) {
            Estructura::$modulo = $modulo;
        }
        else {
            $padre->url->reingresarParametro($parametro);
        }

        $this->definir();
        $padre->pipeModulos($modulo);

    }

}