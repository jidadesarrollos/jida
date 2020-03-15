<?php
/**
 * Created by PhpStorm.
 * User: Rosmy Rodriguez
 * Date: 23/5/2018
 * Time: 11:32 AM
 */

namespace Jida\Manager;

use App\Config\Configuracion;
use Jida\Componentes\Correo;
use Jida\Core\GeneradorCodigo\GeneradorCodigo;
use Jida\Manager\Excepcion\Log;
use Jida\Manager\Vista\Layout;
use Jida\Manager\Vista\Manager;
use Jida\Manager\Vista\Tema;
use Jida\Medios\Debug;

class Excepcion {

    use GeneradorCodigo, Log;
    protected $ruta;
    protected $excepcion;
    protected $txtLog;
    private static $contador;

    function __construct(\Exception $e) {

        $this->nombreArchivo = "error.log";
        $this->ruta = Estructura::path();
        $this->excepcion = $e;

    }

    private function _enviarEmail() {

        $destinatario = Configuracion::EMAIL_SOPORTE;
        $detalle_error = str_replace("\r\n", "<br/>", $this->txtLog);

        $correo = new Correo();
        $correo->plantilla("error");
        $correo->data([
            'aplicacion'    => Configuracion::NOMBRE_APP,
            'detalle_error' => $detalle_error
        ]);
        $correo->enviar($destinatario, "Error generado en " . Configuracion::NOMBRE_APP);

        return $this;

    }

    public static function procesar($msj, $codigo) {
        try {
            throw new \Exception($msj, $codigo);
        }
        catch (\Exception $exception) {
            self::validar($exception);
        }

    }

    private static function _api($excepcion) {

        $traza = $excepcion->getTrace();
        array_pop($traza);

        $impresion = [
            'message' => $excepcion->getMessage(),
            'code'    => $excepcion->getCode(),
            'trace'   => $traza
        ];

        foreach ($impresion['trace'] as $key => $item) {
            array_walk($item['args'], function (&$elemento) {
                if (is_string($elemento)) $elemento = strip_tags($elemento);
            });
            $impresion['trace'][$key] = $item;
        }

        //Debug::imprimir([$impresion], true);
        exit(json_encode(htmlspecialchars($impresion), JSON_PRETTY_PRINT));

    }

    private static function _obtenerVista($e) {

    }

    static function validar($e, $type = 'exception') {

        $tema = Tema::obtener();
        $conf = $tema::$configuracion;
        /**
         * TODO: configure API way
         */
        $hasTpl = !!is_object($conf->layout) and !isset($conf->layout->error);

        if (!$conf or !$hasTpl) self::_api($e);
//        $layout = Layout::obtener();
//        $layout->_definirPlantilla($conf->layout->error . ".tpl.php");

        $manager = new Manager($e);

        $manager->renderizar();

    }
}