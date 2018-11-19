<?php
/**
 * Procesa las respuestas manejadas por el controlador
 */

namespace Jida\Core\Controlador;

use Jida\Manager\Excepcion;

Trait Respuesta {

    /**
     * Devuelve contenido para una solicitud via ajax
     *
     * Imprime la respuesta de la solicitud realizada sin esperar llegar a la vista
     *
     * @param mixed $respuesta Respuesta de la solicitud ajax
     *
     */
    protected function respuestaAjax ($respuesta) {

        echo $respuesta;
        exit;
    }

    protected function respuestaJson ($respuesta) {

        exit(json_encode($respuesta));
        exit;
    }

    /**
     * Genera una excepción 404.
     */
    protected function _404 () {

        $msj = "No se consigue el enlace solicitado";
        Excepcion::procesar($msj, 404);

    }
}