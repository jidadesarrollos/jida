<?PHP 
/**
 * Clase para manejo de Consultas SQL
 * 
 * Permite realizar consultas SQL sin necesidad de que el programador genere el query
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package 
 * @subpackage 
 * @category Modelo
 * @version 1.0
 */

 
class Query{
    
    var $tabla;
    var $campos=array();
    var $in=array();
    var $rango;
        
    function __construct(){
        $this->tabla=$tabla;        
    }
}

?>