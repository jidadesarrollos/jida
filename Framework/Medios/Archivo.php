<?php

/**
 * Clase Helper de Arreglos
 *
 * @package Framework
 * @subpackage Helpers
 * @author  Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @category [Helper]
 * @since 0.1
 */

namespace Jida\Medios;

class Archivo {

    /**
     * @var string $extension Extension del archivo
     */
    public $extension;
    /**
     * @var string $_directorio Ruta fisica del archivo
     */
    protected $_directorio;
    private static $_permisos;

    /**
     * @return string
     */
    function directorio() {
        return $this->_directorio;
    }
}