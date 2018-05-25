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

        $this->nombreArchivo = "jida_error_log.txt";
        $this->ruta = Estructura::path();
        $this->excepcion = $e;

    }

    function _registrar () {

        $fecha = "Fecha: " . date('Y-m-d H:i:s');
        $error = "Error: " . $this->excepcion->getMessage();
        $codigo = "Codigo: " . $this->excepcion->getCode();
        $archivo = "Archivo: " . $this->excepcion->getFile();
        $linea = "Linea: " . $this->excepcion->getLine();
        $log = implode(" | ", [$fecha, $error, $linea]);

        $this
            ->crear($this->nombreArchivo)
            ->escribir($log)
            ->cerrar();

    }

    function log () {

        $this->_registrar();
        Debug::imprimir("Capturada Excepcion en Log");

        $configuracion = Config::obtener();

        $path = Estructura::path();
        $directorio = "";

        if (Directorios::validar($path . '/Layout/error.tpl.php')) {
            $directorio = $path . '/Layout/error.tpl.php';
        }
        else {
            $directorio = $path . DIR_JF . '/Layout/error.tpl.php';
        }

        $vista = $path . DIR_JF . "/plantillas/error/error.php";

        $layout = new Layout();
        $layout::definir($directorio);
        $data = new \stdClass();
        $data->excepcion = $this->excepcion;
        Data::destruir();
        Data::inicializar($data);
        echo $layout->render($vista);


    }

    function insertar ($texto) {


        $archivo = fopen($this->ruta . '/' . $this->nombre_archivo, 'a+');

        if (fwrite($archivo, $texto . "\r\n")) {

            Debug::imprimir("Log registrado con exito.");

            return true;

        }
        else {
            return false;
        }

    }


}