<?php

namespace Jida\Manager\Vista;

use Jida\Core\ObjetoManager;
use Jida\Helpers\Debug;

class Manager {

    use ObjetoManager;

    //    private $_ce = 10006;
    private $_data;

    private $_namespace;
    private $_modulo;
    /**
     * Instancia de objeto Layout
     * @var object $_layout
     * @see Layout
     *
     */
    private $_layout;

    public $Procesador;

    static public $controlador;
    static public $Padre;
    static public $vista;

    function __construct ($padre, $controlador, $data) {

        self::$controlador = $controlador;

        $this->Procesador = $padre->procesador;
        $this->_data = $data;

        self::$Padre = $padre;

        $this->_inicializar();

    }

    /**
     * Procesa la data en el nuevo objeto data
     *
     * Esta funcion es provisoria hasta tanto el objeto dataVista sea reemplazado
     */
    private function _procesarData () {

        Data::inicializar($this->_data);

    }

    private function _inicializar () {

        $this->_procesarData();
        $padre = self::$Padre;

        $this->_namespace = $padre::$namespace;
        $this->_modulo = $padre->modulo;
        $this->_layout = new Layout($this);

    }

    function excepcion () {

    }

    function renderizar () {

        $data = Data::obtener();
        $plantilla = $this->_data->obtPlantilla();

        $vista = $this->vista();
        $archivoVista = (!!$plantilla) ? $vista->rutaPlantilla($plantilla) : $vista->obtener();

        $salida = $this->_layout
            ->leer()
            ->render($archivoVista);

        echo $salida;

    }

    function vista () {

        //Debug::imprimir("ak", true);
        if (!self::$vista) {
            self::$vista = new Vista($this);
        }

        return self::$vista;

    }

}