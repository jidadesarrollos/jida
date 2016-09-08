<?php

/**
 * Clase Modelo para s_componentes
 *
 *
 * @package Aplicacion
 * @category Modelo

*/
namespace Jida;
use \Datamodel as DataModel;
class Componente extends DataModel{


	/**
	* @var int id_componente
	*/
	public $id_componente;
	/**
	* @var varchar componente
	*/
	public $componente;
	/**
	* @var varchar descripcion
	*/
	public $descripcion;
	protected $pk='id_componente';
	protected $tablaBD='s_componentes';

}//fin clase