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
class OpcionMenuPerfil extends BD\DataModel{


	public $id_opcion_menu_perfil;
	public $id_opcion_menu;
	public $id_perfil;

	protected $registroMomentoGuardado=false;
  	protected $registroUser=false;

	protected $pk='id_opcion_menu_perfil';
	protected $tablaBD='s_opciones_menu_perfiles';


}//fin clase