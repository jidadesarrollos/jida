<?php

namespace Jida\Core\Controlador;

use Jida\Manager;
use Jida\Medios\Debug;

Trait Peticion {

    private $_post;
    private $_get;
    private $_request;
    private $_files;
    /**
     * @var object $data Objeto Data para pasar la informacion a las vistas
     * @see Data
     */
    private $_data;

    private function _procesarPeticiones() {

        $this->_post = $_POST;
        $this->_get = $_GET;
        $this->_request = $_REQUEST;
        $this->_files = $_FILES;

    }

    protected function post($propiedad = "", $valor = "") {

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

    protected function get($propiedad, $valor = "") {

        if ($valor) {
            $this->_get[$propiedad] = $valor;
        }

        if (!array_key_exists($propiedad, $this->_get)) {
            return false;
        }

        return $this->_get[$propiedad];

    }

    protected function request($propiedad, $valor = "") {

        if ($valor) {
            $this->_request[$propiedad] = $valor;
        }

        if (!array_key_exists($propiedad, $this->_request)) {
            return;
        }

        return $this->_request[$propiedad];

    }

    /**
     * Permite acceder ala data buscada en el arreglo global $_FILES
     *
     * @param string $propiedad clave a buscar en $_FILES
     * @return mixed
     */
    protected function files($propiedad) {

        if (isset($this->_files[$propiedad])) {
            return $this->_files[$propiedad];
        }

    }

    /**
     * Valida si se ha realizado una solicitud ajax (se debe usar el plugin javascript jd.ajax)
     *
     * Verifica la existencia del post s-ajax
     * @method solicitudAjax
     *
     * @return boolean
     */
    protected function solicitudAjax() {

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) and
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            $this->data('solicitudAjax', true);

            return true;
        }

        return false;

    }

}