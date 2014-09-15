<?PHP 
/**
 * Clase con funcionalidades generales que permiten al programador hacer tests
 * 
 * 
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category Helpers
 * @version 0.1
 */

 
class Debug{
    
    /**
     * Muestra el contenido de un arreglo envuelto en tag <pre>
     * @method mostrarArray
     */
    static function mostrarArray($ar,$exit=true){
        echo "<pre style=\"background:black;color:#dcdcdc\">";
        print_r($ar);
        echo "</pre>";
        if($exit==TRUE){
            exit;
        }
        
    }
    /**
     * Muestra el contenido de una variable String
     * 
     */
    static function string($content,$exit=false,$tag="hr"){
        echo $content."<$tag/>";
        if($exit==TRUE){
            exit;
        }
        
    }
}
?>