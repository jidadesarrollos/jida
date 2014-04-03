<?PHP
/**
 * String Helper 
 *
 * Clase helper para manejo de Strings
 * 
 * 
 * @package Framework
 * @category Helper
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class String {
	
	/**
	 * Convierte una cadena a formato upperCamelCase
	 * @method upperCamelCase
	 * @param string $cadena
	 * @param boolean $espacios Opcional, si el valor es true deben ser eliminados los espacios en blanco
	 * @return string $upperC
	 **/
	static function upperCamelCase($cadena,$espacios=true){
		$strUpperCase =ucwords($cadena);
		if($espacios===true){
			$strUpperCase = str_replace(" ", "", $strUpperCase);
		} 
		return $strUpperCase;
	}
	 /**
	 * Convierte una cadena a formato lowerCamelCase
	 * @method upperCamelCase
	 * @param string $cadena
	 * @param boolean $espacios Opcional, si el valor es true deben ser eliminados los espacios en blanco
	 * @return string $upperC
	 */
	static function lowerCamelCase($cadena,$espacios=true){
        try{
            if(!empty($cadena)){
                
        		$strLowerCase = self::upperCamelCase($cadena);
                
        		$strLowerCase[0] = strtolower($strLowerCase[0]);
        		return $strLowerCase;
            }else{
                throw new Exception("La cadena " . $cadena ." esta vacia");
                
            }   
        }catch (Exception $e){
            controlExcepcion($e->getMessage());
        }
	}
        /**
     * Función que corta el texto de una vista y coloca tres puntos suspensivos.
     * @param string $texto
     * @param int $tamaño
     * @return string
     */
    
    static function textoVista($texto='',$tamaño=50){
        
        if(strlen($texto)>=$tamaño){
            //echo $texto;exit;
            
            $valorCortado=substr($texto,0,$tamaño);
            
            if(substr_count($valorCortado,' ')>=1){
                
                $posicionReemplazar=strripos($valorCortado,' ');
                        
                $textoFinal=substr_replace($texto,'...',$posicionReemplazar);
                    
                return $textoFinal;
                
            }else{
                
                $posicionBlancoEspacio=strrpos($texto,' ');
                
                $textoFinal=substr_replace($texto,'...',$posicionBlancoEspacio);
                
                return $textoFinal;
                
            }
            //echo $valorCortado;exit;
            
            
        }else{
            
            return $texto;
                
        }
    }
    
    /**
     * Función que se encarga de colocar caracteres del lado izquierdo del texto enviado dependiendo del tamaño establecido.
     * 
     * @param string $texto
     * @param int $tamaño
     * @param string o int $remplazarecho 
     * @return int
     */
    static function rellenarString($texto='',$tamaño=10,$remplazar){
        
        $totalString=strlen($texto);
        
        if($totalString==$tamaño){
            
            return $texto;
            
        }else{
            return str_pad($texto,$tamaño,$remplazar, STR_PAD_LEFT);
        }
    }
    
    /**
     * Generar Hash a Partir de un String
     */
    public static function generarUUID($parametro = null) {
        return hash('sha256', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxy123456789' . $parametro . date('U'));
    }
    
    /**
     * Verifica los caracteres especiales y los cambia por la codificación asci correspondiente
     */
    public static function codificarHTML($cadena){
        $arrAcentos = array(
            'á'=>"&aacute;", 'é'=>'&eacute;',
            'í'=>'&iacute;','ó'=>'&oacute;',
            'ú'=>'&uacute;','Á'=>"&AACUTE;",
            'É'=>'&Eacute;','Í'=>'&Iacute;',
            'Ó'=>'&OACUTE;','Ú'=>'&UACUTE;',
            'Ñ'=>"&Ntilde;", 'ñ'=>"&ntilde;",
            '¿'=>'&iquest;'
        );
        $cadena  = explode(" ",$cadena);
        $arrCadena=array();
        foreach ($cadena as $valor){
            $band = 0;
            foreach ($arrAcentos as $key=>$value){
                if(strpos($valor,$key)!==false){
                    $arrCadena[]=str_replace($key,$value,$valor);
                    $band = 2;
                }else{
                    if($band!=2)
                    $band=1;
                }
            }//fin foreach interno
            if($band==1){
                $arrCadena[]= trim($valor);
            }//final if
        }//fin foreach
        $cadenaFinal=implode(" ",$arrCadena);
        return $cadenaFinal;
    }//fin función
        
} // END 



?>