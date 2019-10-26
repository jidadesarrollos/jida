<?php

namespace Jida\Core\BD;

use Jida\Core\ObjetoManager;
use Jida\Manager\Excepcion;

class Conexion {

    use ObjetoManager;
    protected static $_ce = "004";

    private $id;
    /**
     * @var string $_connection Nombre de la configuracion actual para la conexion
     */
    protected $_connection;
    protected $_manager;
    private static $instancia;
    protected $_config;
    protected $_connector;
    public $connected;

    public function __construct($env) {

        $this->_connection = $env;

        if (!class_exists('\App\Config\BD')) {
            Excepcion::procesar("No existe el objeto De configuracion de Base de datos", $this->_ce . "1");
        }

    }

    private function _config() {
        $this->_config = $config = new \App\Config\BD();
        $this->_manager = $config->manejador;

        if (property_exists($config, $this->_connection)) {
            $this->establecerAtributos($config->{$this->_connection}, $this);
        }
    }

}