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
    var $js;
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
    var $robots = TRUE;
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
    function addCSS($css,$constante=TRUE,$ambito=""){
        
        if($constante===TRUE) $constante=URL_CSS;
            
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
     * Remueve un css a cargar
     * @method removerCSS
     * @param $pos Key numerica del arreglo
     * @param boolean $ambito En caso de que aplique a css cargados segun el entorno de la app
     */
    function removerCSS($pos,$ambito=FALSE){
        if($ambito){
            unset($GLOBALS[ENTORNO_APP][$pos]);    
        }else{
            
        }
        
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
    
    function setMetaBasico(){
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
    
   
    
    
}
