<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

class JMenu extends DataModel{
    
    #Aqui tus propiedades publicas================
    
    private $menu;
    private $opciones;
    private $html;
    
    #Propiedades Heredadas========================
    protected $tablaBD="s_menus";
    protected $pk="id_menu";
    //  protected $unico;
    
    
    
    function __construct($id,$objeto=""){
        if(!empty($tabla)){
            if(is_object($tabla)){
                
            }else{
                throw new Exception("El objeto pasado no existe", 1);
                
            }
        }
        parent::__construct();    
    }
    
    
    
  
}
