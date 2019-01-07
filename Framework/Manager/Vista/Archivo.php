<?php

namespace Jida\Manager\Vista;

use Jida\Manager\Estructura;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

Trait Archivo {

    /**
     * Obtiene el contenido de un archivo ya parseado y lo retorna en una variable
     *
     * @param $archivo
     * @param array $datos
     * @return string
     *
     */
    private function _obtenerContenido($archivo, $datos = []) {

        try {

            if (!Directorios::validar($archivo)) {
                $msj = "No existe el ${archivo} archivo pasado para obtener contenido";
                throw new \Exception($msj, self::$_ce . 11);
            }

            extract($datos);
            ob_start();

            include_once $archivo;
            $contenido = ob_get_clean();

            if (ob_get_length()) {
                ob_end_clean();
            }


            return $contenido;

        }
        catch (\Exception $e) {
            Debug::imprimir([
                "Excepcion en Layout::render",
                $e->getCode(),
                $e->getMessage(),
                $e->getTrace()
            ],
                true);
        }

    }

    /**
     * Permite incluir objetos media
     *
     * @deprecated
     * @param $folder
     * @param $item
     * @param bool $tema
     * @return string
     */
    function media($folder, $item, $tema = true) {

        return $this->htdocs($folder, $item, $tema);
    }

    /**
     * Retorna la url publica de los archivos htdocs de un tema
     * @method htdocs
     *
     * @params string $folder Carpeta a obtener
     * @params string $item nombre del archivo
     * @params boolean $tema Determina si el archivo debe buscarse en el contenido
     * de un tema o en el contenido general.
     * @param $folder
     * @param $item
     * @param bool $tema
     * @return string
     */

    function htdocs($folder, $item, $tema = true) {

        $path = Estructura::$urlRuta;
        $url = $path . URL_HTDOCS_TEMAS . $this->_tema . '/htdocs/' . $folder . '/' . $item;
        if ($tema) {
            return $url;
        }

        return $path . "htdocs/" . $folder . '/' . $item;
    }
}