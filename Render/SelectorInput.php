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
	private $_ce = '00101';
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
			$this->_crearOpcionesSelectorMultiple($opciones);
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
			case 'textarea':
				$this->_crearTextArea();
				break;
			default:
				$this->_crearInput();
				break;
		}

	}
	/**
	 * Crea los objetos selector para cada opcion de un selector multiple
	 * @method crearOpcionesSelectorMultiple
	 */
	private function _crearOpcionesSelectorMultiple($opciones){
		
		for($i=0;$i<count($opciones);++$i){
			
			$class = new \stdClass();
			$class->value=array_shift($opciones[$i]);
			$class->labelOpcion=array_shift($opciones[$i]);
			$class->name=$this->name;
			$class->type = $this->type;
			$class->_tipo = $this->type;
			$class->_identif = 'objectSelectorInputInterno';
			$class->id=$this->id."_".($i+1);
			
			$selector = new SelectorInput($class,$this->type);
			array_push($this->_selectoresOpcion,$selector);
		}
	}
	/**
	 * Genera los objeto selector para las opciones de un select
	 * @method crearOpcionesSelect
	 */
	private function _crearOpcionesSelect($options){
		
		foreach ($options as $key => $data) {
			$key = array_keys($data);
			$opcion = new Selector('option',['value'=>$data[$key[0]]]);
			$opcion->innerHTML($data[$key[1]]);
			//$optionsHTML .= Selector::crear('option',,$data[$key[1]]);
			array_push($this->_selectoresOpcion,$opcion);
		}
	}
	/**
	 * Procesa los item a agregar en controles de seleccion
	 *
	 */
	private function obtOpciones(){
		    
		$revisiones = explode(";",$this->opciones);
		foreach ($revisiones as $key => $opcion) {
			if(stripos($opcion, 'select')!==FALSE)
			{	
				$data = BD::query($opcion);
				return $data;
					
			}elseif(stripos($opcion,'externo')!==FALSE){
				continue;
			}else{
				$opciones[] = explode("=",$opcion);
			}
		}
        return $opciones;
		
	}
	private function _crearTextArea(){
		$this->_attr= array_merge($this->_attr,['type'=>$this->_tipo,'name'=>$this->_name]);
		parent::__construct($this->_tipo,$this->_attr);
		
		
	}
	/**
	 * Permite editar las opciones de un selector multiple
	 * 
	 * @internal
	 * 
	 * @method editarOpciones
	 */
	function editarOpciones($opciones,$add=FALSE){
		$this->opciones = $opciones;
		if(!in_array($this->type, $this->_controlesMultiples) and $this->_tipo!='select')
			throw new Exception("El selector ".$this->id." no es un control de seleccion", $this->_ce.'08');
			
		if(!is_array($opciones)){
			$this->opciones = $opciones;
			$opciones  = $this->obtOpciones();
		}
		if(!$add) $this->_selectoresOpcion =[];
		if($this->type=='select'){
			$this->_crearOpcionesSelect($opciones);
		}else{
			$this->_crearOpcionesSelectorMultiple($opciones);
		}
		
		
	}
	/**
	 * Crea un selector Select
	 * @method _crearSelect
	 */
	private function _crearSelect(){
		//$this->_attr= array_merge($this->_attr,['name'=>$this->_name]);
		
		$this->_attr= array_merge($this->_attr,['type'=>$this->_tipo,'name'=>$this->_name]);
		parent::__construct($this->_tipo,$this->_attr);
		$options = $this->obtOpciones();
		$this->_crearOpcionesSelect($options);
	}
    
	function _crearInput(){

		$this->_attr= array_merge($this->_attr,['type'=>$this->_tipo,'name'=>$this->_name]);
		parent::__construct('input',$this->_attr);

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
	private function renderSelect(){
		$options = "";
		foreach ($this->_selectoresOpcion as $key => $option) {
			$options.=$option->render();
		}
		return $this->innerHTML($options)->render(TRUE);
	}
	function render($parent=FALSE){
		
		if(!$parent and in_array($this->_tipo,$this->_controlesMultiples)){
			
			return $this->renderMultiples();
		}elseif(!$parent and $this->_tipo=='select'){
			return $this->renderSelect();
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
