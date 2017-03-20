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
class Menu extends BD\DataModel{


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

}//fin clase