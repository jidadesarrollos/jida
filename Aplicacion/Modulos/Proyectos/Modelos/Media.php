<?php

namespace App\Modulos\Proyectos\Modelos;

use Jida\BD\DataModel;
use Jida\BD\Modelo;

class Media extends DataModel {

    public $id_media_proyecto;
    public $nombre;
    public $directorio;
    public $tipo_media;
    public $descripcion;
    public $leyenda;
    public $meta_data;
    public $id_idioma;
    public $texto_original;

    protected $tablaBD = "t_media_proyectos";
    protected $pk = 'id_media_proyecto';


}