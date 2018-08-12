<?php

namespace Jida\Core\Controlador;

use Jida\Manager\Estructura;

Trait Url {

    /**
     * Realizar una redireccion
     * @method redireccionar
     */
    protected function redireccionar ($url) {

        header('location:' . $url . '');
        exit;
    }

    /**
     * Retorna la url de la aplicacion actual
     * @method obtURLApp
     * @deprecated since 0.6.1
     *
     */
    protected function obtURLApp () {

        $url = Estructura::$urlBase;
        if ($this->multiidioma) {

            $idioma = (empty($this->idioma)) ? "" : $this->idioma . "/";
            $url . "/" . $idioma;

        }

        return $url;

    }

    /**
     * Devuelve la estructura de la url solicitada
     * @method obtUrl
     *
     * @param string $metodo Nombre del metodo o ruta a solicitar url
     * @param string $data parametros pasados a la funcion
     * @example  obtUrl('controlador/metodo', ['p1'=> $valor])
     *
     * @return string $url
     */
    protected function obtUrl ($ruta = null, $data = []) {

        $url = Estructura::$url;

        if (!is_string($ruta)) {
            throw new \Exception("El valor de url pasado no es valido", self::_ce . 1);
        }

        $url .= "/$ruta";
        if (count($data)) {
            $url .= "?" . http_build_query($data);
        }

        return $url;

    }

}