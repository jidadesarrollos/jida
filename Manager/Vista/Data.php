<?php

namespace Jida\Manager\Vista;

use Jida\Core\ObjetoManager;
use Jida\Manager\Vista\Data\Meta;
use Jida\Manager\Vista\Data\Plantilla;
use Jida\Medios\Debug;

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
    static function obtener() {

        self::validarInstancia();

        return self::$instancia;

    }

    static function destruir() {
        self::$instancia = false;
    }
}