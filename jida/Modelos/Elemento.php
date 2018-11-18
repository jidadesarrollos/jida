<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package Framework
* @version 1.4
* @category Jida Model
*/

namespace Jida\Modelos;
use Jida\BD as BD;
class Elemento extends BD\DataModel{

    #Aqui tus propiedades publicas================
	var $id_elemento;
	var $elemento;
	var $data;
	//var $autocarga;
	var $area;

    #Propiedades Heredadas========================
    protected $tablaBD="t_elementos";
    protected $pk="id_elemento";
    //  protected $unico;

	/**
	 * Registra una nueva area de configuracion
	 *
	 * @method addArea
	 * @since 1.4
	 *
	 */
	static function addArea($config){
		global $elementos;
		array_push($elementos['areas'],$config);

	}

	static function addElemento($elemento){
		global $elementos;
		array_push($elementos['elementos'],\String::upperCamelCase($elemento));
	}


}

#\Debug::string("hola",1);
