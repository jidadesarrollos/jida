<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version 1.3
* @category
*/
namespace Jida;
class ElementoUsado extends \DataModel{

    #Aqui tus propiedades publicas================
	var $id_elemento_usado;
	var $elemento_usado;
	var $data;
	var $identificador;

    #Propiedades Heredadas========================
    protected $tablaBD="t_elementos_usados";
    protected $pk="id_elemento_usado";
    //  protected $unico;


}
