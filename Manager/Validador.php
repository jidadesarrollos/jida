<?php

namespace Jida\Manager;


use Jida\Helpers\Debug;

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

        $this->_configurarEntorno()
            ->_validarConfiguracion();

        return true;

    }

    private function _configurarEntorno () {


        if (function_exists('ini_set')) {
            /**
             * Inclusión de directorios de aplicación, framework y libs dentro del path
             */
            ini_set('include_path', DIR_APP . PS . DIR_FRAMEWORK . PS . get_include_path());

        }
        else {
            throw new Exception("Debe activar la funcion ini_set para continuar..");

        }

        return $this;

    }

    private function _validarConfiguracion () {

        if (class_exists('\App\Config\Configuracion')) {
            $configuracion = new \App\Config\Configuracion();
            $configuracion->inicio();
        }

    }


}