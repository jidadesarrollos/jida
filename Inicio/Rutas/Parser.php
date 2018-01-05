<?php

namespace Jida\Inicio\Rutas;


class Parser {

    private $_arrayUrl;
    private $_procesador;

    public $default;
    public $controlador;
    public $jadmin;
    public $metodo;
    public $modulo;
    public $namespace;
    public $parametros;

    public function __construct($datos) {

        $this->_arrayUrl = $datos;
        $this->_parser();

    }

    private function _parser() {

        $parametro = array_shift($this->_arrayUrl);
        if (strtolower($parametro) === 'jadmin') {
            $this->jadmin = true;
            $this->_procesador = new Jadmin($this);
        }

        $this->_procesador->procesar();

    }

    public function proximoParametro() {

        return array_shift($this->_arrayUrl);

    }

    public function reingresarParametro($parametro) {

        array_unshift($this->_arrayUrl, $parametro);

    }


}