<?php

namespace Jida\Jadmin\Controllers;
use Jida\Helpers as Helpers;
use Jida\Render as Render;
use Jida\RenderHTML as RenderHTML;
use Jida\Modelos as Modelos;
use Exception;

class FormulariosController extends  JController{

	var $preEjecucion = 'validarModelo';
	function __construct(){

		$this->modelo = new Modelos\Formulario();
		$this->layout="jadmin.tpl.php";
		//se llama al constructor luego de instanciar el modelo
		//para que no sea llamado por la clase padre
		parent::__construct();

	}


	function validarModelo($app="app",$form=""){

		if($app=='jida'){
			$this->modelo = new Modelos\JFormulario($form);
		}else{

			$this->modelo = new Modelos\Formulario($form);

		}

	}
	/**
	 * Vista de Formularios de la aplicacion
	 * @method index
	 *
	 */
	function index($app="app"){
		$params = [
			'titulos'=>['','Formulario','Identificador','Clave Primaria']
		];

		if($app=='jida'){
			$vista = new Render\JVista('Jida\Formulario.obtJida',$params);
		}else{
			$vista = new Render\JVista('Jida\Formulario.obtFormulario',$params);

		}

		$vista->accionesFila([
			['span'=>'fa fa-edit','title'=>'Editar','href'=>$this->obtUrl('gestion',[$app,'{clave}'])],
			['span'=>'fa fa-trash','title'=>'Eliminar Formulario','href'=>$this->obtUrl('gestion',[$app,'{clave}'])]
		])->acciones([
		'Nuevo Proyecto' => ['href'=>$this->getUrl('gestion'),'class'=>'btn btn-jida btn-primary']
			]);

		$this->vista = 'formularios';

		$this->dv->vista = $vista->obtenerVista();

	}
	function eliminar($app,$id=""){

	}
	function gestion($app,$id=""){
		$this->vista="jform";
		$tipoForm = 1;
		if(!empty($id)) $tipoForm=2;
		$form = new Modelos\Formulario('Formularios',$tipoForm,$id);
		$form->action = $this->obtUrl('gestion',[$app,$id]);
		if($this->post('btnFormularios')){
			if($form->validarFormulario()){
				if(!$this->modelo->validarConsulta($this->post('query_f')))
				{

					RenderHTML\Formulario::msj('error', $this->tr->cadena('errorQuery','Formularios') ."<hr /> ".$this->post('query_f'));
				}else{
					$this->post('query_f',addslashes($this->post('query_f')));
					if($tipoForm==1)
						$this->post('nombre_identificador',$this->armarIdenficador($this->post('nombre_f')));

					if($this->modelo->salvar($this->post()))
					{

						RenderHTML\Formulario::msj('suceso', $this->tr->cadena('procesoFormFormulario'),$this->obtUrl('gestionCampos',[$app,$id]));
					}else{
						Helpers\Debug::string("algo raro",1);
					}
				}
			}else{
				RenderHTML\Formulario::msj('error', $this->tr->cadena('errorFormFormularios','Formularios'));
			}
		}
		$form->tituloFormulario = $this->tr->cadena('formGestionForms','Formularios');

		$this->dv->formulario = $form->armarFormulario();


	}
	/**
	 * Crea el identificador de un Formulario
	 * @method armarIdentificador
	 * @param string $nombre Nombre del Formulario
	 * @return string Nombre del Formulario en UpperCamelCase
	 */
	private function armarIdentificador($nombre){
		return str_replace(" ","", ucWords(strtolower($nombre)));
	}
	/**
	 * Gestiona los campos de un formulario
	 * @method gestionarCampos
	 */
	function gestionCampos($app,$form=""){

		$datos = $this->modelo->totalCampos();
		$campos = $this->modelo->procesarCampos();
		$this->dv->tipoApp = $app;
		$this->dv->form = $form;
		$this->dv->camposFormulario = $campos;
		if($this->post('btnCamposFormulario')){
			$idCampo = $this->post('id_campo');
			$tipoForm = ($app=='jida')?2:1;
			$form = $this->obtFormularioCampo($idCampo, $tipoForm);
			$this->dv->formCampo = $form->armarFormularioEstructura();
			if($form->validarFormulario())
			{
				if($this->modelo->guardarCampo($this->post()))
				{
					RenderHTML\Formulario::msj('suceso', 'El campo <strong>'.$this->post('name').'</strong> ha sido actualizado exitosamente');

				}

			}
		}
	}
	private function obtFormularioCampo($idCampo,$tipoForm){
		$form = new Modelos\Formulario('CamposFormulario',2,$idCampo,$tipoForm);
		$form->action = "#";
		$form->tipoBoton = "submit";
		$form->valueBotonForm = "Guardar Configuracion";
		return $form;
	}
	/**
	 * Muestra el formulario para la  configuracion de un capo
	 * @method configuracionCampo
	 *
	 */
	function configuracionCampo($app,$form){
		$idCampo  =$this->post('idCampo');
		$tipoForm = ($app=='jida')?2:1;
		$form = $this->obtFormularioCampo($idCampo,$tipoForm);
		$this->dv->formCampo = $form->armarFormularioEstructura();


	}

}
