<?php
/**
* Clase Modelo
* @author Julio Rodriguez
* @package
* @version
* @category
*/
class Imagen extends Archivo{
    
    
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
    function redimensionar($anchoEsperado,$altoEsperado,$rutaImg,$rutaNuevaImg=""){
        
        if(empty($nombreImg) or is_null($nombreImg)){
            $directorioImagen = $rutaImg;
        }else{
            $directorioImagen = $rutaImg.$nombreImg;
        }
        $infoImagen = getimagesize($directorioImagen);
        $anchoActual = $infoImagen[0];
        $altoActual = $infoImagen[1];
        $tipoImagen = $infoImagen['mime'];
        $rutaAguardar = $rutaImg;
        if(!empty($rutaNuevaImg)){
            $rutaAguardar = $rutaNuevaImg;
                
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
        
        $imagen = $this->crearLienzo($tipoImagen,$rutaImg);
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
     * @param unknown $tipoImagen
     * @param string $url
     */
    private function crearLienzo($tipoImagen,$url){
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
    function recortar($rutaImagen,$nuevaRuta,$alto,$ancho,$x,$y,$w,$h){
        if(!file_exists($rutaImagen)){
            throw new Exception("No existe la imagen requerida para recorte $rutaImagen", 2500);
            
        }
        $infoImagen = getimagesize($rutaImagen);
        $lienzo = $this->crearLienzo($infoImagen['mime'], $rutaImagen);
        $nuevaImg = imagecreatetruecolor($ancho,$alto);
        //imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $anchoRedimension, $altoRedimension, $anchoActual, $altoActual);
        imagecopyresampled($nuevaImg,$lienzo,0,0,$x,$y,$ancho,$alto,$w,$h);
        if($this->exportarImagen($infoImagen['mime'], $nuevaImg, $nuevaRuta)){
            return true;
        }
    }
        
    /**
     * Verifica que el archivo cargado sea una imagen
     */
    function validarCargaImagen(){
        $arrayMimes = ['image/jpg','image/jpeg','image/png','image/gif'];
        if($this->totalArchivosCargados>=1){
            foreach ($this->type as $key => $mime) {
                if(in_array($mime, $arrayMimes)){
                    return true;
                }
                return false;
            }    
        }else{
          if(in_array($this->type, $arrayMimes)){
                return true;
            }
            return false;
        }
        
    }
}
