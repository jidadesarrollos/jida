<?php

namespace App\Modulos\Proyectos\Modelos;

use Jida\BD\DataModel;
use Jida\BD\Modelo;

class Objeto extends DataModel {

    var $id_objeto_media;
    var $objeto_media;
    var $directorio;
    var $tipo_media;
    var $descripcion;
    var $leyenda;
    var $meta_data;
    var $id_idioma;
    /**
     * @var string $modulo Modulo al que pertenece el objeto media
     */
    var $modulo;
    /**
     * @var int $id_proyecto id de relacion con el modelo padre de los objetos media
     */
    var $id_relacion;
    var $texto_original;

    protected $tablaBD = "s_objetos_media";
    protected $pk = 'id_objeto_media';

}