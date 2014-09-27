<?PHP 
/**
 * Representa un objeto Result de Base de datos
 * 
 * Permite acceder y manejar la matriz resultado de una consulta a base de datos
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package 
 * @subpackage 
 * @category Modelo
 * @version 1.0
 */

 
class ResultBD{
    /**
     * @var object $bd Objeto Instanciado manejador de base de datos
     */
    private $bd;
    /**
     * @var $result Resultado obtenido de la consulta de base de datos
     */
    private $result ; 
    function __construct($result){
        
        $this->result=$result;
    }
    
    function getData(){
        return $this->result;
    }
}

?>