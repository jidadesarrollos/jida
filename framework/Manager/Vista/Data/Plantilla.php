<?php

namespace Jida\Manager\Vista\Data;

Trait Plantilla {

    private $_plantilla = null;
    private $_path;

    /**
     * Permite definir una vista para usar fuera del ambito del controlador
     *
     * Este metodo está disponible para vistas estandard que puedan tener un mismo comportamiento en diversos
     * controladores
     * @method setVista
     *
     * @param string $nombreVista Vista a utilizar
     * @param string $path a utilizar opciones disponibles 'app' 'jida' cualquier valor distinto será tomado
     *                            como app
     *
     * @deprecated 0.7
     * @see plantilla
     * @return void
     */
    function usarPlantilla ($nombreVista, $path = "") {

        if ($path == 'jida') {
            $this->_path = "jida";
        }

        $this->_plantilla = $nombreVista;

    }

    /**
     * Define o retorna una plantilla
     * @param null $plantilla
     * @param null $path
     *
     * @return string $_plantilla
     */
    function plantilla ($plantilla = null, $path = null) {

        if ($path == 'jida') {
            $this->_path = 'jida';
        }

        if ($plantilla) {
            $this->_plantilla = $plantilla;
        }

        return $this->_plantilla;

    }
}