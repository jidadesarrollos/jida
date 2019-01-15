<?php

namespace App\Jadmin\Controllers;

use Jida\Manager\Estructura;
use Jida\Medios\Archivos\ProcesadorCarga;
use Jida\Medios\Debug;

class Test extends Jadmin {

    function gestion() {

        if ($this->post('cargaArchivos')) {

            $procesador = new ProcesadorCarga('cargaArchivo');

            if (!$procesador->validar()) {
                Debug::imprimir(["Hubo error en la carga"], true);
                // TODO: Manejo de errores
            }
            else {
                if (!$procesador->mover(Estructura::$directorio . '/htdocs/test')) {
                    Debug::imprimir(["Hubo error moviendola"], true);
                }
            }

            Debug::imprimir(["todo bien"], true);

        }

    }

}