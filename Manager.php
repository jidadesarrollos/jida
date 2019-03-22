<?php
/**
 * ce = 1;
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

    private static $_ce = 1000;

    private $_validador;
    private $_arranque;

    /*Tiempos*/
    private $_tiempoInicio;
    private $_tiempoFin;
    public static $configuracion;

    private $ruta;
    private $rutaApp;
    private static $instancia;

    private $parametros;

    function __construct($ruta, $parametros = []) {

        try {

            if (!Medios\Directorios::validar($ruta)) {
                throw new \Exception("La ruta pasada para iniciar el jida no existe: $ruta", 1);
            }

            $this->ruta = __DIR__;
            $this->rutaApp = $ruta;
            $this->parametros = $parametros;

            Conf\Base::constantes();

            self::$configuracion = Conf\Config::obtener();

            Estructura::procesar($this->ruta, $ruta);

            Conf\Base::path();

            $this->_validador = new Validador();
            $this->_arranque = new Arranque($parametros);

        }
        catch (\Exception $e) {
            Medios\Debug::imprimir([$e], true);
            exit("capturada excepcion");
        }
        catch (\Error $e) {
            Medios\Debug::imprimir(["error", $e], true);
        }

    }

    /**
     * Define el inicio de ejecución del Jida.
     *
     * @throws \Exception
     */
    private function _inicio() {

        $this->_tiempoInicio = microtime(true);

        $config = self::$configuracion;

        if (!$config) {
            $msj = "No se consigue el objeto de configuración";
            throw new \Exception($msj, self::$_ce . 2);
        }

        date_default_timezone_set($config::ZONA_HORARIA);

        Medios\Sesion::iniciar();

        $_SERVER = array_merge($_SERVER, getallheaders());

        if ($this->_validador->inicio()) {
            $this->_arranque->ejecutar();
        }
        else {
            $msj = "La aplicación no se encuentra configurada de forma correcta";
            Excepcion::procesar($msj, self::$_ce . 1);
        }

        $this->_tiempoFin = microtime(true);

    }

    static function inicio($ruta, $parametros = []) {

        if (!self::$instancia) {
            self::$instancia = new Manager($ruta, $parametros);
        }

        $manager = self::$instancia;
        try {
            $manager->_inicio();
        }
        catch (\Exception $exception) {
            Medios\Debug::imprimir(["capturada excepcion", $exception->getMessage()], true);
        }

    }

}