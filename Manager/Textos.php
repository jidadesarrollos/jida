<?php

namespace Jida\Manager;

use Jida\Medios\Directorios;

class Textos {

    /**
     * Codigo de excepcion para el objeto
     *
     * @var $_ce ;
     */
    static private $_ce = 100016;

    private $_dir = "Textos";
    private $_archivo = "textos.json";

    public $textos = [];

    private static $instancia;

    public function __construct() {
        $this->_inicializar();
    }

    private function _inicializar() {

        $archivo = Estructura::$rutaModulo . DS . $this->_dir . DS . $this->_archivo;

        if (Directorios::validar($archivo)) {

            $contenido = file_get_contents($archivo);
            $this->textos = json_decode($contenido);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $msj = "El archivo ${archivo} no está estructurado correctamente";
                Excepcion::procesar($msj, self::$_ce . 1);
            }

            return $this;

        }

    }

    public static function obtener() {

        if (!self::$instancia) {
            self::$instancia = new Textos();
        }

    }

}