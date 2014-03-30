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
	
	/**
	 * 
	 */
	var $something;
	
	/**
	 * 
	 */
	function __construct(){
		$this->nombreTabla="s_perfiles";
        $this->clavePrimaria="id_perfil";
	}//final constructor
	
	function obtenerPerfil(){
	    
	}
    
    function obtenerAllPerfiles(){
        $query = "select * form $this->nombreTabla";
        $perfiles = $this->bd->obtenerDataCompleta($query);
        return $perfiles;
        
    }
	
} // END

?>