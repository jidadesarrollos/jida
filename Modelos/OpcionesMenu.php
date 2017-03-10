<?php

/**
 * Clase Modelo para s_opciones_menu
 *
 *
 * @package Aplicacion
 * @category Modelo

*/

namespace Jida\Modelos;
use Jida\BD as BD;
class OpcionesMenu extends BD\DataModel{


	/**
	* @var int id_opcion_menu
	*/
	public $id_opcion_menu;
	/**
	* @var varchar id_menu
	*/
	public $id_menu;
	/**
	* @var varchar url_opcion
	*/
	public $url_opcion;
	/**
	* @var varchar nombre_opcion
	*/
	public $nombre_opcion;
	/**
	* @var varchar padre
	*/
	public $padre;
	/**
	* @var varchar hijo
	*/
	public $hijo;
	/**
	* @var varchar orden
	*/
	public $orden;
	/**
	* @var varchar icono
	*/
	public $icono;
	/**
	* @var varchar id_estatus
	*/
	public $id_estatus;

	/**
	* @var varchar selector_icono
	*/
	public $selector_icono;

	protected $pk='id_opcion_menu';
	protected $tablaBD='s_opciones_menu';

	function obtOpciones(){
        $this->consulta(['id_opcion_menu','url_opcion','nombre_opcion','orden']);
        return $this->obt('id_opcion_menu');
    }

}//fin clase