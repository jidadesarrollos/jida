<?php

/**
 * Clase Modelo para s_metodos
 * 
 * @package Aplicacion
 * @category Modelo
*/

namespace Jida\Modelos;
use Jida\BD as BD;
class Metodo extends BD\DataModel{
	

	/**
	* @var int id_metodo 
	*/
	public $id_metodo;
	/**
	* @var int id_objeto 
	*/
	public $id_objeto;
	/**
	* @var varchar metodo 
	*/
	public $metodo;
	/**
	* @var varchar descripcion 
	*/
	public $descripcion;
	/**
	* @var int loggin 
	*/
	public $loggin;
	public $identificador;
	protected $pk='id_metodo';
	protected $tablaBD='s_metodos';

}//fin clase