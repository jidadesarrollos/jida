<?php
/**
* Helper para Imagenes
* @author Julio Rodriguez
* @package
* @version
* @category
*/

namespace Jida\Helpers;
class Imagen extends Archivo{


	private $mimes = [
		'image/gif' 					=> 'gif',
		'image/jpeg' 					=> 'jpeg',
		'image/png' 					=> 'png',
		'image/psd' 					=> 'psd',
		'image/bmp' 					=> 'bmp',
		'image/tiff' 					=> 'tiff',
		'image/tiff' 					=> 'tiff',
		'image/jp2' 					=> 'jp2',
		'image/iff' 					=> 'iff',
		'image/xbm' 					=> 'xbm',
		'image/vnd.wap.wbmp' 			=> 'bmp',
		'image/vnd.microsoft.icon' 		=> 'ico',
		'application/x-shockwave-flash' => 'swf'
	];
	
	 private $mimesAceptados=[
	 	'image/gif' => 'gif',
      	'image/jpeg'=> 'jpeg',
      	'image/png'	=> 'png'
	 ];

     /**
      * Información de getimagesize
      */
     private $ancho;
     private $alto;
     private $tipo;
     private $atributos;
     private $peso;

     /**
     * Permite redimensionar una imagen sin quitar la calidad de la misma
     * @internal Los ultimos dos parametros son opcionales, si no son pasados la imagen redimensionada
     * reemplazará la imagen actual.
     * @method redimiensionar
     * @param mixed $anchoEsperado Ancho al que se desea redimencionar la imagen
     * @param mixed $altoEsperado Alto al que se desea redimensionar la imagen
     * @param string $rutaImg Ubicacion de imagen a Redimensionar
     * @param string $nombreImg Nombre de la imagen a redimensionar
     * @param string $rutaNuevaImg Ubicacion donde se guardará la nueva imagen
     */
    function redimensionar($anchoEsperado,$altoEsperado,$rutaImg="",$rutaNuevaImg=""){
        if(empty($rutaImg)) $rutaImg = $this->directorio;
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
            $imagen = imagepng($lienzo, $url,2);
            break;
          case "image/gif":
            $imagen = imagegif($lienzo, $url,90);
            break;
        }
         return true;
    }


    /**
     * Crea una nueva imagen a partir de un fichero o de una URL
     * @internal Las imagenes pueden ser gift,png o jpg
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
     * Crea una imagen recortada a partir de una imagen dada
	 * 
     * @param int $alto Pixeles de altura de la imagen a crear
     * @param int $ancho Pixeles de largo de la imagen a crear
     * @param int $x inicio de recorte de eje x de la imagen
     * @param int $y inicio de recorte de eje y de la imagen
     * @param int $w ancho de la nueva imagen
     * @param int $h alto de la nueva imagen
     * @param string $rutaImagen Ruta de la imagen a recortar
     * @param string $rutaNueva Ruta donde se guardará la nueva imagen
     *
     */
    function recortar($alto,$ancho,$x,$y,$w,$h,$rutaImagen="",$nuevaRuta=""){
        //Debug::string("$alto,$ancho,$x,$y,$w,$h");
        if(empty($rutaImagen))$rutaImagen=$this->directorio;
        else $this->directorio = $rutaImagen;
        if(!$this->validarExistencia())
            throw new Exception("No existe la imagen requerida para recorte $rutaImagen", 2500);
        if(empty($nuevaRuta))$nuevaRuta=$rutaImagen;

        $infoImagen = getimagesize($rutaImagen);
        $lienzo = $this->crearLienzo($infoImagen['mime'], $rutaImagen);
        $nuevaImg = imagecreatetruecolor($ancho,$alto);
        //imagecopyresampled($lienzo, $imagen, 0, 0, 0, 0, $anchoRedimension, $altoRedimension, $anchoActual, $altoActual);
        //bool imagecopyresampled (
        //resource $dst_image , resource $src_image , int $dst_x ,
        // int $dst_y , int $src_x , int $src_y , int $dst_w ,
        //int $dst_h , int $src_w , int $src_h )
        imagecopyresampled($nuevaImg,$lienzo,0,0,$x,$y,$ancho,$alto,$w,$h);

        if($this->exportarImagen($infoImagen['mime'], $nuevaImg, $nuevaRuta)){
            return true;
        }
    }
	/**
	 *
	 */
	function recorte(){
		//$imgDe
	}

    /**
     * Verifica que el archivo cargado sea una imagen
     */
    function validarCargaImagen(){
    	if(function_exists('exif_imagetype'))
    		return $this->useExifImagetype();
    	else
    		return $this->validarImagenFileInfo();
    }
	
	private function validarImagenFileInfo(){

        if($this->totalArchivosCargados>1){

        }else{
          $r = getimagesize( $image );
          return $r[2];
        }
	}
	/**
	 * Verifica si una imagen es valida haciendo uso de la funcion exif_imagetype
	 *
	 * @internal Esta es la función de validacion por defecto, siempre y cuando las librerias
	 * necesarias se encuentren activas en el php.ini
	 * @method useExifImagetype
	 * @access private
	 *
	 */
	private function useExifImagetype(){
		$arrayMimes = [IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF];
        if($this->totalArchivosCargados>1){
            $total = $this->getTotalArchivosCargados();
            $band = true;
            for($i=0;$i<$total;++$i){
            	if(in_array(exif_imagetype($this->files['tmp_name'][$i]),$arrayMimes))
                    $band=true;
                else
                    $band=false;
            }
            return $band;
        }else{

			if(count($this->files)>0){

				$tmpName = is_array($this->files['tmp_name'])?$this->files['tmp_name'][0]:$this->files['tmp_name'];

          		if(!empty($tmpName)){
          			$valor = exif_imagetype($tmpName);
					if(in_array($valor, $arrayMimes)) return true;
				}
          	}
			return false;
        }
	}
	/**
	 * Edita los tipos de mymes aceptados para la carga de imagenes
	 */
	function setMimesAceptados($arr){
		$this->mimesAceptados=$arr;
	}

	function redimension(){}

    static function obtDimensiones($img){
        $size = list($ancho,$alto,$mime,$attr)=getimagesize($img);
        return ['ancho'=>$ancho,'alto'=>$alto,'mime'=>$mime,'attr'=>$attr];

    }
	
	
	/**
     * Crea imagenes recortadas en los distintos formatos a partir de una imagen dada
	 * 
	 * @internal Utiliza por defectos los formatos 1024, 720, 350 y 140. Le asocia un identificador
	 * al final del nombre lg, md, sm, xs para cada formato.
	 * 
     * @param string $ruta Ruta origen de la imagen que se desea redirecciona
     * @param string $nombre Prefijo para las imagenes generadas
	 * @param string $directorio Ruta destino de las imagenes generadas, si no se especifica, las imagenes se generan en la carpeta de origen
     *
     */
	function resize($ruta,$nombre='img',$directorio=false){
		$arr=[];
		$bandera = FALSE;
		
		if(!$directorio)
			$directorio = $ruta;
		else
			Directorios::crear($directorio);
		
		if(preg_match('/\.[jpg|png|jpeg]/', $ruta)){
			$imagenes[] = $ruta;
		}else{
			$imagenes = Directorios::listarDirectoriosRuta($ruta, $arr, '/.*\.[jpg|png|jpeg]/');
			$bandera = TRUE;
		}
		
		foreach ($imagenes as $key => $img){
			$origen = ($bandera)?$ruta.'/'.$img:$img;
			$ext = preg_split('/\./', $img);

			$this->redimensionar(IMG_TAM_LG, IMG_TAM_LG, $origen, $directorio.'/'.$nombre.$key.'-lg.'.$ext[1]);
			$this->redimensionar(IMG_TAM_MD, IMG_TAM_MD, $origen, $directorio.'/'.$nombre.$key.'-md.'.$ext[1]);
			$this->redimensionar(IMG_TAM_SM, IMG_TAM_SM, $origen, $directorio.'/'.$nombre.$key.'-sm.'.$ext[1]);
			$this->redimensionar(IMG_TAM_XS, IMG_TAM_XS, $origen, $directorio.'/'.$nombre.$key.'-xs.'.$ext[1]);
		}
	}
    
    /**
     * Crea una imagen recortada con las dimensiones pasadas por parametro
     * 
     * @internal Asocia un identificador al final del nombre lg, md, sm, xs para cada formato.
     * 
     * @param string $ruta Ruta origen de la imagen que se desea redirecciona
     * @param string $nombre Prefijo para las imagenes generadas
     * @param string $dimAlto Alto de redimension de la imagen
     * @param string $dimAlto Ancho de redimension de la imagen
     * @param string $directorio Ruta destino de las imagenes generadas, si no se especifica, las imagenes se generan en la carpeta de origen
     * @return string nombre de la imagen redimensionada
     */
     
    function resizeImagen($ruta,$nombre='img',$dimAlto,$dimAncho,$directorio=false){
        
        if(!$directorio)
            $directorio = $ruta;
        else
            Directorios::crear($directorio);
        
        $corte = explode('.', $ruta);
        $extensiones = ['jpg'=>'jpg','png'=>'png','jpeg'=>'jpeg'];
        $ext='';
        foreach ($extensiones as $key => $formato){
            $aux=array_search($formato,$corte);
            if($aux){
                $ext=$key;
            }
        }
        
        $this->redimensionar($dimAlto, $dimAncho, $ruta, $directorio.'/'.$nombre.'.'.$ext);

        return $nombre.'.'.$ext[1];
    }

}
