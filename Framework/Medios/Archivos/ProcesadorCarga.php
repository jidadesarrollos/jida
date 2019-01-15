<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 14/1/2019
 * Time: 07:46
 */

namespace Jida\Medios\Archivos;

use Jida\Medios\Debug;

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

}