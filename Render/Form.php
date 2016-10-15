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
use \Exception as Excepcion;
use \Cadenas as Cadenas;
class Form extends  \Selector{

    private $layout;
    var $name;
    var $tagPost 	=TRUE;
    var $action 	="";
    var $method 	="POST";
	var $enctype 	= "application/x-www-form-urlencoded";
	var $target 	="";
	/**
	 * Define si la etiqueta form debe ser integrada
	 * @var boolean $tagForm
	 * @default true
	 */
	var $tagForm=TRUE;
	/**
	 * Define si se agrega un boton submit al formulario
	 * @var boolean $botonEnvio
	 * @default true
	 */
	var $botonEnvio=TRUE;
	/**
	 * Define si se agregan las propiedades para uso del validador js
	 * @var boolean $jidaValidador
	 */
	var $jidaValidador=TRUE;
	/**
	 * Label a usar en el boton de envio por defecto
	 * @var string $_labelBotonEnvio
	 */
	private $_labelBotonEnvio="Guardar";
	private $_numeroExcepciones=5;
	/**
	 * @var string $_id id del formulario
	 * @access private
	 */
	private $_id;
	/**
	 * Define si el formulario lleva labels o no
	 *
	 * @internal Si esta definido en TRUE el formulario busca el valor name en el
	 * json y lo agrega
	 * @var label
	 * @access public
	 */
	var $labels = TRUE;
	/**
	 * Agrega un titulo al formulario
	 * @var Selector $_titulo
	 * @see Selector
	 */
	private $_titulo=FALSE;
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
	private $_plantillaBotones=
	'<section class="row">
		<div class="col-md-12 {{:cssColumnaBotones}}">
			<div class="{{:cssContenedorBotones}}">
				{{:botones}}
			</div>
		</div>
	</section>';
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
	private $_css = [
		'input' 			=> 'form-control',
		'titulo'			=> 'titulo-form',
		'columnaBotones'	=> 'col-md-12 text-right',
		'contenedorBotones'	=> 'btn-group',
		'botonEnvio'		=> 'btn btn-primary',
		'botones'			=> 'btn btn-default'

	];
	private $_cssInput ="form-control";
	private $_cssTitulo='titulo-form';

	private $html;
	/**
	 * Expresion regular para validar estructura
	 * @var regexp $_exprEstructura
	 */
	private $_exprEstructura='/^\d+((\[(\d|,\d|x\d)*\])|x\d|;\d|,\d)*$/';
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
	/**
	 * Arreglo con botones del formulario
	 * @var array $_botones
	 */
	private $_botones;

	function __construct($form=""){
		if($form){
			$this->_cargarFormulario($form);
		}
		debug_backtrace()[1]['function'];

		$this->action = JD('URL_COMPLETA');


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
			throw new Excepcion("No se consigue el archivo de configuracion del formulario ".$form, $this->_ce.'2');

		}

		$this->_configuracion = json_decode(file_get_contents($this->_path));

		$this->_instanciarCamposConfiguracion();
		$this->_configuaricionInicial();
		$this->_procesarEstructura();
	}
	private function _configuaricionInicial(){
		$this->_id =$this->_configuracion->identificador;

		$this->attr([
			'id'	=>'form'.$this->_id,
			'method'=>'POST',
			'name'	=>'form'.$this->_id,
			'role'	=>'form',
			'class'	=>$this->css('form'),
			'target'=>$this->target,
			'enctype'=>$this->enctype
		]);

		$this->_botonEnvio();
	}
	/**
	 * Genera el boton de envio si es requerido
	 */
	private function _botonEnvio(){
		if($this->botonEnvio){
			$id = 'btn'.$this->_id;

			$btn = new Selector('button');
			$btn ->attr(['id'=>$id,'name'=>$id,'type'=>'submit'])
				 ->innerHTML($this->_labelBotonEnvio)
				->addClass($this->css('botonEnvio'));
			if($this->jidaValidador){
				$btn->data('jida','validador');
			}
			$this->_botones['envio'] = $btn;
		}

	}
	/**
	 * Get y Set para css de los componentes del formulario
	 * @method css
	 * @param string $elemento Elemento al que acceder
	 * @param string $css [opcional] Si es pasado, sera asignado como clase css a $elemento
	 * @return mixed Si el metodo es usado como setter retornara el mismo objeto form,
	 * si es usado como getter retornara la clase del elemento si es conseguido, caso contrario
	 * retornara un string vacio
	 */
	function css($elemento,$css=""){
		if(!empty($css)){
			$this->_css[$elemento] = $css;
			return $this;

		}else{
			if(array_key_exists($elemento, $this->_css))
				return $this->_css[$elemento];
		}
		return "";
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
		if(!preg_match($this->_exprEstructura, $estructura))
			throw new Excepcion("La estructura pasada no es valida", $this->_ce.'3');


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
					throw new Excepcion("La estructura no esta armada correctamente. La distribución es menor a la cantidad de columnas".$distribucion, $this->_ce.'1');

			}
			for($je=0;$je<$repeticiones;$je++)
				$this->_estructura = array_merge($this->_estructura,explode(",",$distribucion));
		endfor;

		if(count($this->_estructura)>$this->_totalCampos){
			throw new Excepcion("La estructura tiene mayor cantidad de campos que el formulario ".$this->_configuracion->nombre, $this->_ce.'5');
		}






	}
	private function _instanciarCamposConfiguracion(){

		$this->_totalCampos = count($this->_configuracion->campos);
		if($this->_totalCampos<1){
			throw new Excepcion("El formulario ".$this->_formulario," no tiene campos registrados", $this->_ce."1");

		}
		foreach ($this->_configuracion->campos as $id => $campo) {
			$this->_campos[$campo->id] = new SelectorInput($campo->name,$campo->type);
			if($this->labels){
				$label = new Selector('label',['for'=>$campo->id]);
				$label->innerHTML((property_exists($campo, 'label')?$campo->label:$campo->name));

				$this->_campos[$campo->id]->label = $label;
			}
			if(property_exists($campo, 'eventos') and !empty($campo->eventos)){
				$this->_campos[$campo->id]->data('validacion',json_encode((array)$campo->eventos));
			}
			$this->_campos[$campo->id]->configuracion =$campo;
		}

	}
	/**
	 * Permite acceder a un selector Campo
	 */
	function campo($campo){
		if(array_key_exists($campo, $this->_campos))
			return $this->_campos[$campo];
		else {
			new Excepcion ("No se consigue el campo solicitado: ".$campo,$this->_ce."5");
		}
	}
	/**
	 * Permite agregar un titulo al formulario
	 * @method titulo
	 * @param mixed $titulo Contenido del titulo.
	 * @param string $selector Selector del titulo. por defecto es un h2
	 * @param string $class Clase del Titulo
	 * @return object $this
	 */
	function titulo($titulo,$selector="h2",$class=""){

		if(empty($class)) $class = $this->css('titulo');

		$this->_titulo = new Selector($selector,['class'=>$class]);
		$this->_titulo->innerHTML($titulo);
	}
	/**
	 * Renderiza un formulario
	 *
	 * @internal Genera el HTML de un formulario creado en el Framework, con toda la personalizacion
	 * creada
	 * @method armarFormulario
	 * @param array $titulos
	 *
	 */
	function armarFormulario(){
		$i=0;

		$columnas=0;
		$contenedor = new Selector('article');
		if($this->_titulo)
		{
			$contenedor->addInicio($this->_titulo->render());
		}
		foreach($this->_campos as $id => $campo){
			$content="";

			if($columnas==0){
				$filaPivote = new Selector('section',['class'=>'row']);

			}
			$columna = $this->_estructura[$i];
			$columnas+=$columna;
			$campo->addClass($this->css('input'));
			$content .= $campo->render();
			$configuracion = $campo->configuracion;
			$html = str_replace("{{:cols}}", $columna, $this->_plantillaItem);

			if($campo->label){

				$content = $campo->label->render().$content;
			}

			$html = str_replace("{{:contenido}}", $content,$html);
			$filaPivote->addFinal($html);
			if($columnas>=12){
				$columnas=0;
				if($this->tagForm)
					$this->addFinal($filaPivote->render());
				else {
					$contenedor->addFinal($filaPivote->render());
				}
			}
			++$i;
		}

		if($this->tagForm){

			if($this->botonEnvio)
			{
				$this->addFinal($this->imprimirBotones());

			}
			$contenedor->addFinal(parent::render());
		}


		return $contenedor->render();


	}
	/**
	 * Renderiza el HTML de los botones agregados al formulario
	 * @method imprimirBotones
	 * @param boolean $plantilla true;
	 */
	function imprimirBotones($plantilla=TRUE){
		$botones = "";

		foreach (array_reverse($this->_botones) as $id => $boton) {
			if($boton->attr('class')==""){
				$boton->addClass($this->css('botones'));

			}
			$botones.= $boton->render();
		}
		return $this->_obtTemplate($this->_plantillaBotones, [
		'botones'=>$botones,
		'cssContenedorBotones'=>$this->css('contenedorBotones'),
		'cssColumnaBotones'=>$this->css('columnaBotones')
		]);
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
		$this->armarFormulario();

	}
	/**
	 * Permite configurar botones para el formulario
	 *
	 * @internal Permite acceder a la clase Selector del boton pedido para configurarlo
	 * @param string $boton identificador del Boton.
	 * @param string $label [opcional] Si es pasado sera agregado como label del boton
	 * @return object $selector Objeto Selector
	 * @see Selector
	 * @method boton
	 *
	 *
	 */
	function boton($boton,$label=""){
		if(array_key_exists($boton, $this->_botones)){
			if(!empty($label)) $this->_botones[$boton]->innerHTML($label);
			return $this->_botones[$boton];

		}else{
			$btn = new Selector('button',['type'=>"button","name"=>$boton,"id"=>$boton]);
			$btn->innerHTML($label);
			return $this->_botones[$boton] = $btn;
		}
	}


    /**
     * Crea un mensaje a mostrar en un grid u objeto Tipo Vista
     *
     * Define valores para las variables de sesion __msjVista e __idVista
     * @method msjVista
     * @param string $type Tipo de mensaje, puede ser: success,error,alert,info
     * @param string $msj Contenido del mensaje
     * @param mixed $redirect Por defecto es false, si se desea redireccionar se pasa la url
     */
    static function msj($type,$msj,$redirect=false){
        $msj = Mensajes::crear($type, $msj);
        Session::set('__msjForm',$msj);
        if($redirect){
            redireccionar($redirect);
        }
    }

}
