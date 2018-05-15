<?php


namespace Jida\Manager\Vista;

use Jida\Core\Controller as Controller;
use Jida\Helpers as Helpers;

class Manager {

    use \Jida\Core\ObjetoManager;

    private $_ce = 10006;
    private $_data;

    private $_namespace;
    private $_modulo;
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

        if (!self::$vista) {
            self::$vista = new Vista($this);
        }

        return self::$vista;

    }


}