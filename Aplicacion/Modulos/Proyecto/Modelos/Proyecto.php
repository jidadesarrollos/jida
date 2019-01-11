<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 11/01/19
 * Time: 03:03 PM
 */

namespace App\Modulos\Proyecto\Modelos;

use Jida\Core\Modelo;

class Proyecto extends Modelo {

    var $id_proyecto;
    var $nombre;
    var $descripcion;
    var $id_categoria;


    protected $tablaBD = "m_proyecto";
    protected $pk = 'id_proyecto';

}