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
     * @var int $total Numero total de archivos cargados
     */
    var $total;

    private $_archivos = [];

    function __construct($carga) {

        if (isset($_FILES[$carga])) {

            $carga = $_FILES[$carga];

            if (!is_array($carga['tmp_name'])) $carga = [$carga];

            foreach ($carga as $indice => $archivo) {
                array_push($this->_archivos, new ArchivoCargado($archivo));
            }

        }
        Debug::imprimir([$this->_archivos], true);

    }

    /**
     * Verifica la carga de uno o varios archivos
     * @method validarCarga
     */
    function validar() {

        $totalCarga = count($this->tmp_name);
        $archivosCargados = 0;

        foreach ($this->tmp_name as $key) {
            if (is_uploaded_file($key)) ++$archivosCargados;
        }//fin foreach

        if ($totalCarga == $archivosCargados) {
            $this->total = $archivosCargados;
            return true;
        }

        return false;

    }

}