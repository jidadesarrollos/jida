<?php
/**
 * Clase para manejo de base de datos sin un modelo
 * @author Julio Rodriguez
 * @twitter @ark0soner
 */

namespace Jida\BD;

use Exception as Excepcion;

class BD extends ConexionBD {

    private $_ce = '005';
    private $configuracionBD = 'default';
    var $objeto;

    function __construct ($configuracionBD = 'default') {

        $this->configuracionBD = $configuracionBD;
        parent::__construct();
        $this->_inicializarBD();
    }

    private function _inicializarBD () {

        if (empty($this->manejador)) {
            throw new Excepcion("No se ha definido el manejador de base de datos", $this->_ce . '1');

        }
        switch ($this->manejador) {
            case 'PSQL' :
                #include_once 'Psql.class.php';
                $this->objeto = new Psql ($this->configuracionBD);
                break;
            case 'MySQL' :
                #include_once 'Mysql.class.php';
                $this->objeto = new Mysql($this->configuracionBD);
                break;
            default:
                throw new Excepcion("No se ha definido correctamente el manejador de base de datos", 3);
        }
    }

    static function query ($query) {

        $consulta = new BD();

        return $consulta->objeto->obtenerDataCompleta($query);
    }
}
