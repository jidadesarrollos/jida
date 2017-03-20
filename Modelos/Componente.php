<?php

/**
 * Clase Modelo para s_componentes
 *
 *
 * @package Aplicacion
 * @category Modelo

*/

namespace Jida\Modelos;
use Jida\Helpers\Debug as Debug;
use Jida\BD as BD;
class Componente extends BD\DataModel{


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



	function obtComponentesData(){
		$this->debug = new Debug();

		$data = $this
			->consulta(['id_componente','componente','descripcion descripcion_componente'])
			->join('Jida\Modelos\Objeto',['id_objeto','objeto','descripcion descripcion_objeto'],[],'LEFT')
			->join('Jida\Modelos\Metodo',['id_metodo','metodo','descripcion descripcion_metodo','loggin'],
					['clave'=>'s_objetos.id_objeto','clave_relacion'=>'s_metodos.id_objeto'],'LEFT'
			)->obt('id_metodo');
		return $data;

	}
	
	function obtComponentes(){
		return $this->consulta(['id_componente','componente'])->obt('id_componente');
	}

}//fin clase