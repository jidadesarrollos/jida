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
use \Exception as Exception;
class Form extends  \Selector{

    private $layout;
    var $name;
    var $tagPost="true";
    var $action="";
    var $method="POST";
    var $attr;
	var $labels = TRUE;
	/**
	 * Codigo de excepcion para el objeto
	 *
	 * @var $_ce;
	 */
	private $_ce="1002";
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
	/**
	 * Define el numero de columnas a manejar en el grid
	 *
	 * @internal
	 *
	 * La clase Formulario trabaja con un sistema de columnas, por defecto el de bootstrap, el cual se divide en 12 columnas, sin embargo
	 * estos valores pueden ser modificados por medio de esta variable
	 * @var int $_columnasTotal
	 */
	private $_columnasTotal=12;
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
	 * @internal Registra la estructura agregada en el formulario, ya parseada a las columnas correspondientes.
	 *
	 * @var mixed $_estructura
	 */
	private $_estructura=[];
	/**
	 * Numero total de campos en el Formulario
	 * @var $_totalCampos;
	 */
	private $_totalCampos;

	private $_filaPivote;

	function __construct($form=""){
		if($form){
			$this->_cargarFormulario($form);
		}
		parent::__construct('form');

	}
	/**
	 * Carga el Formulario a mostrar
	 *
	 * @internal Verifica si existe un archivo json para el formulario pedido, carga la informacion del mismo y la procesa.
	 *
	 * Los formularios deben encontrarse en la carpeta formularios de Aplicacion o Framework, caso contrario arrojara excepcion.
	 *
	 * @method _cargarFormulario
	 * @param string $form Nombre del Formulario
	 */
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
	/**
	 * Procesa la estructura del formulario
	 *
	 * @method _procesarEstructura
	 * @access private
	 */
	private function _procesarEstructura(){
		if(!property_exists($this->_configuracion, 'estructura')){

			$estructura = '1x'.$this->_totalCampos;

		}else{
			$estructura = $this->_configuracion->estructura;
			if(empty($estructura))
				$estructura = '1x'.$this->_totalCampos;
		}

		$estructura = explode(";",$estructura);
		for($i=0;$i<=count($estructura)-1;++$i):
			$original = $estructura[$i];
			$columnas=0;
			$partes=[];
			//entra acá si existe definicion de estructura
			if(strpos($estructura[$i], "[")){
				$partes 		= explode("[",$estructura[$i]);
				$columnas 		= array_shift($partes);
				$partes 		= explode("]",implode($partes));
				$distribucion 	= array_shift($partes);
				$partes 		= array_filter($partes);
				$repeticiones 	= str_replace("x", "", implode($partes));
				if(empty($repeticiones)){
					$repeticiones =1;
				}




				if(strpos($distribucion,"x")){
					$partesEstructura = explode(",",$distribucion);
					$estructuraFinal = [];
					foreach ($partesEstructura as $key => $columna) {
						$segmentos = explode("x", $columna);

						if(count($segmentos)>1)
						{
							for($ji=0;$ji<$segmentos[1];$ji++)
								array_push($estructuraFinal,$segmentos[0]);
						}else 	array_push($estructuraFinal,$segmentos[0]);
												}
					$distribucion = implode(",",$estructuraFinal);
				}

			}else{
				if(strpos($estructura[$i], "x")!==FALSE){
					$partes 		= explode("x", $estructura[$i]);
					$columnas 		= array_shift($partes);
					$repeticiones 	= array_shift($partes);
					$partes 		= array_filter($partes);

				}else{
					$columnas 	  	= $estructura[$i];
					$repeticiones 	= 1;

				}
				$columnasGrid = $this->_columnasTotal/$columnas;
				$pivote=0;
				$distribucion=[];
				while($pivote<$this->_columnasTotal){
					array_push($distribucion,$columnasGrid);
					$pivote +=$columnasGrid;
				}
				$distribucion = implode(",",$distribucion);


			}
			if(count(explode(",",$distribucion))<$columnas){
					throw new Exception("La estructura no esta armada correctamente. La distribución es menor a la cantidad de columnas".$distribucion, $this->_ce.'1');

			}
			for($je=0;$je<$repeticiones;$je++)
				$this->_estructura = array_merge($this->_estructura,explode(",",$distribucion));
		endfor;




	}
	private function _instanciarCamposConfiguracion(){

		$this->_totalCampos = count($this->_configuracion->campos);
		if($this->_totalCampos<1){
			throw new Exception("El formulario ".$this->_formulario," no tiene campos registrados", $this->_ce."1");

		}
		foreach ($this->_configuracion->campos as $id => $campo) {
			$this->_campos[$campo->id] = new SelectorInput($campo->name,$campo->type);
			$this->_campos[$campo->id]->configuracion =$campo;
		}


	}
	function armarFormulario(){
		$i=0;

		$columnas=0;
		foreach($this->_campos as $id => $campo){
			$content="";

			if($columnas==0){
				$filaPivote = new Selector('section',['class'=>'row']);

			}

			$columna = $this->_estructura[$i];
			$columnas+=$columna;
			$campo->addClass($this->_cssInput);
			$content .= $campo->render();
			$configuracion = $campo->configuracion;
			$html = str_replace("{{:cols}}", $columna, $this->_plantillaItem);

			if($this->labels){
				$label = new Selector('label',['for'=>$campo->id]);
				$label->innerHTML((property_exists($configuracion, 'label')?$configuracion->label:$configuracion->name));
				$content = $label->render().$content;
			}

			$html = str_replace("{{:contenido}}", $content,$html);
			$filaPivote->addFinal($html);
			if($columnas>=12){
				$columnas=0;
				$this->addFinal($filaPivote->render());
			}
			++$i;
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
