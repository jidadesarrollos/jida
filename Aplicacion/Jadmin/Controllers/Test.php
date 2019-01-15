<?php

namespace App\Jadmin\Controllers;

use Jida\Manager\Estructura;
use Jida\Medios\Archivos\ProcesadorCarga;
use Jida\Medios\Debug;
use Jida\Medios\Imagen;

class Test extends Jadmin {

    function gestion() {

        if ($this->post('cargaArchivos')) {

            $archivos = [];

            $procesador = new ProcesadorCarga('cargaArchivo');

            if (!$procesador->validar()) {
                Debug::imprimir(["Hubo error en la carga", $procesador->errores], true);
                // TODO: Manejo de errores
            }
            else {
                $archivos = $procesador->mover(Estructura::$directorio . '/htdocs/test/test5');
            }

            $imagen = new Imagen($archivos[0]);

            $nuevaImagen = $imagen->redimencionar(200, 200);

            Debug::imprimir(["todo bien {$nuevaImagen}"], true);

        }

    }

}