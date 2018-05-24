<?php
/**
 * Created by PhpStorm.
 * User: Rosmy Rodriguez
 * Date: 23/5/2018
 * Time: 11:32 AM
 */

namespace Jida\Core;


use Jida\Helpers\Debug;
use Jida\Manager\Estructura;

class Excepcion extends \Exception {

    protected $nombre_archivo;

    protected $ruta;

    protected $excepcion;

    function __construct (\Exception $e) {

        $this->nombre_archivo = "jida_error_log.txt";
        $this->ruta = Estructura::path();
        $this->excepcion = $e;

    }

    function log () {

        Debug::imprimir("Capturada Excepcion en Log");

        $fecha = "Fecha: " . date('Y-m-d H:i:s');
        $error = "Error: " . $this->excepcion->getMessage();
        $codigo = "Codigo: " . $this->excepcion->getCode();
        $archivo = "Archivo: " . $this->excepcion->getFile();
        $linea = "Linea: " . $this->excepcion->getLine();

        $log = implode(" | ", [$fecha, $error, $linea]);

        $this->insertar($log);

    }

    function insertar ($texto) {


        $archivo = fopen($this->ruta . '/' . $this->nombre_archivo, 'a+');

        if (fwrite($archivo, $texto . "\r\n")) {
            Debug::imprimir("Log registrado con exito.");
            return $this;
        }
        else {
            return false;
        }

    }


}