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
        /**
        * Funcion que recorre y lista todos archivos segun el patron contenido en $expReg 
        *
        * @param string $ruta   # directorio a recorrer 
        * @param string $arr    # arreglo que guarda los archivos recorridos
        * @param string $expReg # expresion regular para filtrar por el nombre del archivo
        * @param string $i  # indice
        
        * @return $arr array con todos los controladores que coincidan con $expReg
        */
        static public function listarDirectoriosRuta($ruta,&$arr,$expReg='',&$i=0){
        // Abrir un directorio y listarlo recursivamente
            if (is_dir($ruta)) {
                if ($directorio = opendir($ruta)) {
                    while (($file = readdir($directorio)) !== false) {
                    // Listamos todo lo que hay en el directorio, mostraría tanto archivos como directorios
                        if(empty($expReg)){
                        // Guardo todos los archivos recorridos
                            $arr[$i] = $file;++$i;
                        }else{
                        // Guardo los archivos que coincidan con la expresion regular
                            $esCoincidencia = (preg_match($expReg,$file))?1:0;
                            if($esCoincidencia){
                               
                                $arr[$i] = $file;++$i;
                            }   
                        }
                        if (is_dir($ruta . $file) && $file!="." && $file!=".."){
                        // Solo si el archivo es un directorio, distinto a "." y ".."
                        // echo "<br>Directorio: $ruta$file<hr>";
                        self::listarDirectoriosRuta($ruta . $file . "/",$arr,$expReg,$i);
                        }
                    }//fin while
                    closedir($directorio);
                }//fin if openRuta
            }else{
                throw new Exception("La ruta a listar no es una ruta valida $ruta", 333);
            }
            return $arr;
        }
} // END

?>

