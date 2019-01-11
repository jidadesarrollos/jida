<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 11/01/19
 * Time: 03:03 PM
 */

namespace App\Modulos\Categoria\Modelos;

use Jida\Core\Modelo;

class Categoria extends Modelo {

    var $id_categoria;
    var $nombre;
    var $descripcion;

    protected $tablaBD = "m_categoria";
    protected $pk = 'id_categoria';

}