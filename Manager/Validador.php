<?php

namespace Jida\Manager;


class Validador {

    private $_ce = 10011;

    function __construct () {

    }

    public function inicio () {

        global $elementos;
        $elementos = [
            'areas'     => [],
            'elementos' => []
        ];

        $this->_validarConfiguracion();

    }

    private function _validarConfiguracion () {

        if (class_exists('\App\Config\Configuracion')) {
            $configuracion = new \App\Config\Configuracion();
            $configuracion->inicio();
        }

    }


}