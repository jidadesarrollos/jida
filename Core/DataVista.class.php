<?php
/**
* Objeto Data para pasar información a Vistas y Layouts
 * 
 * Objeto pasado por medio del jidaController del Controlador ejecutado
 * a la clase Pagina para que pueda ser accedido en conjunto con los valores
 * pasados desde la vista
 * 
* @author Julio Rodriguez
* @package Framework
 * @subpackage core
* @version 0.1
* @category View
*/

class DataVista{
    /**
     * @var array $css Arreglo Global de archivos javascript a usar por la vista 
     */        
    var $js;
    /**
     * @var array $css Arreglo Global con css a usar por la vista 
     */
    var $css;
    /**
     * @var array $jsAjax Arreglo que registra los js a incluir en llamadas ajax
     */
    var $jsAjax;
    /**
     * Titulo de la Página HTML
     * 
     * Representa la etiqueta TITLE en el HEAD
     * @var string $title
     */
    var $title;
    /**
     * @var string $meta_descripcion Representa la etiqueta meta property description de la página 
     */
    var $meta_descripcion;
    var $meta_autor;
    var $meta_image;
    var $meta_url;
    var $meta = array();
    var $url_canonical;
	var $responsive=TRUE;
    var $robots = TRUE;
    var $solicitudAjax=FALSE;
	var $google_verification=FALSE;
    /**
     * Define una ruta absoluta para el template de la vista a usar, si no se encuentra
     * definida sera usada como vista la vista correspondiente al metodo por defecto o la definida
     * en la propiedad "vista del" controlador
     */
    private $_template="";
    private $_path="app";    
    function __construct(){
        if(array_key_exists('_CSS', $GLOBALS)) $this->css=$GLOBALS['_CSS'];
        if(array_key_exists('_JS', $GLOBALS)) $this->js=$GLOBALS['_JS'];
        if(array_key_exists('_JS_AJAX', $GLOBALS)) $this->jsAjax=$GLOBALS['_JS_AJAX'];
        $this->setMetaBasico();   
    }
    /**
     * Agrega un javascript para ser renderizado en el layout
     * @method addjs
     * @param mixed $js Arreglo o string con ubicación del js
     * @param boolean $ambito TRUE si se desea usar la constante URL_JS como path de ubicación 
     * @param string ambito Usado para agregar el js solo para prod o dev
     */
    function addJs($js,$dir=TRUE,$ambito=""){
        if ($dir===TRUE) $dir=URL_JS;
        if(is_array($js)){
            foreach ($js as $key => $archivo) {
                if(!empty($ambito))
                    $this->js[$ambito][] = $dir.$archivo;
                else 
                    $this->js[]=$dir.$archivo;
            }
        }else{
            if(!empty($ambito))
                    $this->js[$ambito][] = $dir.$js;
            else 
                $this->js[]=$dir.$js;
        }
        return $this;
    }
	/**
	 * Permite agregar archivos JS pertenecientes a un modulo especifico
	 * 
	 * Los archivos js seran buscados dentro de una carpeta htdocs/js del modulo sobre el cual
	 * se encuentre el sistema
	 * @method addJsModulo
	 */
	function addJsModulo($js,$ruta=true){
		$modulo = $GLOBALS['_MODULO_ACTUAL'];
		(String::guionCase($modulo)=='jadmin')?$modulo="framework/":$modulo="aplicacion/modulos/".strtolower($modulo);
		
		if(is_array($js)){
			foreach ($js as $key => $archivo) {
				
				if($ruta)$this->js[]="/".$modulo."/htdocs/js/".$archivo;
				else $this->js[]=$archivo;
			}
		}elseif(is_string($js)){
			if($ruta)$this->js[]="/".$modulo."/htdocs/js/".$js;
				else $this->js[]=$js;
		}
		
	}
	/**
	 * Permite agregar archivos css pertenecientes a un modulo especifico
	 * 
	 * Los archivos css seran buscados dentro de una carpeta htdocs/css del modulo sobre el cual
	 * se encuentre el sistema
	 * @method addcssModulo
	 */
	function addCssModulo($css,$ruta=true){
		$modulo = $GLOBALS['_MODULO_ACTUAL'];
		(String::guionCase($modulo)=='jadmin')?$modulo="framework":$modulo="aplicacion/modulos/".$modulo;
		if(is_array($css)){
			foreach ($css as $key => $archivo) {
				
				if($ruta)$this->css[]="/".$modulo."/htdocs/css/".$css;
				else $this->css[]=$archivo;
			}
		}elseif(is_string($css)){
			if($ruta)$this->css[]="/".$modulo."/htdocs/css/".$css;
				else $this->css[]=$css;
		}
		
	}
    /**
     * Agrega un javascript para ser renderizado en el layout
     * @method addjs
     * @param mixed $js Arreglo o string con ubicación del js
     * @param boolean $ambito TRUE si se desea usar la constante URL_JS como path de ubicación 
     * @param string ambito Usado para agregar el js solo para prod o dev
     */
    function addJsAjax($js,$dir=TRUE,$ambito=""){
        if ($dir===TRUE) $dir=URL_JS;
        if(is_array($js)){
            foreach ($js as $key => $archivo) {
                if(!empty($ambito))
                    $this->jsAjax[$ambito][] = $dir.$archivo;
                else 
                    $this->jsAjax[]=$dir.$archivo;
            }
        }else{
            if(!empty($ambito))
                    $this->js[$ambito][] = $dir.$js;
            else 
                $this->jsAjax[]=$dir.$js;
        }
    }
    
    
    
    /**
     * Agrega un css a la hoja de estilo global
     * @method addCss
     * @param mixed $css Arreglo o string con ubicación del css 
     * @param boolean $ambito TRUE si se desea usar la constante URL_CSS como ubicacion
     * @param string $ambito Usado para agregar css solo para prod o dev
     */
    function addCSS($css,$constante=URL_CSS,$ambito=""){
        
        
            
        if(is_array($css)){
            foreach ($css as $key => $value) {
                if(!empty($ambito))
                    $this->css[$ambito][]=$constante.$value;
                else
                    $this->css[]=$constante.$value;
            }            
        }else{
            if(!empty($ambito))
                $this->css[$ambito][]=$constante.$css;
            else{
                $this->css[]=$constante.$css;
            
            }
        }
        return $this;        
    }
    
    /**
     * Remueve un archivo CCS
     * 
     * Elimina un archivo css de la lista de archivos registrada para inserción en el view.
     * 
     * 
     * @method removerCss
     * @param string $css Nombre del Archivo CSS a eliminar 
     */
    function removerCSS($archivo,$key=null){
       $arrayCss=& $this->css;
       if(!is_null($key) and array_key_exists($key, $this->css)) $arrayCss =& $this->css[$key];
       if(in_array($archivo, $arrayCss) ){
           $key = array_search($archivo,$arrayCss);
           unset($arrayCss[$key]);
           return true;
       }
       return false;
    }
    
    function removerJs(){
        
    }
    /**
     * Permite definir una vista para usar fuera del ambito del controlador
     * 
     * Este metodo está disponible para vistas estandard que puedan tener un mismo comportamiento en diversos
     * controladores
     * @method setVista
     * @param string $nombreVista Vista a utilizar
     * @param string $path a utilizar opciones disponibles 'app' 'jida' cualquier valor distinto será tomado como app
     * @return void
     */
    function setVistaAsTemplate($nombreVista,$path=""){    
        if($path=='jida')
            $this->_path="jida";
        $this->_template = $nombreVista;
        
    }
    
    function getTemplate(){
      
        
        if(!isset($this->_template))
            $this->_template="";
        return $this->_template;
        
    }
    function getPath(){	
        return $this->_path;
    }
    
  
    
    /**
     * Agrega codigo Js al final de la vista, luego de incluir
     * @method addCodeJs
     * @param mixed $arg1 Variable con codigo o Nombre del archivo, si es un archivo debe encontrarse en la misma carpeta
     * de las vistas
     * @param boolean $file Determina si lo pasado es una variable o una url de archivo.
     * 
     */
    function addCodeJs($arg1,$file=false){
       if($file==TRUE)
        $this->js['code'][] = ['archivo'=>$arg1];
       else{
          $this->js['code'][]=['codigo'=>$arg1];
       }
    }
    /**
	 * Permite editar las multiples etiquetas metas de una pagina
	 * @method editarMeta
	 * @param array Arreglo de etiquetas meta, los keys deben coincidir con las meta definidas
	 */
    function editarMeta($array){
    	$this->establecerAtributos($array,__CLASS__);
    }
    private function setMetaBasico(){
        $html = "";
        if(empty($this->meta_descripcion)){
           if(defined('META_DESCRIPCION')) $this->meta_descripcion = META_DESCRIPCION;
        }
        if(empty($this->autor)){
            if(defined('APP_AUTOR'))  $this->meta_autor = APP_AUTOR;
        }
        if(empty($this->image)){
            if(defined('APP_IMAGEN')) $this->image = APP_IMAGEN;
        }
    }
    
	function addMeta($meta){
		$this->meta[]=$meta;
	}   
     /**
     * Establece los atributos de una clase.
     *
     * Valida si los valores pasados en el arreglo corresponden a los atributos de la clase en uso
     * y asigna el valor correspondiente
     * 
     * @access protected
     * @param array @arr Arreglo con valores
     * @param instance @clase Instancia de la clase
     */
    protected function establecerAtributos($arr, $clase="") {
        if(empty($clase)){
            $clase=$this->_clase;
        }
        
        $metodos = get_class_vars($clase);
        foreach($metodos as $k => $valor) {
            
            if (isset($arr[$k])) {
                $this->$k = $arr[$k];
            }
        }
        
    }
    
}
