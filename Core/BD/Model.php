<?php

namespace Jida\Core\BD;

use Jida\BD\Mysql;

class Model {

    protected $table;
    protected $pk;
    protected $tablaBD;
    private $keepConnect = true;
    /**
     * @var Nombre de la clase
     */
    private $_class;

    /**
     * @var \ReflectionClass $_reflector
     */
    private $_reflector;

    protected $_conection = 'default';

    function __construct($id = null) {

        if ($id !== null) $this->_connect();

        $this->_reflector = new \ReflectionClass(get_class($this));
        $this->_class = get_class($this);

    }

    private function _connect() {

        $this->_db = new Mysql($this->_conection);
    }

}