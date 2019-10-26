<?php

use Jida\Core\BD\Conexion;

class Mysql extends Conexion {

    /**
     * @var mysqli $_connector mysqli php native object
     */
    protected $_connector;

    function connect() {

        if ($this->_current) return;

        $config = $this->_manager[$this->_connection];

        $this->_connector = @new mysqli(
            $config['servidor'],
            $config['usuario'],
            $config['clave'],
            $config['bd'],
            $config['puerto']
        );

        if ($this->mysqli->connect_error) {
            $this->connected = false;
            $msj = "No se establecido la conexi&oacute;n a base de datos: ";
            Jida\Manager\Excepcion::procesar($msj . $this->mysqli->connect_error,
                self::$_ce . 3);

        }

        $this->connected = true;

        return $this->connected;

    }// end

    function close() {

        if (!$this->connected) return;

        $this->_connector->close();
        $this->connected = false;

    }

}