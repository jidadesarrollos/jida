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
	function __construct($file=""){
		if(!isset($file) or is_array($file)){
    		$this->name  = $file['name'];
            $this->type = $file['type'];
            $this->tmp_name = $file['tmp_name'];
            $this->error = $file['error'];
            $this->size = $file['size'];
            $this->obtenerExtension();
            
            $this->validarCarga();    
		}
		
	}
    /**
     * Verifica la carga de uno o varios archivos
     * @method validarCarga
     */
    private function validarCarga(){
        if(is_array($this->tmp_name)){
            $i=0;
            foreach ($this->tmp_name as $key) {
                if(is_uploaded_file($key)){
                    $this->cargaRealizada[$i]=TRUE;
                    $i++;
                }else{
                    $this->cargaRealizada[$i]=FALSE;
                }
            }//fin foreach
        }else{
            if(is_uploaded_file($this->tmp_name)){
                    $this->cargaRealizada=TRUE;
                }else{
                    $this->cargaRealizada=FALSE;
            }    
        }
        
    }
	
	private function obtenerExtension(){
	    
        if(is_array($this->type)){
            $i=0;
            foreach ($this->type as $key ) {
                $this->extension[$i] = explode("/",$key)[1];
                $i++;
            }
        }else{
		  $this->extension = explode("/",$this->type)[1];
        }
		
	}
    /**
     * Permite redimensionar una imagen sin quitar la calidad de la misma
     * Los ultimos dos parametros son opcionales, si no son pasados la imagen redimensionada
     * reemplazará la imagen actual.
     * @method redimiensionar
     * @param mixed $anchoEsperado Ancho al que se desea redimencionar la imagen
     * @param mixed $altoEsperado Alto al que se desea redimensionar la imagen
     * @param string $rutaImg Ubicacion de imagen a Redimensionar
     * @param string $nombreImg Nombre de la imagen a redimensionar
     * @param string $rutaNuevaImg Ubicacion donde se guardará la nueva imagen
     */
	function redimensionar($anchoEsperado,$altoEsperado,$rutaImg,$nombreImg=null,$rutaNuevaImg=""){
	    
        if(empty($nombreImg) or is_null($nombreImg)){
            $directorioImagen = $rutaImg;
        }else{
            $directorioImagen = $rutaImg.$nombreImg;
        }
        $infoImagen = getimagesize($directorioImagen);
        $anchoActual = $infoImagen[0];
        $altoActual = $infoImagen[1];
        $tipoImagen = $infoImagen['mime'];
        $rutaAguardar = $rutaImg.$nombreImg;
        if(!empty($rutaNuevaImg)){
            $rutaAguardar = $rutaNuevaImg;
                
        }else{
            
        }
        
        
        #Calcular proporciones
        $proporcionActual = $anchoActual/$altoActual;
        $proporcionRedimension = $anchoEsperado/$altoEsperado;
        
        if($proporcionActual>$proporcionRedimension){
            $anchoRedimension=$anchoEsperado;
            $altoRedimension = $anchoEsperado/$proporcionActual;
        }else
        if($proporcionActual<$proporcionRedimension){
            $anchoRedimension = $anchoEsperado*$proporcionActual;
            $altoRedimension = $altoEsperado;
            
        }else{
            $anchoRedimension=$anchoEsperado;
            $altoRedimension=$altoEsperado;
        }
        
        $imagen = $this->crearLienzoImagen($tipoImagen,$rutaImg.$nombreImg);
        $lienzo = imagecreatetruecolor($anchoRedimension, $altoRedimension);
        imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $anchoRedimension, $altoRedimension, $anchoActual, $altoActual);
        if($this->exportarImagen($tipoImagen,$lienzo,$rutaAguardar)){
            return true;    
        }
        
                
	}
    /**
     * Exporta una imagen especificada a una url dada
     * @method exportarImagen
     * @param string $tipoImagen TIPO MIME de la imagen
     * @param image $lienzo imagen a exportar
     * @param string $url Ubicación en la que será alojada la imágen
     * @param string $nombreImagen [opcional] Nombre de la imagen, sino es pasado se asume que el nombre viene incluido en la variable $url 
     */
    function exportarImagen($tipoImagen,$lienzo,$url,$nombreImagen=""){
        if(!empty($nombreImagen)){
            $url=$url.$nombreImagen;
        }
         switch ( $tipoImagen ){
          case "image/jpg":
          case "image/jpeg":
              
            $imagen = imagejpeg($lienzo, $url,90);
            break;
          case "image/png":
            $imagen = imagepng($lienzo, $url,90);
            break;
          case "image/gif":
            $imagen = imagegif($lienzo, $url,90);
            break;
        }
         return true;
    }
    
    
    /**
     * Crea una nueva imagen a partir de un fichero o de una URL
     * Las imagenes pueden ser gift,png o jpg
     * @method crearLienzoImagen
     */
    private function crearLienzoImagen($tipoImagen,$url){
       switch ( $tipoImagen ){
          case "image/jpg":
          case "image/jpeg":
            $imagen = imagecreatefromjpeg( $url );
            break;
          case "image/png":
            $imagen = imagecreatefrompng( $url );
            break;
          case "image/gif":
            $imagen = imagecreatefromgif( $url );
            break;
        }
       return $imagen;
    }
	function moverDirectorio($directorio,$nombreArchivo){
		if(move_uploaded_file($nombreArchivo, $directorio)){
			return true;
		}else{
			return false;
		}
		
		
	}
    
    function moverArchivosCargados($directorio,$archivos){
        $bandera=TRUE;
        foreach ($archivos as $key => $archivo) {
            
            $file = new Archivo($archivo);
            if($this->moverDirectorio($directorio, $file->tmp_name)){
                continue;
            }else{
                $bandera=FALSE;
            }
        }//final foreach
        return $bandera;
    }
    /**
     * Crea una imagen recortada a partir de una imagen dada;
     * @param string $rutaImagen Ruta de la imagen a recortar
     * @param string $rutaNueva Ruta donde se guardará la nueva imagen
     * @param int $alto Pixeles de altura de la imagen a crear
     * @param int $ancho Pixeles de largo de la imagen a crear
     * @param int $x inicio de recorte de eje x de la imagen
     * @param int $y inicio de recorte de eje y de la imagen
     * @param int $w ancho de la nueva imagen
     * @param int $h alto de la nueva imagen 
     * 
     */
    function recortarImagen($rutaImagen,$nuevaRuta,$alto,$ancho,$x,$y,$w,$h){
        if(!file_exists($rutaImagen)){
            throw new Exception("No existe la imagen requerida para recorte $rutaImagen", 2500);
            
        }
        $infoImagen = getimagesize($rutaImagen);
        $lienzo = $this->crearLienzoImagen($infoImagen['mime'], $rutaImagen);
        $nuevaImg = imagecreatetruecolor($ancho,$alto);
        //imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $anchoRedimension, $altoRedimension, $anchoActual, $altoActual);
        imagecopyresampled($nuevaImg,$lienzo,0,0,$x,$y,$ancho,$alto,$w,$h);
        if($this->exportarImagen($infoImagen['mime'], $nuevaImg, $nuevaRuta)){
            return true;
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