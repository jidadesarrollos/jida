<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package Framework
* @version 1.3
* @category Jida Model
*/

namespace Jida;

class Elemento extends \DataModel{

    #Aqui tus propiedades publicas================
	var $id_elemento;
	var $elemento;
	var $data;
	var $autocarga;
	var $id_usuario_creador;
	var $id_usuario_modificador;

    #Propiedades Heredadas========================
    protected $tablaBD="t_elementos";
    protected $pk="id_elemento";
    //  protected $unico;


}
