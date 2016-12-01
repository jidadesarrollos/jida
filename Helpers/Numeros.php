<?PHP 
/**
 * Helper para Numeros
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category
 * @version
 */

namespace Jida\Helpers;
class Numeros{
    
	
	static $moneda = 'Bs.';
	static $separadorMiles = ".";
	static $separadorDecimales = ",";
	static $monedaAntes = FALSE; 
    /**
	 * Devuelve un número en formato de moneda
	 * @method moneda
	 * @param mixed $valor Numero a formatear
	 * @param string $post Texto A agregar posteriormente. 
	 * @param int $decimales
	 * @param string $separador Separador de miles
	 * @param boolean $textPre Define si $post va antes o despues del número
	 
	 * @return string $numero Numero resultante
	 */
	public static function moneda($valor,$decimales=2,$moneda=TRUE){
		
	
		$numero = number_format($valor,$decimales,self::$separadorDecimales,self::$separadorMiles);
		if($moneda){
			if(self::$monedaAntes) return self::$moneda." ".$numero;
			return $numero." ".self::$moneda;
		}
		return $numero;
	}
	
	public static function validarInt($valor){
		return filter_var($valor,FILTER_VALIDATE_INT);
	}
	/**
	 * @deprecated 0.5
	 */
    public static function _moneda($numero,$decimales=2,$type="bolivar"){
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
