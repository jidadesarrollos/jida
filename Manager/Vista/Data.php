<?php

namespace Jida\Manager\Vista;

use Jida\Core\ObjetoManager;
use Jida\Helpers\Debug;

class Data {

    use ObjetoManager;
    private static $data;
    private static $instancia;


    private function __construct ($data) {

        if (is_object($data)) {
            $this->copiarAtributos($data);
        }

    }

    private static function validarInstancia ($data = null) {

        if (!self::$instancia) {
            self::$instancia = new Data($data);
        }

    }

    static function inicializar ($data) {

        self::validarInstancia($data);

        return self::$instancia;

    }

    /**
     * @return Object Retorna la instancia de un objeto Data
     * @see Jida\Manager\Vista\Data
     * 
     */
    static function obtener () {

        self::validarInstancia();

        return self::$instancia;

    }

    static function destruir () {

        self::$instancia = false;
    }
}