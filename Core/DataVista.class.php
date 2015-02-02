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
    
    function __construct(){
        if(array_key_exists('_CSS', $GLOBALS)) $this->css=$GLOBALS['_CSS'];
        if(array_key_exists('_JS', $GLOBALS)) $this->js=$GLOBALS['_JS'];        
    }
    /**
     * Agrega un javascript para ser renderizado en el layout
     * @method addjs
     * @param mixed $css Arreglo o string con ubicación del js 
     * @param string ambito Usado para agregar el js solo para prod o dev
     */
    function addJs($js){
        
    }
    /**
     * Agrega un css a la hoja de estilo global
     * @method addCss
     * @param mixed $css Arreglo o string con ubicación del css 
     * @param string ambito Usado para agregar css solo para prod o dev
     */
    function addCSS($css,$ambito=""){
        if(is_array($css)){
            foreach ($css as $key => $value) {
                if(!empty($ambito))
                    $this->css[$ambito][]=$value;
                else
                    $this->css[]=$value;
            }            
        }else{
            if(!empty($ambito))
                $this->ccs[$ambito]=$css;
            else
                $this->ccs[]=$css;
        }
        
        
    }
    
}
