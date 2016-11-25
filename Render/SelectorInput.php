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
	 * @var string $labelOpcion Label para cada selector multiple radio o inputs
	 */
	var $labelOpcion="";
	var $multiplesInline=TRUE;
	/**
	 * Selectores que requieren de multiples instancias
	 * @var array $_controlesMultiples;
	 */
	private $_controlesMultiples=['checkbox','radio'];
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
	  * Contiene los objetos SelectorInput de cada opcion de un control
	  * de seleccion múltiple
	  * @param array $_selectoresOpcion
	  */
	 private $_selectoresOpcion=[];
	 
	 private $_tplMultiples = 
	 '<div class="{{:type}} {{:type}}-inline">
	    {{:input}}
	    <label for="{{:label}}">
	        {{:label}}
	    </label>
	  </div>
  ';
  
  	/**
	 * Bandera interna que determina si el constructor debe o no llamar al metodo crearSelector
	 * @var boolean $_crear
	 */
  	private $_crear=TRUE;
	/**
	 * Items u opciones para agregar en campos pasados por el usuario
	 * @var mixed $_items
	 * @access private
	 */
	private $_items;
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
		if($numero==1 or ($numero==2 and in_array(func_get_arg(1),$this->_controlesMultiples))){
			if($numero>1)
				$this->__constructorObject(func_get_arg(0),func_get_arg(1));
			else 
				$this->__constructorObject(func_get_arg(0));
		}else{
			call_user_func_array([$this,'__constructorParametros'], func_get_args());
		}
		if($this->_crear)
			$this->_crearSelector();

	}

	private function __constructorObject($params,$type=FALSE){
		$this->establecerAtributos($params,$this);
		$this->_name = $params->name;
		$this->_tipo = $params->type;
		if(!$type and in_array($params->type,['checkbox','radio']))
		{
				
			$this->opciones = $params->opciones;
			$this->_tipo = $params->type; 
			$opciones = $this->obtOpciones();
			
			for($i=0;$i<count($opciones);++$i){
				
				$class = new \stdClass();
				$class->labelOpcion=$opciones[$i][1];
				$class->value=$opciones[$i][0];
				$class->name=$params->name;
				$class->type = $params->type;
				$class->_tipo = $params->type;
				$class->_identif = 'objectSelectorInputInterno';
				$class->id=$params->id."_".($i+1);
				
				$selector = new SelectorInput($class,$params->type);
				array_push($this->_selectoresOpcion,$selector);
			}
			$this->_crear=FALSE;
			//Helpers\Debug::imprimir("es multiple",$params,$opciones,TRUE);
		}
			

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
			    
				$opciones[] = explode("=",$opcion);
                
			}
		}
        return $opciones;
		
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
		#Helpers\Debug::imprimir($optionsHTML);exit;
		$this->innerHTML($optionsHTML);
		
		
	}
    
    private function _crearRadio(){
    	
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
	/**
	 * Imprime los selectores multiples incluidos en $_controlesMultiples
	 * @method renderMultiples
	 */
	private function renderMultiples(){
		$tpl="";
		foreach ($this->_selectoresOpcion as $id => $selector) {
			$input = $selector->render(TRUE);
			
			$data = [
			'input' =>$input,
			'label' => $selector->labelOpcion,
			'type' =>$selector->type,
			
			];
			$tpl.= $this->_obtTemplate($this->_tplMultiples, $data );
			
		}
		
		return $tpl;
	}
	
	function render($parent=FALSE){
		

		if(!$parent and in_array($this->_tipo,$this->_controlesMultiples)){
			
			return $this->renderMultiples();
		}else{
	
			return parent::render();	
		}
	}
	/**
	 * Renderiza el contenido en plantillas predeterminadas
	 * @method _obtTemplate
	 * @param $plantilla;
	 */
	private function _obtTemplate($template,$params){
		foreach ($params as $key => $value) {
			$template = str_replace("{{:".$key."}}", $value,$template);
		}
		return $template;
	}


}
