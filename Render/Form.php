<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

namespace Jida;
use \Selector as Selector;
class Form extends  \Selector{

    private $layout;
    var $name;
    var $tagPost="true";
    var $action="";
    var $method="POST";
    var $attr;
	var $labels = TRUE;

	/**
	 * Estructura html que se implementa por cada item del formulario
	 * @var $_plantillaItem
	 * @access private
	 */
	private $_plantillaItem=
	'
	<section class="col-md-{{:cols}}">
		<div class="form-group">
		{{:contenido}}
		</div>
	</section>
	';

	private $_cssInput="form-control";
	/**
	 * Define la ubicacion del archivo de configuracion del formulario
	 * @var $_path
	 * @access private
	 */
	private $_path;
	/**
	 * Arreglo de campos del formulario
	 * @var array $_campos
	 */
	private $_campos;
	/**
	 * Estructura del formulario
	 *
	 * @var mixed $_estructura
	 */
	private $_estructura;
	/**
	 * Numero total de campos en el Formulario
	 * @var $_totalCampos;
	 */
	private $_totalCampos;

	function __construct($form=""){
		if($form){
			$this->_cargarFormulario($form);
		}
		parent::__construct('form');

	}
	private function _cargarFormulario($form){
		if(\Directorios::validar(DIR_APP . 'formularios/' . strtolower($form) .'.json')){

			$this->_path = DIR_APP . 'formularios/' . $form .'.json';
		}elseif(\Directorios::validar(DIR_FRAMEWORK . 'formularios/' . $form .'.json')){
			$this->_path = DIR_FRAMEWORK . 'formularios/' . $form .'.json';
		}else{
			throw new \Exception("No se consigue el archivo de configuracion del formulario ".$form, 1);

		}

		$this->_configuracion = json_decode(file_get_contents($this->_path));

		$this->_instanciarCamposConfiguracion();
		$this->_procesarEstructura();

	}
	private function _procesarEstructura(){
		if(property_exists($this->_configuracion, 'estructura')){
			$this->_estructura = $this->_configuracion->_estructura;
		}else{
			$this->_estructura = "1x".$this->_totalCampos;
		}
	}
	private function _instanciarCamposConfiguracion(){
		foreach ($this->_configuracion->campos as $id => $campo) {
			$this->_campos[$campo->id] = new SelectorInput($campo->name,$campo->type);
			$this->_campos[$campo->id]->configuracion =$campo;
		}


	}
	function armarFormulario(){

		foreach($this->_campos as $id => $campo){

			$columna = 12;
			$campo->addClass($this->_cssInput);
			$content = $campo->render();
			$configuracion = $campo->configuracion;
			$html = str_replace("{{:cols}}", $columna, $this->_plantillaItem);

			if($this->labels){
				$label = new Selector('label',['for'=>$campo->id]);
				$label->innerHTML((property_exists($configuracion, 'label')?$configuracion->label:$configuracion->name));
				$content = $label->render().$content;
			}
			$html = str_replace("{{:contenido}}", $content,$html);
			$this->addFinal($html);
		}
		return parent::render();

	}
	/**
	 * Realiza lo mismo que la funcion armarFormulario
	 * @internal Se mantiene la funcion para poder realizar la transicion de formularios
	 * usados con la clase Formulario, sin embargo el funcionamiento es el mismo ahora que
	 * el de armarFormulario, por tanto no se aconseja su uso.
	 * @deprecated 1.4
	 */
	function armarFormularioEstructura(){
		$this->armarFormulario();
	}

	function render(){

	}
	function generarInputs($array){

		foreach ($array as $key => $selector) {
			if(is_array($selector)){
				$attr =& $selector; $tipo =& $key;
			}else{
				$nombre = $selector;
			}

			$selector = new SelectorInput($nombre);
			$columna = 12;
			$content = $selector->render();

			$html = str_replace("{{:cols}}", $columna, $this->_plantillaItem);
			$html = str_replace("{{:contenido}}", $content,$html);
			$this->addFinal($html);
		}

	}


}
