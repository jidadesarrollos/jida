<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/
namespace Jida;
class SelectorInput extends \Selector{
	/**
	 * Define el tipo de Selector de formulario
	 * @internal El valor por defecto es text
	 * @var string $_tipo
	 * @access private

	 */
	private $_tipo="text";
	/**
	 * Atributos pasados en el constructor
	 * @var mixed $_attr;
	 * @access private
	 */
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
	 */
	function __construct($name,$tipo="text",$attr=[],$items=""){
		$this->_name = $name;
		$this->_tipo = $tipo;
		$this->_attr = $attr;
		$this->_crearSelector();

	}

	private function _crearSelector(){

		switch ($this->_tipo) {
			case 'select':
					$this->_crearSelect();
				break;

			default:
					$this->_crearInput();
				break;
		}

	}

	function _crearSelect(){
		$this->_attr= array_merge($this->_attr,['name'=>$this->_name]);
		parent::__construct($this->_tipo,$this->_attr);
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
