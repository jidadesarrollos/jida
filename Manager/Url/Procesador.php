<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 30/3/2019
 * Time: 18:55
 */

namespace Jida\Manager\Url;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;

class Procesador {

    private $_conf;
    /**
     * @var object Url Objeto de manejo de la url
     * @see Url
     */
    public $url;
    private $_managerVista;

    function __construct($parametros = []) {

        $this->_conf = Config::obtener();
        $conf = $this->_conf;
        $this->modulos = $conf::$modulos;
        $this->url = Url::obtener();

        $this->_get = $_GET;
        $this->parametros = $parametros;

    }

    private function _inicializar() {

        $pipe = new Pipeline();
        $handlers = isset($this->parametros['handlers']) ? $this->parametros['handlers'] : [];

        if ($handlers) {

            if (is_string($handlers)) $handlers = (array)$handlers;

            foreach ($handlers as $item => $handler) {
                if (!class_exists($handler)) continue;
                $pipe->agregar(new $handler($this->url));
            }

        }

        $pipe->procesar();

        return $this->url;

    }

    function validar() {
        return $this->_inicializar();
    }
}