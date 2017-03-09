<?php

/**
 * Clase Modelo para s_menus
 *
 *
 * @package Aplicacion
 * @category Modelo

*/

namespace Jida\Modelos;
use Jida\BD as BD;
class Menus extends BD\DataModel{


	/**
	* @var int id_menu
	*/
	public $id_menu;
	/**
	* @var varchar nombre_menu
	*/
	public $nombre_menu;
	/**
	* @var varchar meta_data
	*/
	public $meta_data;
	protected $pk='id_menu';
	protected $tablaBD='s_menus';

	function obtMenus(){
        $this->consulta(['id_menu','nombre_menu']);
        return $this->obt('id_menu');
    }

}//fin clase