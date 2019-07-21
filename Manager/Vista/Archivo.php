<?php

namespace Jida\Manager\Vista;

use Exception;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;

Trait Archivo {

    var $urlBase;
    var $urlModulo;
    var $url;

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
                throw new Exception($msj, self::$_ce . 11);
            }

            extract($datos);
            ob_start();

            include_once $archivo;
            $contenido = ob_get_clean();
            $contenido .= $this->jidaJS();
            if (ob_get_length()) {
                ob_end_clean();
            }

            return $contenido;

        }
        catch (Exception $e) {
            Debug::imprimir([
                "Excepcion en Layout::render",
                $e->getCode(),
                $e->getMessage(),
                $e->getTrace()
            ],
                true);
        }

    }

    private function jidaJS() {
        $data = [
            'urls' => [
                'modulo' => Estructura::$urlModulo,
                'base'   => Estructura::$urlBase,
                'actual' => Estructura::$url,
            ],
            'mapa' => [
                'modulo'    => Estructura::$modulo,
                'submodulo' => Estructura::$metodo
            ]
        ];
        return "<script>window.jida=" . json_encode($data) . "</script>";
    }

    /**
     * Permite incluir objetos media
     *
     * Los objetos media se pueden incluir desde el tema implementado en la aplicacion
     * o desde la carpeta htdocs general. Si se quiere incluir algo desde el tema
     * debe usarse la palabra "tema" siguiendo la estructura de url.
     *
     * @param string $archivo
     * @param string $item
     * @param bool $tema
     * @return string
     * @example $this->media('tema/favicon.png')
     *
     */

    function media($archivo = "", $item = "", $tema = true) {

        $argumentos = func_num_args();

        if ($argumentos > 1) return $this->_htdocs($archivo, $item, $tema);

        if (!$archivo) return Estructura::$urlBase . '/htdocs';

        $pos = strpos($archivo, '{tema}');

        if ($pos !== false) {
            $ruta = str_replace('{tema}', Tema::$url, $archivo);
            return $ruta;
        }

        return Estructura::$urlBase . '/htdocs/' . $archivo;

    }

    /**
     * Retorna la url publica de los archivos htdocs de un tema
     * @method htdocs
     *
     * @param $folder
     * @param $item
     * @param bool $tema
     * @return string
     * @deprecated
     * @params string $folder Carpeta a obtener
     * @params string $item nombre del archivo
     * @params boolean $tema Determina si el archivo debe buscarse en el contenido
     * de un tema o en el contenido general.
     */
    function _htdocs($carpeta, $item = "", $tema = true) {

        $path = Estructura::$urlRuta;
        $urlTema = Tema::$url . "/htdocs/";

        $url = $urlTema . $carpeta . '/' . $item;
        if ($tema) {
            return $url;
        }

        return $path . "htdocs/" . $carpeta . '/' . $item;

    }
}