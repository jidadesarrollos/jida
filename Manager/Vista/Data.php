<?php

namespace Jida\Manager\Vista;

use Jida\Core\Manager\DataVista;
use Jida\Core\ObjetoManager;
use Jida\Manager\Estructura;
use Jida\Manager\Vista\Data\Meta;
use Jida\Manager\Vista\Data\Plantilla;

class Data {

    use ObjetoManager, Plantilla, Meta;
    private static $data;
    private static $instancia;

    function __construct($data) {

        if (is_object($data)) {
            $this->copiarAtributos($data);
        }

    }

    private static function validarInstancia($data = null) {

        if (!self::$instancia) {
            self::$instancia = new Data($data);
        }

    }

    static function inicializar($data = null) {

        self::validarInstancia($data);

        return self::$instancia;

    }

    /**
     * @return Object Retorna la instancia de un objeto Data
     * @see \Jida\Manager\Vista
     *
     */
    static function obtener($controlador = null) {

        $ControlPadre = 'Jida\Core\Controlador\Control';
        $esData = ($controlador and $controlador instanceof $ControlPadre);

        self::validarInstancia();

        return self::$instancia;

    }

    static function destruir() {
        self::$instancia = false;
    }
}