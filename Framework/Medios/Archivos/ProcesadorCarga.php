<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 14/1/2019
 * Time: 07:46
 */

namespace Jida\Medios\Archivos;

use Jida\Medios\Debug;
use Jida\Medios\Directorios;

class ProcesadorCarga {

    /**
     * @var boolean $procesada Define si un archivo es subido exitosamente
     */
    var $procesada;

    /**
     * @var int $total Numero total de archivos que se intentan subir
     */
    var $totalArchivos = 0;
    /**
     * @var int $total Numero total de archivos cargados
     */
    var $totalArchivosCargados = 0;

    var $errores = [];

    private $_archivos = [];

    function __construct($carga) {

        if (isset($_FILES[$carga])) {

            $carga = $_FILES[$carga];

            if (!is_array($carga['tmp_name'])) $carga = [$carga];

            $carga = $this->_listaArchivos($carga);

            $this->totalArchivos = count($carga);

            foreach ($carga as $indice => $archivo) {

                $archivo = new ArchivoCargado($archivo);
                if ($archivo->cargado) {
                    ++$this->totalArchivosCargados;
                }
                else {
                    $this->errores[] = $archivo->error();
                }

                array_push($this->_archivos, $archivo);
            }

        }

    }

    private function _listaArchivos($archivos) {
        $strucArchivos = [];
        for ($i = 0; $i < count($archivos['name']); $i++) {
            $archivo = [];
            $archivo['name'] = $archivos['name'][$i];
            $archivo['type'] = $archivos['type'][$i];
            $archivo['tmp_name'] = $archivos['tmp_name'][$i];
            $archivo['error'] = $archivos['error'][$i];
            $archivo['size'] = $archivos['size'][$i];
            $strucArchivos[$i] = $archivo;
        }
        return $strucArchivos;
    }

    /**
     * Verifica la carga de uno o varios archivos
     * @method validarCarga
     */
    function validar() {

        if ($this->totalArchivosCargados < $this->totalArchivos) {
            return false;
        }

        return true;
    }

    function mover($directorio, $prefijo = "") {

        if (!Directorios::validar($directorio))
            Directorios::crear($directorio);

        foreach ($this->_archivos as $archivo) {

            $nombreArchivo = $this->_generadorNombres($archivo->extension, $prefijo);
            $nuevoArchivo = $directorio . "/" . $nombreArchivo;
            $archivo->mover($nuevoArchivo);

        }
        return true;

    }

    private function _generadorNombres($extension, $prefijo = "") {
        $fecha = md5(Date('U'));
        $random = rand(100000, 999999);
        $name = $fecha . $random;
        $name = (!empty($prefijo)) ? $prefijo . "-" . $name : $name;

        return $name . "." . $extension;
    }
}