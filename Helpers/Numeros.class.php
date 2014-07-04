<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category
 * @version
 */

 
class Numeros{
    
    
    
    public static function moneda($numero,$decimales=2,$type="bolivar"){
        switch ($type) {
            case 'dolar':
                $numero =($numero=="")?"":number_format($numero,$decimales,".",",");
                return $numero;
                break;
            
            default:
                $numero =($numero=="")?"":number_format($numero,$decimales,",",".");
                return $numero;
                break;
        }
    }
    
    /**
     * Convierte un numero en formato de moneda en el formato correcto para guardarlo en base de datos
     * @method floatBD
     */
    public static function floatBD($valor){
        $valor = str_replace(".","",$valor);
        $valor = str_replace(",",".",$valor);
        return $valor;
    }
    
}


?>