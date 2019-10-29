<?php

namespace Jida\Manager\Vista;

use Jida\Core\ObjetoManager;

class Manager {

    use ObjetoManager;

    //    private $_ce = 10006;
    private $_data;

    /**
     * Instancia de objeto Layout
     *
     * @var object $_layout
     * @see Layout
     *
     */
    private $_layout;
    private $_controlador;
    private $_error = false;
    static public $vista;

    function __construct($controlador) {

        if (is_a($controlador, '\Exception') or is_a($controlador, '\Error')) {
            $this->_error = $controlador;
        }

        $this->_controlador = $controlador;
        $this->_data = Data::obtener();
        $this->_layout = Layout::obtener();
        Data::inicializar($this->_data);

    }

    function renderizar() {

        if ($this->_error) return $this->_renderizarError();

        $plantilla = $this->_data->plantilla();
        $this->_layout->render($this->vista()->obtener($plantilla));

    }

    private function _renderizarError() {
        /**
         * @var $exception \Exception;
         */
        $exception = $this->_error;

        $this->_data->exception = [
            'error' => $exception->getMessage(),
            'code'  => $exception->getCode(),
            'trace' => $exception->getTrace(),
            'file'  => $exception->getFile(),
            'line'  => $exception->getLine()
        ];

        $vista = $this->vista()->error($this->_error->getCode());

        $this->_layout->render($vista, true);

    }

    function vista() {

        if (!self::$vista)  self::$vista = new Vista($this->_controlador);

        return self::$vista;

    }

    function __get($propiedad) {

        if ($propiedad == 'data') return $this->_data;

    }
}