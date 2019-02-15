<?php

class Tipo extends \Jida\BD\Modelo {

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

    protected $tablaBD = "s_objetos_media";
    protected $pk = 'id_objeto_media';
}