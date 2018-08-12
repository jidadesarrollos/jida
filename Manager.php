<?php
/**
 * ce = 1;
 */

namespace Jida;

use Jida\Config\Base;
use Jida\Helpers as Helpers;
use Jida\Configuracion as Conf;
use Jida\Manager\Estructura;
use Jida\Manager\Rutas\Lector;
use Jida\Manager\Validador;
use Jida\Manager\Excepcion;

class Manager {

    private $_ce = '1000';

    private $_validador;
    private $_lector;

    /*Tiempos*/
    private $_tiempoInicio;

    private $_tiempoFin;

    static $configuracion;

    private $ruta;

    function __construct ($ruta) {

        try {

            $this->ruta = $ruta;

            Base::constantes();
            self::$configuracion = Conf\Config::obtener();
            Estructura::procesar($ruta);

            $this->_validador = new Validador();
            $this->_lector = new Lector($this);

        }
        catch (\Exception $e) {
            exit("capturada excepcion");
        }
        catch (\Error $e) {
            exit("capturado error");

        }

    }

    public function inicio () {

        try {

            $this->_tiempoInicio = microtime(true);

            $config = self::$configuracion;

            date_default_timezone_set($config::ZONA_HORARIA);
            $_SERVER = array_merge($_SERVER, getallheaders());
            Helpers\Sesion::iniciar();

            if ($this->_validador->inicio()) {
                $this->_lector->validar();
            }
            else {
                $msj = "La aplicación no se encuentra configurada de forma correcta";
                throw new \Exception($msj, $this->_ce . 1);
            }

            $this->_tiempoFin = microtime(true);

        }
        catch (\Exception $e) {
            Helpers\Debug::imprimir($e);
            $excepcion = new Excepcion($e);
            $excepcion->log();

        }

    }

}