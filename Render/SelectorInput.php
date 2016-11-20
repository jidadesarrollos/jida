<?php
/**
* Clase para SelectorInput
* @author Julio Rodriguez
* @package
* @version
* @category
*/
namespace Jida\Render;
use Jida\BD\BD as BD;
use Jida\Helpers as Helpers;
class SelectorInput extends Selector{
	use \Jida\Core\ObjetoManager;
	var $name;
	var $type;
	var $id;
	var $label;
	var $opciones;
	/**
	 * Define el tipo de Selector de formulario
	 * @internal El valor por defecto es text
	 * @var string $_tipo
	 * @access private

	 */
	private $_tipo="text";
	/**
	 * Opciones del selector
	 * @internal Posee las opciones a agregar a un control
	 * de selección multiple
	 * @var array $_opciones 
	 */
	private $_opciones;
	/**
	 * Atributos pasados en el constructor
	 * @var mixed $_attr;
	 * @access private
	 */
	 private $_attr=[];
	/**
	 * Items u opciones para agregar en campos pasados por el usuario
	 * @var mixed $_items
	 * @access private
	 */

	/**
	 * Crea Selectores para un formulario
	 * @internal Permite crear y definir selectores HTML para formularios
	 * @param string $tipo Tipo del Selector de Formulario
	 * @param array $attr Arreglo de atributos
	 * @param mixed $items Valores para los selectores de multiseleccion
	 * @method __construct
	 * @example new SelectorInput($name,$tipo="text",$attr=[],$items="")
	 * @example new SelectorInput(Std Class);
	 */
	function __construct(){
		$numero = func_num_args();
		if($numero==1){
			$this->__constructorObject(func_get_arg(0));
		}else{
			call_user_func_array([$this,'__constructorParametros'], func_get_args());
		}

		$this->_crearSelector();

	}

	private function __constructorObject($params){
		$this->establecerAtributos($params,$this);
		$this->_name = $params->name;
		$this->_tipo = $params->type;

	}
	private function __constructorParametros($name,$tipo="text",$attr=[],$items=""){

		$this->_name = $name;
		$this->_tipo = $tipo;

		$this->_attr = is_array($attr)?$attr:[];
	}
	private function _crearSelector(){

		switch ($this->_tipo) {
			case 'select':
					$this->_crearSelect();
				break;
			case 'radio':
					$this->_crearRadio();
			case 'button':

			default:
					$this->_crearInput();
				break;
		}

	}
	/**
	 * Procesa los item a agregar en controles de seleccion
	 *
	 */
	private function obtOpciones(){
		$revisiones = explode(";",$this->opciones);
		
		foreach ($revisiones as $key => $opcion) {
			
			if(stripos($opcion, 'select')!==FALSE){
				
				$data = BD::query($opcion);
				return $data;
				
			}elseif(stripos($opcion,'externo')!==FALSE){
				
			}else{
				$opciones = explode("=",$opcion);
			}
		}
		
	}
	private function _crearSelect(){
		//$this->_attr= array_merge($this->_attr,['name'=>$this->_name]);
		
		parent::__construct($this->_tipo,$this->_attr);
		$this->_attr= array_merge($this->_attr,['type'=>$this->_tipo,'name'=>$this->_name]);
		$options = $this->obtOpciones();
		$optionsHTML ="";	
		foreach ($options as $key => $data) {
			$key = array_keys($data);
			
			$optionsHTML .= Selector::crear('option',['value'=>$data[$key[0]]],$data[$key[1]]);
		}
		
		$this->html($optionsHTML);
		
		
	}
	function _crearInput(){

		$this->_attr= array_merge($this->_attr,['type'=>$this->_tipo,'name'=>$this->_name]);
		parent::__construct('input',$this->_attr);
	}
	/**
	 * Genera un input de texto
	 * @method text
	 */
	function text(){

	}


}
