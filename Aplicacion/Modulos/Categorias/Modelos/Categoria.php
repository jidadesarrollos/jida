<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 11/01/19
 * Time: 03:03 PM
 */

namespace App\Modulos\Categorias\Modelos;

use Jida\Core\Modelo;

class Categoria extends Modelo {

    var $id_categoria;
    var $nombre;
    var $descripcion;
    var $identificador;

    protected $tablaBD = "m_categorias";
    protected $pk = 'id_categoria';

}