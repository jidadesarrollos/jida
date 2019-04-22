<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 30/3/2019
 * Time: 09:06
 */

namespace Jida\Manager\Url;

use Jida\Manager\Estructura;
use Jida\Medios\Debug;

class Pipeline {

    private $_listaHandlers = [
        '\\Jida\\Manager\\Url\\Handlers\\Modulo',
        '\\Jida\\Manager\\Url\\Handlers\\Controlador',
        '\\Jida\\Manager\\Url\\Handlers\\Metodo',

    ];
    private $_handlers = [];
    private $_url;
    static private $instancia;
    public $parametros = [];

    function __construct() {
        $this->_url = Url::obtener();

        foreach ($this->_listaHandlers as $handler) {
            $this->_handlers[] = (new $handler($this->_url));
        }

    }

    static function obtener() {

        if (!self::$instancia) self::$instancia = new self();
        return self::$instancia;
    }

    function agregar($handler) {

        if (is_object($handler)) {
            array_unshift($this->_handlers, $handler);
        }

    }

    function procesar() {

        foreach ($this->_handlers as $item => $handler) {

            if (method_exists($handler, 'definir')) {
                //  Debug::imprimir(["processing " . get_class($handler) . ": " . Estructura::$controlador  ,]);

                $handler->procesar();
            }
        }

        $this->procesarParametros();

    }

    private function procesarParametros() {

        $parametros = $this->_url->parametros();
        if (!count($parametros)) {
            return;
        }

        if (is_array($parametros) and $parametros) {

            $parametros = array_filter($parametros,
                function ($valor) {

                    return !empty($valor) or $valor === 0;

                });
            $this->parametros = $parametros;
            Estructura::$parametros = $parametros;

        }

    }
}