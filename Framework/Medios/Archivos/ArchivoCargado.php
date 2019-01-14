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
    var $error;
    var $extension;

    var $finfo;
    var $mime;
    /**
     * @var bool $cargado Retorna el valor de la funcion is_uploaded_file
     * @see is_uploaded_file();
     */
    var $cargado;

    function __construct($archivo) {

        $this->name = $archivo['name'];
        $this->type = $archivo['type'];
        $this->tmp_name = $archivo['tmp_name'];
        $this->error = $archivo['error'];
        $this->size = $archivo['size'];
        $this->_obtExtension();

        $this->finfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->mime = finfo_file($this->finfo, $this->tmp_name);
        $this->cargado = is_uploaded_file($this->tmp_name);

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
}

