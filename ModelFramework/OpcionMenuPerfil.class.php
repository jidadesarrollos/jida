<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/

class OpcionMenuPerfil extends DataModel{
    var $id_opcion_menu_perfil;
    var $id_opcion_menu;
    var $id_perfil;
    protected $pk ='id_opcion_menu_perfil';
    protected $tablaBD = 's_opciones_menu_perfiles';
	protected $registroMomentoGuardado=FALSE;
	protected $registroUser=FALSE;
    /**
     * Funcion constructora
     * @method __construct
     */
     
     
    function eliminarAccesos($id_opcion_menu){
    	
        $q = "delete from $this->tablaBD where id_opcion_menu=".$id_opcion_menu;
        $this->bd->ejecutarQuery($q);
        return $this;
        
    }//fin
    
}
