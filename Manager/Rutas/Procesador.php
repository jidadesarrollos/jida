<?php
/**
 *  Procesador de Url parseada
 *
 *
 */

namespace Jida\Manager\Rutas;

use Jida\Manager\Estructura;
use Jida\Medios as Medios;
use Jida\Manager\Rutas\Procesador\Controlador;
use Jida\Manager\Rutas\Procesador\Metodo;
use Jida\Manager\Rutas\Procesador\Modulo;

class Procesador {

    use Modulo, Controlador, Metodo;
    /**
     * @var Arranque $_padre
     */
    protected $_padre;
    protected $_moduloValidado;
    protected $_default = 'Index';
    private static $_ce = 10005;

    function __construct(Arranque $padre) {

        $this->_padre = $padre;
    }

    public function procesar() {

        $this->_moduloValidado = false;

        $this->_modulo();
        $this->_controlador();
        $this->_metodo();
        $this->_argumentos();

        Medios\Debug::imprimir([
            "fin",
            "Ruta Modulo: " . Estructura::$rutaModulo,
            "Modulo: " . Estructura::$modulo,
            "Controlador: " . Estructura::$controlador,
            "Metodo: " . Estructura::$metodo
        ], false);
    }

    private function _argumentos() {

        $parametros = $this->_padre->url->parametros();

        if (is_array($parametros) and $parametros) {

            $parametros = array_filter($parametros,
                function ($valor) {

                    return !empty($valor) or $valor === 0;

                });
            $this->_padre->parametros = $parametros;

        }

    }


}