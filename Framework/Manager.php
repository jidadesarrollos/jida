<?php
/**
 * ce = 2;
 */

namespace Jida;

use Jida\Configuracion as Conf;
use Jida\Manager\Rutas\Arranque;
use Jida\Medios as Medios;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Manager\Rutas\Lector;
use Jida\Manager\Validador;

class Manager {

    private $_ce = '1000';

    private $_validador;
    private $_lector;

    /*Tiempos*/
    private       $_tiempoInicio;
    private       $_tiempoFin;
    public static $configuracion;

    private $ruta;

    private static $_controlador;

    function __construct($ruta) {

        try {

            $this->ruta = $ruta;

            Conf\Base::constantes();

            self::$configuracion = Conf\Config::obtener();

            Estructura::procesar($ruta);
            Conf\Base::path();

            $this->_validador = new Validador();
            $this->_lector = new Lector($this);

        }
        catch (\Exception $e) {
            Medios\Debug::imprimir([$e], true);
            exit("capturada excepcion");
        }
        catch (\Error $e) {

        }

    }

    public function inicio() {

        $this->_tiempoInicio = microtime(true);

        $config = self::$configuracion;
        if (!$config) {
            $msj = "No se consigue el objeto de configuración";
            throw new \Exception($msj, $this->_ce . 2);
        }

        date_default_timezone_set($config::ZONA_HORARIA);

        Medios\Sesion::iniciar();

        $_SERVER = array_merge($_SERVER, getallheaders());

        if ($this->_validador->inicio()) {
            $this->_lector->validar();
        }
        else {
            $msj = "La aplicación no se encuentra configurada de forma correcta";
            Excepcion::procesar($msj, $this->_ce . 1);
        }

        $this->_tiempoFin = microtime(true);

    }

    static function controlador() {
        if (!self::$_controlador) {
            self::$_controlador = Arranque::$Controlador;
        }
        return self::$_controlador;
    }

}