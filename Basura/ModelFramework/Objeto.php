<?php

/**
 * Clase Modelo para s_objetos
 * 
 * 
 * @package Aplicacion
 * @category Modelo

*/
namespace Jida;
class Objeto extends \DataModel{
	

	/**
	* @var int id_objeto 
	*/
	public $id_objeto;
	/**
	* @var int id_componente 
	*/
	public $id_componente;
	/**
	* @var varchar objeto 
	*/
	public $objeto;
	/**
	* @var varchar descripcion 
	*/
	public $descripcion;
	protected $pk='id_objeto';
	protected $tablaBD='s_objetos';

}//fin clase