<?php

namespace Jida\Manager\Vista;

use Jida\Core\ObjetoManager;
use Jida\Manager\Estructura;

class Manager {

    use ObjetoManager;

    //    private $_ce = 10006;
    private $_data;

    private $_namespace;
    private $_modulo;
    /**
     * Instancia de objeto Layout
     *
     * @var object $_layout
     * @see Layout
     *
     */
    private $_layout;

    public $Procesador;

    static public $controlador;
    static public $Padre;
    static public $vista;

    function __construct($padre) {

        $this->Procesador = $padre->procesador;
        self::$Padre = $padre;

        $this->_layout = new Layout($this);
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
        $padre = self::$Padre;

        $this->_namespace = Estructura::$namespace;
        $this->_modulo = $padre::$modulo;
        $this->_layout = new Layout($this);

    }

    function excepcion() {

    }

    function renderizar() {

        $plantilla = $this->_data->plantilla();

        $vista = $this->vista();

        $contenido = $vista->obtener($plantilla);

        $this->_layout->render($contenido);

    }

    function vista() {

        if (!self::$vista) {
            self::$vista = new Vista($this->_data);
        }

        return self::$vista;

    }

    function __get($propiedad) {

        if ($propiedad == 'data') {
            return $this->_data;
        }

    }
}