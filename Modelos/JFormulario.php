<?php

/**
 * Clase Modelo para s_formularios
 *
 *
 * @package Aplicacion
 * @category Modelo
*/

namespace Jida\Modelos;
use Jida\BD as BD;
class JFormulario extends BD\DataModel{


	/**
	* @var int id_form
	*/
	public $id_form;
	/**
	* @var varchar nombre_f
	*/
	public $nombre_f;
	/**
	* @var text query_f
	*/
	public $query_f;
	/**
	* @var varchar clave_primaria_f
	*/
	public $clave_primaria_f;
	/**
	* @var varchar nombre_identificador
	*/
	public $nombre_identificador;
	/**
	* @var varchar estructura
	*/
	public $estructura;
	protected $pk='id_form';
	protected $tablaBD='s_jida_formularios';

	protected $tieneMuchos= [
		'Campos' =>'JCampoFormulario'
	];
	private $totalCampos;
	private $nombreCampos;
	protected $objetoCampos = "Jida\JCampoFormulario";
	protected $registroMomentoGuardado=FALSE;
	protected $registroUser=FALSE;
	function obtFormulario(){

		$this->consulta(['id_form','nombre_f','nombre_identificador','clave_primaria_f']);
		return $this;
	}
	function obtJida(){

		$this->query(['id_form','nombre_f','nombre_identificador','clave_primaria_f'],'s_jida_formularios');

		return $this;
	}

	function validarConsulta($consulta){
		if($this->bd->ejecutarQuery($consulta))
			return true;
		return false;
	}
	/**
	 * Retorna el total de registros del formulario
	 */
	function totalCampos($query=""){
		if(empty($query)) $query = $this->query_f;
		$this->bd->ejecutarQuery($this->query_f);
		$this->totalCampos = $this->bd->totalField($this->bd->result);
		return $this->totalCampos;
	}
	/**
	 * Procesa los campos de un formulario
	 */
	function procesarCampos(){
		$this->nombreCampos = [];
		$objetoCampo = new $this->objetoCampos();
		$camposExistentes = $objetoCampo->consulta()->filtro(['id_form'=>$this->id_form])->obt();

		//Se crea la estructura de inserción
		for($i=0;$i<$this->totalCampos;++$i)
		{
			$nombreCampo =  $this->bd->obtenerNombreCampo($this->bd->result,$i);
			$this->nombreCampos[$i] =$nombreCampo;
			$campos[$i]=[
				'id_form'		=> $this->id_form,
				'id_propiedad'	=> $nombreCampo,
				'name'			=> $nombreCampo
			];

		}

		foreach ($camposExistentes as $key => $campo) {
			foreach ($campos as $key => $data) {
				//Si ya esta registrado se elimina de la inserción
				if($data['name']==$campo['name']) unset($campos[$key]);
			}
		}
		if(count($campos)>0){
			$ids = $objetoCampo->salvarTodo($campos)->ids();
			for($i=0;$i<count($campos);++$i)
			{
				$campos[$i]['id_campo'] = $ids[$i];
			}
			$camposExistentes = array_merge($camposExistentes,$campos);



		}
		return $camposExistentes;


	}

	function guardarCampo($data){
		$objetoCampo = new $this->objetoCampos($data['id_campo']);
		return $objetoCampo->salvar($data);
	}

}//fin clase