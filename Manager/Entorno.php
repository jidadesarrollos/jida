<?php

namespace Jida\Manager;

class Entorno {

    public $ce = 10001;

    function __construct () {

        if (defined('ENTORNO_APP')) {
            $this->_configurar();

            return;
        }
        $this->_desarrollo();

    }

    private function _configurar () {

        if (ENTORNO_APP === 'dev') {
            return $this->_desarrollo();
        }
        else {
            return $this->_produccion();
        }

    }

    private function _desarrollo () {

        ini_set("display_errors", 1);
        ini_set("track_errors", 1);
        ini_set("html_errors", 1);
        error_reporting(E_ALL);

    }

    private function _produccion () {

        ini_set("display_errors", 0);
        ini_set("track_errors", 0);
        ini_set("html_errors", 0);
        error_reporting(0);

    }
}