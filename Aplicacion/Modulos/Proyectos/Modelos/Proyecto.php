<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 11/01/19
 * Time: 03:03 PM
 */

namespace App\Modulos\Proyectos\Modelos;

use Jida\Core\Modelo;

class Proyecto extends Modelo {

    var $id_proyecto;
    var $nombre;
    var $descripcion;
    var $identificador;
    var $id_categoria;

    protected $tablaBD = "m_proyectos";
    protected $pk      = 'id_proyecto';

}