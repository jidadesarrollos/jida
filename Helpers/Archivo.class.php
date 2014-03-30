<?PHP 
/**
 * Helper para manejar Archivos
 *
 * @package Framework
 * @category Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class Archivo {
	
	var $name;
	var $type;
	var $size;
	var $tmp_name;
	var $error;
	var $extension;
	/**
	 * Define si un archivo a sido subido al servidor exitosamente
	 */
	var $cargaRealizada="";
	function __construct($file){
		
		$this->name  = $file['name'];
		$this->type = $file['type'];
		$this->tmp_name = $file['tmp_name'];
		$this->error = $file['error'];
		$this->size = $file['size'];
		$this->obtenerExtension();
		
		if(is_uploaded_file($this->tmp_name)){
			$this->cargaRealizada=TRUE;
		}else{
			$this->cargaRealizada=FALSE;
		}
	}
	
	private function obtenerExtension(){
		$this->extension = explode("/",$this->type)[1];
		
	}
	
	function moverDirectorio($directorio,$nombreArchivo){
		if(move_uploaded_file($nombreArchivo, $directorio)){
			return true;
		}else{
			return false;
		}
		
		
	}
	/**
     * Obtiene un archivo
     * 
     * Usa file_get_contents para devolver el archivo como un string
     */
    static function obtArchivo($rutaArchivo){
        try{
            if(is_readable($rutaArchivo)){
                
            }else{
                throw new Exception("La ruta del archivo no es legible : ".$rutaArchivo);
                
            }
        }catch(Exception $e){
            controlExcepcion($e->getMessage());
        }
    }
    
    /**
     * Crea un archivo 
     * 
     * Genera un archivo con el contenido indicado y lo guarda en la ruta
     * señalada.
     * 
     * @param string $nombreArchivo Nombre del archivo a crear
     * @param string $contenido Contenido del archivo a crear
     * @param string $ruta Ruta donde debe ser guardado el archivo
     */
    static function crearArchivo($nombreArchivo,$contenido,$ruta){
        
    }//final funcion crearArchivo
    
    static function eliminarArchivo($dir){
    	if(unlink($dir)){
    		return true;
    	}else{
    	    throw new Exception("No se puede eliminar el directorio $dir", 1);
			
    		return false;
    	}
    }
    
} // END

?>