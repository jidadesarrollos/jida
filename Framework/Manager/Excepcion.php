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
use Jida\Configuracion\Config;
use Jida\Core\GeneradorCodigo\GeneradorCodigo;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;
use Jida\Manager\Vista\Data;
use Jida\Manager\Vista\Layout;

class Excepcion {

    use GeneradorCodigo;
    protected $ruta;
    protected $excepcion;
    protected $txtLog;

    const PLANTILLAS_APP = 'Aplicacion/plantillas/';

    function __construct(\Exception $e) {

        $this->nombreArchivo = "error.log";
        $this->ruta = Estructura::path();
        $this->excepcion = $e;

    }

    function _registrar() {

        $separador = "--------------------------";
        $fecha = "Fecha: " . date('Y-m-d H:i:s');
        $mensaje = "Mensaje: " . $this->excepcion->getMessage();
        $codigo = "Error: " . $this->excepcion->getCode();
        $archivo = "Archivo: " . $this->excepcion->getFile();
        $linea = "Linea: " . $this->excepcion->getLine();

        $traza = $this->excepcion->getTrace();
        $detalle = "";

        foreach ($traza as $k => $v) {
            if (array_key_exists('file', $v)) {
                $detalle .= "Archivo: " . $v['file'];
            }

            if (array_key_exists('line', $v)) {
                $detalle .= " | Linea: " . $v['line'];
            }

            if (array_key_exists('class', $v)) {
                $detalle .= "\r\nClase: " . $v['class'] . "::" . $v['function'] . "\r\n";
            }
            else {
                $detalle .= "\r\nClase: " . $v['function'] . "\r\n";
            }

        }

        $log = implode("\r\n",
            [
                $separador,
                $fecha,
                $separador,
                $codigo,
                $mensaje,
                $detalle
            ]);

        $this->txtLog = implode("<br/>",
            [
                $codigo,
                $mensaje,
                $detalle
            ]);

        if (Configuracion::ENVIAR_EMAIL_ERROR) {
            $this->_enviarEmail();
        }

        $this
            ->crear($this->nombreArchivo, "a+")
            ->escribir($log)
            ->cerrar();

    }

    function log() {

        $this->_registrar();
        $excepcion = $this->excepcion;

        $configuracion = Config::obtener();

        $path = $this->ruta;
        $directorio = "";

        if (Directorios::validar($path . '/Aplicacion/Layout/' . $configuracion->tema . '/error.tpl.php')) {
            $directorio = $path . '/Aplicacion/Layout/' . $configuracion->tema . '/error.tpl.php';
        }
        else {
            $directorio = $path . DS . DIR_JF . '/Layout/error.tpl.php';
        }

        $vista = $path . DS . DIR_JF . "/plantillas/error/error.php";

        $dataExcepcion = new \stdClass();
        $dataExcepcion->mensaje = $excepcion->getMessage();
        $dataExcepcion->codigo = $excepcion->getCode();
        $dataExcepcion->archivo = $excepcion->getFile();
        $dataExcepcion->linea = $excepcion->getLine();
        $dataExcepcion->traza = $excepcion->getTrace();
        $dataExcepcion->trazaStr = $excepcion->getTraceAsString();

        $layout = new Layout();
        $layout::definir($directorio);
        $data = new \stdClass();
        $data->excepcion = $dataExcepcion;
        Data::destruir();
        Data::inicializar($data);
        echo $layout->render($vista);

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
            Debug::imprimir(["procesada excepcion", $exception], true);
        }

    }

    public static function capturar(\Exception $e) {
        Debug::imprimir(["Capturada Excepcion", $e], true);
    }

}