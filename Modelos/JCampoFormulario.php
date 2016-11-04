<?php

/**
 * Clase Modelo para s_campos_f
 *
 *
 * @package Aplicacion
 * @category Modelo

*/

namespace Jida\Modelos;
use Jida\BD as BD;
class JCampoFormulario extends BD\DataModel{


	/**
	* @var int id_campo
	*/
	public $id_campo;
	/**
	* @var int id_form
	*/
	public $id_form;
	/**
	* @var varchar label
	*/
	public $label;
	/**
	* @var varchar name
	*/
	public $name;
	/**
	* @var int maxlength
	*/
	public $maxlength;
	/**
	* @var int size
	*/
	public $size;
	/**
	* @var text eventos
	*/
	public $eventos;
	/**
	* @var int control
	*/
	public $control;
	/**
	* @var text opciones
	*/
	public $opciones;
	/**
	* @var int orden
	*/
	public $orden;
	/**
	* @var varchar id_propiedad
	*/
	public $id_propiedad;
	/**
	* @var varchar placeholder
	*/
	public $placeholder;
	/**
	* @var varchar class
	*/
	public $class;
	/**
	* @var varchar data_atributo
	*/
	public $data_atributo;
	/**
	* @var varchar title
	*/
	public $title;
	/**
	* @var int visibilidad
	*/
	public $visibilidad;
	/**
	* @var varchar ayuda
	*/
	public $ayuda;
	protected $pk='id_campo';
	protected $tablaBD='s_jida_campos_f';
	protected $registroMomentoGuardado=FALSE;
	protected $registroUser=FALSE;
}//fin clase