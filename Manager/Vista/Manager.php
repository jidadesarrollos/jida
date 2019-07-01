<?php

namespace Jida\Manager\Vista;

use Jida\Core\ObjetoManager;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;

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
    static public $vista;

    function __construct($controlador) {

        $this->_controlador = $controlador;
        $this->_data = Data::obtener();
        $this->_inicializar();

    }

    private function _inicializar() {

        $this->_layout = Layout::obtener();
        Data::inicializar($this->_data);

    }

    function renderizar() {

        $plantilla = $this->_data->plantilla();
        $this->_layout->render($this->vista()->obtener($plantilla));

        # agregar instancia de clase Textos::validar para actualizar el arreglo de las traducciones

    }

    function vista() {

        if (!self::$vista) {
            self::$vista = new Vista($this->_controlador);
        }

        return self::$vista;

    }

    function __get($propiedad) {

        if ($propiedad == 'data') {
            return $this->_data;
        }

    }
}