<?php

namespace Jida\Inicio;

use Jida\Helpers as Helpers;
use Jida\Configuracion as Conf;

global $JD;

class Manager {

    private $_ce = '1000';

    private $_validador;
    private $_entorno;
    private $_control;
    private $_configuracion;

    /*Tiempos*/
    private $_tiempoInicio;
    private $_tiempoFin;

    function __construct() {

        $this->_validador = new Validador();
        $this->_entorno = new Entorno();
        $this->_configuracion = (array_key_exists('JIDA_CONF', $GLOBALS)) ? $GLOBALS['JIDA_CONF'] : new Conf\Config();
        $this->_control = new Rutas\Control($this);


    }

    public function inicio() {

        try {

            $this->_tiempoInicio = microtime(true);
            date_default_timezone_set(ZONA_HORARIA);
            Helpers\Sesion::iniciar();
            $_SERVER = array_merge($_SERVER, getallheaders());

            $this->_validador->inicio();
            $this->_control->validar();

        } catch (\Exception $e) {
            Helpers\Debug::imprimir("Capturada Excepcion en el manager", $e, true);
        }


    }

    public function configuracion() {

        return $this->_configuracion;

    }
}