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
	 * @method lowerCamelCase
	 * @param string $cadena
	 * @param boolean $espacios Opcional, si el valor es true deben ser eliminados los espacios en blanco
	 * @return string $upperC
	 */
	static function lowerCamelCase($cadena,$espacios=true){
    
        if(!empty($cadena)){
            
    		$strLowerCase = self::upperCamelCase($cadena);
            
    		$strLowerCase[0] = strtolower($strLowerCase[0]);
    		return $strLowerCase;
        }  
    
	}
        /**
     * Función que corta el texto de una vista y coloca tres puntos suspensivos.
     * @param string $texto
     * @param int $tamaño
     * @return string
     */
    
    static function resumen($texto='',$tamaño=50){
        
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
     * @method sha256
     */
    public static function sha256($parametro = null) {
        return hash('sha256', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxy123456789' . $parametro . date('U'));
    }
    /**
     * Codifica el contenido de un array convirtiendo los acentos y caracteres especiales
     * en formato html
     * @method codificarArrayToHTML
     * @param array $array
     */
    public static function codificarArrayToHTML($array){
        
            if(is_array($array)){
                foreach ($array as $key => $value) {
                    $array[$key] = self::codificarHTML($value);
                }
            }
        return $array;
    }
    /**
     * Verifica los caracteres especiales y los cambia por la codificación asci correspondiente
     * @method codificarHTML
     * @param string Cadena
     * @param boolean $inversa Defecto false, si es cambiado a true buscara los valores html para
     * reemplazarlo por el caracter especial
     * 
     */
    public static function codificarHTML($cadena,$inversa=false) {
        $arrAcentos = array (
                'á' => "&aacute;",
                'é' => '&eacute;',
                'í' => '&iacute;',
                'ó' => '&oacute;',
                'ú' => '&uacute;',
                'Á' => "&Aacute;",
                'É' => '&Eacute;',
                'Í' => '&Iacute;',
                'Ó' => '&Oacute;',
                'Ú' => '&Uacute;',
                'Ñ' => "&Ntilde;",
                'ñ' => "&ntilde;",
                '¿' => '&iquest;' 
        );
        if(!empty($cadena) and is_string($cadena)){
            
            
        
        
            $cadena = explode ( " ", $cadena );
            $arrCadena = array ();
            foreach ( $cadena as $valor ) {
                $band = 0;
                foreach ( $arrAcentos as $key => $value ) {
                    if($inversa) {
                           $valorBuscado = $value; $modificador=$key;
                    }
                    else{
                         $valorBuscado = $key; $modificador=$value;
                    }
                    if (strpos ( $valor, $valorBuscado ) !== false) {
                        $valor = str_replace ( $valorBuscado, $modificador, $valor );
                        $band = 2;
                    }
                } // fin foreach interno
                $arrCadena [] = trim ( $valor );
            } // fin foreach
            $cadenaFinal = implode ( " ", $arrCadena );
            return $cadenaFinal;
        }else{
            return $cadena;
        }
    } // fin función
    
    /**
     * Retorna un string en minusculas y seperado por guiones
     * 
     * @method guionCase
     * @param string $string;
     * @return string $string;
     */
    public static function guionCase($string){
        $string = preg_replace('/(\\|\¡|\!|\¿|\?|\/|\_|\'|\"|\*|\[|\]|\{|\}|\=|\+|\.|\$|\n|\t|\r|\&|\´|\(|\))/','', $string);
		$string = self::removerAcentos($string);
		
        return self::removerAcentos(strtolower(str_replace(" ", "-", $string))); 
    }
    /**
     * Modifica un string en formato guionCase a la frase original
     * 
     * @method guionCaseToString
     * @param string $guionCase
     * @return string $str
     */
    public static function guionCaseToString($guionCase){
        $cadena= '/\/\\\'\?\¿\_';
        //$guionCase= str_replace(["¿","?","/"], $replace, $subject)
        preg_replace('/(\\|\¡|\!|\¿|\?|\/|\_|\'|\"|\*|\[|\]|\{|\}|\=|\+|\.|\$|\n|\t|\r|\|&)/','', $guionCase);
        
        return str_replace("-", " ", $guionCase);
    }
    /**
     * Remueve los acentos de un string
     * 
     * Coloca las letras con acentos en su representación sin el mismo
     * @method removerAcentos
     * @param string $cadena Cadena a modificar
     * @param string $enie Valor para modificar la letra Ñ, por defecto es "ni"
     * @return string $string cadena modificada
     */
    public static function removerAcentos($cadena,$enie="ni") {
        $arrAcentos = array (
                'á' => "a",
                'é' => 'e',
                'í' => 'i',
                'ó' => 'o',
                'ú' => 'u',
                'Á' => "A",
                'É' => 'E',
                'Í' => 'I',
                'Ó' => 'O',
                'Ú' => 'U',
                'Ñ' => strtoupper($enie),
                'ñ' => $enie,
                '¿' => '' 
        );
        if(!empty($cadena) and is_string($cadena)){
            $cadena = explode ( " ", $cadena );
            $arrCadena = array ();
            
            foreach ( $cadena as $valor ) {
                $band = 0;
                foreach ( $arrAcentos as $key => $value ) {
                    
                    if (strpos ( $valor, $key ) !== false) {
                        $valor = str_replace ( $key, $value, $valor );
                        $band = 2;
                    }
                } // fin foreach interno
                $arrCadena [] = trim ( $valor );
            } // fin foreach
            $cadenaFinal = implode ( " ", $arrCadena );
            return $cadenaFinal;
        }else{
            return $cadena;
        }
    } // fin función 
     /**
     * Obtiene el singular de una palabra
     * 
     * Esta función trabaja en el lenguaje español principalmente, basandose en las constantes
     * PLURAL_ATONO y PLURAL CONSONANTE, las mismas pueden ser editadas para configurar el funcionamiento
     * del metodo
     * 
     * @method obtenerSingular
     * @param string $palabra Palabra sobre la cual se desea obtener el plural
     * @return string $singular Palabra resultante
     */
    public static function obtenerSingular($palabra){
        $arrayPalabra=array();
        $palabra = preg_split('#([A-Z][^A-Z]*)#', $palabra, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
            foreach ($palabra as $key => $word) {
            	$ultimaLetraSingular = substr($word,strlen($word)-3,1);
				$penultima = substr($word, strlen($word)-2,1);
				$pluralAtono=['í','ú','y','d','r','n','l'];
                if(substr($word, strlen($word)-2)==PLURAL_CONSONANTE 
                	and in_array($ultimaLetraSingular, $pluralAtono)
					
					){
                	
					
                    $arrayPalabra[]=substr($word, 0,strlen($word)-2);
                }elseif(
                	substr($word, strlen($word)-1)==PLURAL_ATONO
                	and !in_array($penultima, ['u'])
				
				){
                    $arrayPalabra[]=substr($word, 0,strlen($word)-1);
                }else{
                    $arrayPalabra[]=$word;
                }
            }
        return implode($arrayPalabra);
    }
    /**
     * Obtiene el plural de una palabra
     * 
     * Esta función se basa en el lenguaje español principalmente, basandose en las constantes
     * PLURAL_ATONO y PLURAL CONSONANTE, las mismas pueden ser editadas para configurar el funcionamiento
     * del metodo
     * 
     * @method obtenerPlural
     * @param string $palabra Palabra sobre la cual se desea obtener el plural
     * @return string $plural Palabra resultante
     */
    public static function obtenerPlural($palabra){
        $vocales = ['a','e','i','o','u'];
        $ultima = substr($palabra,-1);
        if(in_array($ultima, $vocales)){
            return $palabra.PLURAL_ATONO;
        }else{
            return $palabra.PLURAL_CONSONANTE;
        }
        
    }
	/**
	 * Imprime un valor de reemplazo si el string pasado es vacio
	 * 
	 * @method vacio
	 * @param string $string Valor a validar si es vacio
	 * @param string $reemplazo Texto de reemplazo
	 */
	public static function vacio($string,$reemplazo){
		if(empty($string)) return $reemplazo;
		return $string;
	}

} // END
