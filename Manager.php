<?php
/**
 * ce = 1;
 */

namespace Jida;

use Jida\Configuracion as Conf;

use Jida\Manager\Procesador;
use Jida\Medios as Medios;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;

use Jida\Manager\Validador;

class Manager {

    private static $_ce = 1000;

    private $_validador;
    /*Tiempos*/
    private $_tiempoInicio;
    private $_tiempoFin;
    private static $instancia;

    private function __construct($ruta) {

        try {

            if (!Medios\Directorios::validar($ruta)) {
                throw new \Exception("La ruta pasada para iniciar el jida no existe: $ruta", 1);
            }

            Conf\Base::constantes();
            Estructura::procesar(__DIR__, $ruta);
            Conf\Base::path();

            $this->_validador = new Validador();

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
    private function _inicio($parametros = []) {

        $this->_tiempoInicio = microtime(true);
        $config = Conf\Config::obtener();

        if (!$config) {
            $msj = "No se consigue el objeto de configuración";
            throw new \Exception($msj, self::$_ce . 2);
        }

        Medios\Sesion::iniciar();
        date_default_timezone_set($config::ZONA_HORARIA);
        $_SERVER = array_merge($_SERVER, $this->_getAllHeaders());

        if (!$this->_validador->inicio()) {

            $msj = "La aplicación no se encuentra configurada de forma correcta";
            Excepcion::procesar($msj, self::$_ce . 1);
            return false;

        }

        $procesador = new Procesador($parametros);
        $procesador->ejecutar();

        $this->_tiempoFin = microtime(true);

    }

    private function _getAllHeaders() {

        if (!function_exists('getallheaders')) {

            function getallheaders() {
                if (!is_array($_SERVER)) {
                    return array();
                }
                $headers = array();
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-',
                            ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }

        }

    }

    static function inicio($ruta, $parametros = []) {

        try {
            if (!self::$instancia) self::$instancia = new Manager($ruta);

            $manager = self::$instancia;
            $manager->_inicio($parametros);
        }
        catch (\Exception $exception) {
            self::error($exception);
        }
        catch (\Error $error) {
            self::error($error, true);
        }

    }

    private static function error($exception) {

        $error = is_a($exception, '\Error');
        $mensaje = ($error) ? "Error capturado" : "Excepcion capturada";
        $data = [
            "tipo"    => $mensaje,
            "mensaje" => $exception->getMessage(),
            "traza"   => $exception->getTrace()

        ];
        echo json_encode($data);
        exit;
        //Medios\Debug::imprimir($data, true);
    }

}