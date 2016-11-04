<?php
/**
 * Clase Controladora
 * @author Julio Rodriguez
 * @package
 * @version
 * @since 1.4
 * @category Controller
 *
*/

namespace Jida\Jadmin\Controllers;
use Jida\Helpers as Helpers;
use Jida\Elementos as Elementos;
class ElementosController extends JController{
	var $layout="jadmin.tpl.php";
	var $helpers=['Arrays','Cadenas','Debug'];
	function __construct(){
		parent::__construct();
		$this->dv->addJsModulo('/Framework/htdocs/js/jadmin/elementos.js',false);
	}
    function index(){
    	global $elementos;
		$elemento = new Elementos\Elemento();




		if(Helpers\Directorios::validar(DIR_APP."Contenido/elementos.php")){
			include_once 'Contenido/elementos.php';
		}else{
			Helpers\Debug::string(DIR_APP."Contenido/elementos.php");
		}
		$this->dv->areas = $elementos['areas'];

		$this->dv->elementos = $elementos['elementos'];
		$elementosCargados=[];
		$data = $elemento->consulta()->in($this->Arrays->obtenerKey('id',$elementos['areas']),'area')->obt();
		foreach ($data as $key => $elemento) {
			$elementosCargados[$elemento['area']][] = $elemento;
		}
		#Debug::mostrarArray($elementosCargados);
		$this->dv->elementosCargados = $elementosCargados;
    }

	function guardar(){
		if($this->solicitudAjax() and $this->post('btnGuardarElemento')){
			$eleUsado = new Elementos\Elemento();

			$elementoName = str_replace(".", "\\", $this->post('elemento'));
			if($this->post('elemento') and class_exists($elementoName)){

				$elemento = new $elementoName;

				return $this->respuestaJson($elemento->gestion($this->post()));

				// $eleUsado->identificador=$this->post('identificador');
				// $eleUsado->area=$this->post('area');
				// $eleUsado->data=json_encode($post);
				// if($eleUsado->salvar()) $this->respuestaJson(['ejecutado'=>true]);


			}
			$this->respuestaJson(['ejecutado'=>false]);

		}
	}
}



