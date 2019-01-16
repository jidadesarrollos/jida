<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 11/01/19
 * Time: 03:03 PM
 */

namespace App\Modulos\Medias\Modelos;

use Jida\Core\Modelo;

class Media extends Modelo {

    var $id_media;
    var $url_media;
    var $nombre;
    var $descripcion;
    var $externa;
    var $mime;
    var $id_proyectos;

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