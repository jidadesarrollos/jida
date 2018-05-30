<?php
/**
 * Created by PhpStorm.
 * User: Rosmy Rodriguez
 * Date: 23/5/2018
 * Time: 11:32 AM
 */

namespace Jida\Manager;

use Jida\Configuracion\Config;
use Jida\Core\GeneradorCodigo\GeneradorCodigo;
use Jida\Helpers\Debug;
use Jida\Helpers\Directorios;
use Jida\Manager\Estructura;
use Jida\Manager\Vista\Data;
use Jida\Manager\Vista\Layout;

class Excepcion {

    use GeneradorCodigo;
    protected $ruta;
    protected $excepcion;

    const PLANTILLAS_APP = 'Aplicacion/plantillas/';

    function __construct (\Exception $e) {

        $this->nombreArchivo = "error.log";
        $this->ruta = Estructura::path();
        $this->excepcion = $e;

    }

    function _registrar () {

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

        $this
            ->crear($this->nombreArchivo, "a+")
            ->escribir($log)
            ->cerrar();

    }

    function log () {

        $this->_registrar();
        $excepcion = $this->excepcion;

        $configuracion = Config::obtener();

        $path = $this->ruta;
        $directorio = "";

        if (Directorios::validar($path . '/Aplicacion/Layout/' . $configuracion->tema . '/error.tpl.php')) {
            $directorio = $path . '/Aplicacion/Layout/' . $configuracion->tema . '/error.tpl.php';
        }
        else {
            $directorio = $path . DIR_JF . '/Layout/error.tpl.php';
        }

        $vista = $path . DIR_JF . "/plantillas/error/error.php";

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


}