<?php

/**
 * Clase Modelo para s_perfiles
 * 
 * 
 * @package Aplicacion
 * @category Modelo

*/

namespace Jida\Modelos;
use Jida\BD as BD;
use Jida\Helpers\Debug as Debug;
class Perfil extends BD\DataModel{
	
	/**
	* @var int id_perfil 
	*/
	public $id_perfil;
	/**
	* @var varchar perfil 
	*/
	public $perfil;
	/**
	* @var datetime fecha_creado 
	*/
	public $fecha_creado;
	/**
	* @var varchar clave_perfil 
	*/
	public $clave_perfil;
	protected $pk='id_perfil';
	protected $tablaBD='s_perfiles';

	function __construct($id=""){
		parent::__construct($id);
	}
	
	
	function obtAclPerfiles($perfiles){
		$consulta = 
		$this->consulta(['id_perfil','perfil','clave_perfil'])
		->join('s_componentes_perfiles',['id_componente'],['clave'=>'s_perfiles.id_perfil','clave_relacion'=>'s_componentes_perfiles.id_perfil'],'left')
		->join('s_objetos_perfiles',['id_objeto'],['clave'=>'s_perfiles.id_perfil','clave_relacion'=>'s_objetos_perfiles.id_perfil'],'LEFT')
		->join('s_metodos_perfiles',['id_metodo'],['clave'=>'s_perfiles.id_perfil','clave_relacion'=>'s_metodos_perfiles.id_perfil'],'LEFT')
		->in($perfiles,'clave_perfil')
		->obtQuery();
		
		return $this->obt();	
	}
	
}