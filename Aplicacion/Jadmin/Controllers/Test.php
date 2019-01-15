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
                Debug::imprimir(["Hubo error en la carga", $procesador->errores], true);
                // TODO: Manejo de errores
            }
            else {
                $procesador->mover(Estructura::$directorio . '/htdocs/test/test5');
            }

            Debug::imprimir(["todo bien"], true);

        }

    }

}