<?php

namespace Jida\Manager\Rutas\Procesador;

use Jida\Manager\Estructura;

class Url {

    private $_url;
    private $_args;
    private $_get;
    private $_partes;
    static public $actual;
    static public $base;

    function __construct() {

        $this->_get = $_GET;

        if (isset($this->_get['url'])) {
            unset($this->_get['url']);
        }

        if (count($this->_get)) {
            $this->_args = $this->_get;
        }

        self::$base = Estructura::$urlBase;
        $this->_partes = Estructura::$partes;

    }

    public function proximoParametro() {

        $proximo = array_shift($this->_partes);
        return $proximo;

    }

    public function reingresarParametro($parametro) {

        array_unshift($this->_partes, $parametro);

    }

    function parametros() {
        return $this->_partes;
    }

}