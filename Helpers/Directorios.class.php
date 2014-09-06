<?PHP 
/**
 * Helper para manejo de Archivos y directorios
 *
 * @package Framework
 * @category Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class Directorios {
	
	/**
	 * 
	 */
	var $something;
	
	/**
	 * 
	 */
	function __construct(){
		
	}//final constructor
	
	
	
	static function validarExistenciaFile($archivo){
		
	}
    /**
     * Crea un directorio
     * @method crear
     * @param mixed $directorio String o Arreglo de Directorios a crear
     */
    static function crear($directorio,$mode=0777){
        if(is_array($directorio)){
            
        }else{
            if(!file_exists($directorio)){
                mkdir($directorio,$mode,TRUE);
            }
        }
    }
} // END

?>

