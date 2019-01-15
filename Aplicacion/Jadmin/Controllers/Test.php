<?php

namespace App\Jadmin\Controllers;

use Jida\Medios\Archivos\ProcesadorCarga;
use Jida\Medios\Debug;

class Test extends Jadmin {

    function gestion() {

        if ($this->post('cargaArchivos')) {

            $procesador = new ProcesadorCarga('cargaArchivo');
            if($procesador->c)
            if ($procesador->validar()) {
                $procesador->moverArchivos($directorio);
            }

        }

    }

}