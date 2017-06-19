<?PHP 
/**
 * Helper para manejar Archivos
 *
 * @package Framework
 * @category Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class ArchivoCargado extends Archivo {
	
	var $name;
	var $type;
	var $size;
	var $tmp_name;
	var $error;
	var $extension;
	protected $finfo;
	protected $mime;
	/**
	 * Define si un archivo a sido subido al servidor exitosamente
	 */
	var $cargaRealizada="";
	function __construct($file=""){
		if(!isset($file) or is_array($file)){
    		$this->name  = $file['name'];
            $this->type = $file['type'];
            $this->tmp_name = $file['tmp_name'];
            $this->error = $file['error'];
            $this->size = $file['size'];
            $this->obtenerExtension();
            $this->validarCarga();
			$this->finfo=finfo_open(FILEINFO_MIME_TYPE);
			$this->mime = finfo_file($this->finfo, $this->tmp_name);   
		}
		
	}
	
	function obtMime(){
		return $this->mime;
	}
 /**
     * Verifica la carga de uno o varios archivos
     * @method validarCarga
     */
    function validarCarga(){
        
        $totalCarga = (is_array($this->tmp_name))?count($this->tmp_name):1;
        $archivosCargados = 0;
        if(is_array($this->tmp_name)){
            foreach ($this->tmp_name as $key) {
                
                if(is_uploaded_file($key));
                    ++$archivosCargados;
            }//fin foreach
        }else{
            if(is_uploaded_file($this->tmp_name))
                ++$archivosCargados;
        }
        if($totalCarga==$archivosCargados){
            $this->totalArchivosCargados=$archivosCargados;
            return TRUE;
        }else{
            return false;
        }
        
    }
	
	private function obtenerExtension(){
	    
        if(is_array($this->type)){
            $i=0;
            foreach ($this->type as $key ) {
                $explode= explode("/",$key);
                $this->extension[$i] = $explode[1];
                $i++;
            }
        }else{
          $explode = explode("/",$this->type);
		  $this->extension = $explode[1];
        }
		
	}
    
    
   
	/**
     * Carga un archivo o directorio
     * @method moverDirectorio
     */
    function moverDirectorio($directorio,$nombreArchivo){
        return self::moverArchivo($directorio,$nombreArchivo);
    }
    /**
     * Carga un archivo o directorio
     * @method moverArchivo
     * @param string $directorio Url en la cual se movera el archivo
     * @param mixed $nombreArchivo Archivo a mover
     */
    function moverArchivo($directorio,$nombreArchivo){
		if(move_uploaded_file($nombreArchivo, $directorio)){
			return true;
		}else{
			return false;
		}
		
		
	}
    
    function moverArchivosCargado($directorio,$archivos){
        $bandera=TRUE;
        if(is_array($archivos)){
            foreach ($archivos as $key => $archivo) {
                
                $file = new Archivo($archivo);
                if($this->moverDirectorio($directorio, $file->tmp_name)){
                    continue;
                }else{
                    $bandera=FALSE;
                }
            }//final foreach    
        }else{
            if($this->moverDirectorio($directorio, $archivo->tmp_name)){
                continue;
            }else{
                $bandera=FALSE;
            }
        }
        
        return $bandera;
    }

	/**
     * Obtiene un archivo
     * 
     * Usa file_get_contents para devolver el archivo como un string
     */
    static function obtArchivo($rutaArchivo){
        
        if(is_readable($rutaArchivo)){
            return true;
        }else{
            throw new Exception("La ruta del archivo no es legible : ".$rutaArchivo);
            return false;
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