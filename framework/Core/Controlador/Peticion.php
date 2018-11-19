<?php

namespace Jida\Core\Controlador;

Trait Peticion {

    private $_post;
    private $_get;
    private $_request;
    /**
     * @var object $data Objeto Data para pasar la informacion a las vistas
     * @see Data
     */
    private $_data;

    private function _procesarPeticiones () {

        $this->_post = $_POST;
        $this->_get = $_GET;
        $this->_request = $_REQUEST;

    }

    protected function post ($propiedad = "", $valor = "") {

        if ($valor) {
            $this->_post[$propiedad] = $valor;
        }

        if (!empty($propiedad)) {

            if (!array_key_exists($propiedad, $this->_post)) {
                return;
            }

            return $this->_post[$propiedad];
        }

        return $this->_post;

    }

    protected function get ($propiedad, $valor = "") {

        if ($valor) {
            $this->_get[$propiedad] = $valor;
        }

        if (!array_key_exists($propiedad, $this->_get)) {
            return false;
        }

        return $this->_get[$propiedad];

    }

    protected function request ($propiedad, $valor = "") {

        if ($valor) {
            $this->_request[$propiedad] = $valor;
        }

        if (!array_key_exists($propiedad, $this->_request)) {
            return;
        }

        return $this->_request[$propiedad];

    }

}