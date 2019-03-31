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
        $this->_layout = new Layout($this, $controlador);
        $this->_data = Data::obtener();
        $this->_inicializar();

    }

    /**
     * Procesa la data en el nuevo objeto data
     *
     * Esta funcion es provisoria hasta tanto el objeto dataVista sea reemplazado
     */
    private function _procesarData() {

        Data::inicializar($this->_data);

    }

    private function _inicializar() {

        $this->_procesarData();
        $this->_layout = new Layout($this);

    }

    function renderizar() {

        $plantilla = $this->_data->plantilla();

        $vista = $this->vista();

        $contenido = $vista->obtener($plantilla);

        $this->_layout->render($contenido);

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