<?php
/**
 * Representa un archivo cargado al servidor
 *
 * User: Isaac
 * Date: 14/1/2019
 * Time: 07:46
 */

namespace Jida\Medios\Archivos;

use Jida\Manager\Excepcion;
use Jida\Medios\Archivo;

class ArchivoCargado extends Archivo {

    private static $_ce = 50001;

    var $name;
    var $type;
    var $size;
    var $tmp_name;
    var $extension;
    var $finfo;

    var $mime;
    /**
     * @var bool $cargado Retorna el valor de la funcion is_uploaded_file
     * @see is_uploaded_file();
     */
    var $cargado;
    const ERRORES = [
        1 => 'UPLOAD_ERR_INI_SIZE',
        2 => 'UPLOAD_ERR_FORM_SIZE',
        6 => 'UPLOAD_ERR_NO_TMP_DIR',
        7 => 'UPLOAD_ERR_CANT_WRITE'
    ];

    private $_error;

    function __construct($archivo) {

        $this->name = $archivo['name'];
        $this->type = $archivo['type'];
        $this->tmp_name = $archivo['tmp_name'];
        $this->_error = $archivo['error'];
        $this->size = $archivo['size'];

        $this->cargado = is_uploaded_file($this->tmp_name);

        if (!$this->_error) {
            $this->finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->mime = finfo_file($this->finfo, $this->tmp_name);
            $this->_obtExtension();
        }

    }

    private function _obtExtension() {

        $explode = explode("/", $this->type);
        $this->extension = $explode[1];
    }

    function validarCarga() {

    }

    function mover($directorio) {

        if (!move_uploaded_file($this->tmp_name, $directorio)) {

            if (!is_writable($directorio)) {
                $msj = "No tiene permisos en la carpeta $directorio";
                Excepcion::procesar($msj, self::$_ce . 001);
            }
            else {
                $msj = "No se pudo mover el archivo cargado $directorio";
                Excepcion::procesar($msj, self::$_ce . 002);
            }
        }

    }

    /**
     * Retorna el error obtenido
     */
    function error() {
        return ['codigo' => $this->_error, 'ERROR' => self::ERRORES[$this->_error]];
    }
}

