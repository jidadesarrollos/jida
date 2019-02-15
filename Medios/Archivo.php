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

use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;

class Archivo {

    private static $_ce = 50003;
    /**
     * @var string $extension Extension del archivo
     */
    public $extension;
    /**
     * @var string $_directorio Ruta fisica del archivo
     */
    protected $_directorio;
    /**
     * @var mixed $nombre nombre del archivo
     */
    var $nombre;
    /**
     * @var mixed|string carpeta donde se encuentra ubicado el archivo
     */
    var $directorio;
    /**
     * @var string ruta fisica del archivo
     */
    var $ruta;
    /**
     * @var boolean $valido Define si el archivo instanciado es valido o no. Es false si el archivo no existe.
     */
    static $valido;
    static $errores;
    private static $_permisos;

    function __construct($directorio = "") {

        if (is_null($directorio)) return;

        $partes = explode("/", $directorio);

        $this->nombre = array_pop($partes);

        $directorio = implode("/", $partes);

        if (strpos(strtolower($directorio), "{base}") !== false) {
            $directorio = str_replace("{base}", Estructura::$directorio, $directorio);
        }

        $this->_directorio = $directorio;
        $this->directorio = $directorio;
        $this->ruta = "{$directorio}/{$this->nombre}";

        if (!file_exists($this->ruta)) {
            $msj = "El archivo {$directorio} que usted indica no existe.";
            self::$valido = false;
            self::$errores[] = $msj;
        }

    }

    /**
     * @return string
     */
    function directorio() {
        return $this->_directorio;
    }
}