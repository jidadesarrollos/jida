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
                return number_format($numero,$decimales,".",",");
                break;
            
            default:
                return number_format($numero,$decimales,",",".");
                break;
        }
    }
    
}


?>