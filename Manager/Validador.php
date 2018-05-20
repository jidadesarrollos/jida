<?php

namespace Jida\Manager;


class Validador {

    private $_ce = 10011;

    static private $_entorno;

    function __construct () {

        Entorno::configurar();
    }

    public function inicio () {

        global $elementos;
        $elementos = [
            'areas'     => [],
            'elementos' => []
        ];

        $this->_validarConfiguracion();

        return true;

    }

    private function _validarConfiguracion () {

        if (class_exists('\App\Config\Configuracion')) {
            $configuracion = new \App\Config\Configuracion();
            $configuracion->inicio();
        }

    }


}