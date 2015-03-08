<?PHP 
/**
 * Modelo de Perfiles de usuario
 *
 * @package Framework
 * @category Core
 * @author  Julio Rodriguez <jirc48@gmail.com
 * @version 0.1 02/01/2014
 */
class Perfil extends DBContainer{
    var $id_perfil;
    var $perfil;
    var $clave_perfil;
	function __construct($id=""){
		$this->nombreTabla="s_perfiles";
        $this->clavePrimaria="id_perfil";
        $this->unico=array('perfil');
        parent::__construct(__CLASS__,$id);
	}//final constructor
	
	function obtenerPerfil(){
	    
	}
    
    function obtenerAllPerfiles(){
        
        $query = "select * from $this->nombreTabla";
        $perfiles = $this->getTabla();
        
        return $perfiles;
        
    }
	
} // END

?>