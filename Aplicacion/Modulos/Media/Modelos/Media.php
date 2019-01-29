<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 11/01/19
 * Time: 03:03 PM
 */

namespace App\Modulos\Media\Modelos;

use Jida\Core\Modelo;

class Media extends Modelo {

    var $id_objeto_media;
    var $objeto_media;
    var $directorio;
    var $tipo_media;
    var $interno;
    var $descripcion;
    var $leyenda;
    var $alt;
    var $meta_data;
    var $id_idioma;
    var $texto_original;

    protected $tablaBD = "t_medias";
    protected $pk = 'id_media';

    function thumbnail($refH, $refW) {
        $dir = explode("/", $this->url_media);
        $file = array_pop($dir);
        $nameFile = explode(".", $file);
        $dir = implode("/", $dir);
        $nuevoUrl = "{$dir}/{$nameFile[0]}-{$refH}x{$refW}.{$nameFile[1]}";

        return $nuevoUrl;

    }

}