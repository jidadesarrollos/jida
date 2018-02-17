<?php

namespace Jida\Inicio\Rutas;


use Jida\Helpers as Helpers;
use Jida\Core\Manager as Core;

class Controlador {

    private $_arrayUrl;
    private $_procesador;

    public $default;
    public $controlador = false;
    public $jadmin = false;
    public $metodo = false;
    public $modulo = false;
    public $namespace;
    public $parametros = [];
    public $ruta;
    public $modulos;

    private $_dataVista;
    private $_pagina;

    public function __construct($control) {

        $this->modulos = $control->configuracion->modulos;
        $this->_arrayUrl = $control->arrayUrl;
        $this->_parser();

    }

    private function _parser() {

        $parametro = $this->proximoParametro($this->_arrayUrl);
        if (strtolower($parametro) === 'jadmin') {
            $this->jadmin = true;
        } else {
            $this->reingresarParametro($parametro);
        }
        $this->_procesador = new Procesador($this);
        $this->_procesador->procesar();


    }

    public function proximoParametro() {

        $proximo = array_shift($this->_arrayUrl);

        return $proximo;

    }

    public function reingresarParametro($parametro) {

        array_unshift($this->_arrayUrl, $parametro);

    }

    public function arrayUrl() {

        return $this->_arrayUrl;

    }

    public function ejecutar() {

        if ($this->_validar()) {

            $nombreObj = $this->namespace . $this->controlador;

            $controlador = new $nombreObj;
            call_user_func_array([
                $controlador,
                $this->metodo
            ], $this->parametros);


            $this->_pagina->data = $this->_dataVista;
            $this->_pagina->_namespace = $this->namespace;
            $this->_pagina->_controller = $this->controlador;
            $this->_pagina->_modulo = $this->modulo;
            $this->_pagina->layout = $controlador->layout;
            $this->_pagina->definirDirectorios();
            $this->_pagina->renderizar($controlador->vista);



        }

    }

    private function _validar() {


        $this->_pagina = new Core\Pagina(
            $this->controlador,
            $this->metodo,
            $this->modulo,
            $this->ruta,
            $this->jadmin
        );

        $dataVista =
            new Core\DataVista(
                $this->modulo,
                $this->controlador,
                $this->metodo,
                $this->jadmin);

        $this->_dataVista = $dataVista;

        return true;
    }


}