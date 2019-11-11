<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 30/3/2019
 * Time: 21:04
 */

namespace Jida\Manager;

use Jida\Manager\Vista;

class Procesador {

    private $_parametros;
    private $_controlador;
    private $_url;

    function __construct($parametros) {
        $this->_parametros = $parametros;
    }

    function ejecutar() {

        $url = new \Jida\Manager\Url\Procesador($this->_parametros);

        $this->_url = $url->validar();
        $this->procesar();

    }

    private function procesar() {

        try {

            $controlador = Estructura::$controlador;
            $this->_controlador = new $controlador;
            call_user_func_array([$this->_controlador, Estructura::$metodo], Estructura::$parametros);
            $vistaManger = new Vista\Manager($this->_controlador);
            $vistaManger->renderizar();

        }
        catch (\Exception $e) {
            Excepcion::validar($e);
        }
        catch (\Error $e) {
            Excepcion::validar($e, 'error');

        }

    }
}