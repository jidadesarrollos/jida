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
class MotorDePlantillas {

    public function __construct() {

    }

    public function crearArchivoConfigBD($variables) {

        $archivo = __DIR__ . "/plantillas/clase-BD.jida";
        if (!Directorios::validar($archivo)) {
            Debug::imprimir(["no existe el archivo $archivo"], true);
        }

        $content = file_get_contents($archivo);
        foreach ($variables as $data => $valor) {
            $content = str_replace("{{{$data}}}", $valor, $content);
        }

        return $content;

    }

}