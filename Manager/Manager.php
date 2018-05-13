<?php
/**
 * ce = 0;
 */

namespace Jida\Manager;

use Jida\Helpers as Helpers;
use Jida\Configuracion as Conf;
use App as App;

global $JD;

class Manager {

    private $_ce = '1000';

    private static $instancia;
    private $_validador;
    private $_entorno;
    private $_inicio;

    private $_configuracion;
    /*Tiempos*/
    private $_tiempoInicio;

    private $_tiempoFin;

    static $configuracion;

    function __construct () {

        $this->_validador = new Validador();
        $this->_entorno = new Entorno();

        self::$configuracion = Conf\Config::obtener();

        $this->_inicio = new Rutas\Lector($this);


    }

    public function inicio () {

        try {

            $this->_tiempoInicio = microtime(true);
            date_default_timezone_set(ZONA_HORARIA);
            Helpers\Sesion::iniciar();
            $_SERVER = array_merge($_SERVER, getallheaders());


            $this->_validador->inicio();
            $this->_inicio->validar();

        }
        catch (\Exception $e) {
            Helpers\Debug::imprimir("Capturada Excepcion en el manager", $e, true);
        }


    }




}