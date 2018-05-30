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
    private $_lector;

    private $_configuracion;
    /*Tiempos*/
    private $_tiempoInicio;

    private $_tiempoFin;

    static $configuracion;

    function __construct () {

        $this->_validador = new Validador();

        self::$configuracion = Conf\Config::obtener();

        $this->_lector = new Rutas\Lector($this);


    }

    public function inicio () {

        try {

            $this->_tiempoInicio = microtime(true);
            $config = self::$configuracion;
            date_default_timezone_set($config::ZONA_HORARIA);
            Helpers\Sesion::iniciar();
            $_SERVER = array_merge($_SERVER, getallheaders());


            if ($this->_validador->inicio()) {
                $this->_lector->validar();
            }
            else {
                exit("no arranca");
            }

        }
        catch (\Exception $e) {

            $excepcion = new Excepcion($e);
            $excepcion->log();

        }


    }


}