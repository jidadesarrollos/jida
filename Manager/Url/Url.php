<?php

namespace Jida\Manager\Url;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;

class Url {

    private $_url;
    private $_args;
    private $_get;
    private $_partes;
    static public $actual;
    static public $base;

    public $modulo;
    public $pathModulo;
    public $urlModulo;
    public $controlador;
    public $pathControlador;
    public $metodo;

    private static $instancia;

    static public $config;

    function __construct() {

        $this->_get = $_GET;

        if (isset($this->_get['url'])) {
            unset($this->_get['url']);
            unset($_GET['url']);
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

    static function obtener() {

        if (!self::$instancia) self::$instancia = new self();
        return self::$instancia;
    }
}