<?php

namespace Jida\Core\Controlador;

use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Medios\Debug;

Trait Url {

    /**
     * Realizar una redireccion
     * @method redireccionar
     *
     * @param $url
     */
    protected function redireccionar($url) {

        $protocolo = parse_url($url, PHP_URL_SCHEME);
        $url = $protocolo ? $url : '//' . Estructura::$urlBase . $url;

        header('location:' . $url . '');
        exit;

    }

    /**
     * Retorna la url de la aplicacion actual
     * @method obtURLApp
     *
     * @deprecated since 0.6.1
     *
     */
    protected function obtURLApp() {

        $url = Estructura::$urlBase;
        if ($this->multiidioma) {

            $idioma = (empty($this->idioma)) ? "" : $this->idioma . "/";
            $url = "$url/$idioma";

        }

        return $url;

    }

    /**
     * Devuelve la estructura de la url solicitada
     * @method obtUrl
     *
     * @param null $ruta
     * @param array $data parametros pasados a la funcion
     * @return string $url
     * @throws \Exception
     * @example  obtUrl('controlador/metodo', ['p1'=> $valor])
     *
     */
    protected function obtUrl($ruta = null, $data = []) {

        $url = Estructura::$url;

        if (!is_string($ruta)) {
            $msj = "El valor de url pasado no es valido";
            Excepcion::procesar($msj, self::$_ce . 1);
        }

        $url .= "/$ruta";
        if (count($data)) {
            $url .= "?" . http_build_query($data);
        }

        return $url;

    }

}