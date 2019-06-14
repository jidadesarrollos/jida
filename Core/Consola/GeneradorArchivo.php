<?php

namespace Jida\Core\Consola;

use Jida\Manager\Estructura;
use Jida\Medios\Directorios;
use Jida\Medios\Debug;

/**
 * Clase base para crear comandos
 *
 * @author Enyerber Franco <efranco@jidadesarrollos.com>
 * @package Framework
 * @category Console
 *
 */
class GeneradorArchivo {

    public function __construct() {

    }

    public function crearArchivo($variables, $plantilla, $ruta) {

        if (!Directorios::validar($plantilla)) {
            Debug::imprimir(["No existe el archivo $plantilla"], true);
        }

        $content = file_get_contents($plantilla);
        var_dump($variables);
        foreach ($variables as $data => $valor) {

            $content = str_replace("{{{$data}}}", $valor, $content);
        }

        file_put_contents($ruta, $content);

    }

}