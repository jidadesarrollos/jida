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

    var $id_media;
    var $url_media;
    var $nombre;
    var $descripcion;
    var $externa;
    var $mime;
    var $id_proyecto;

    protected $tablaBD = "t_media";
    protected $pk = 'id_media';

}