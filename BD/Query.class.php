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
    /**
     * @var string $tabla Define nombre de la tabla sobre la cual se realiza la consulta
     */
    private $tabla;
    var $propiedades=array();
    var $in=array();
    var $rango;
    var $query;
    var $manejadorBD;
    var $bd;
    /**
     * Permite identificar si la consulta contiene una clausula where;
     * @var $usoWhere
     */
    private $usoWhere=FALSE;
    /**
     * Consulta construida
     * @var string $consulta
     */
    private $consulta;        
    function __construct($tabla,$propiedades=""){
        $this->tabla=$tabla;
        if(!empty($propiedades)){
            $this->propiedades=$propiedades;
        }
        $this->initBD();
    }
    /**
     * Inicializa el objeto correspondiente para el manejo de la base de datos
     * @method initBD
     */
    private function initBD(){
        if (!defined('manejadorBD')) {
            throw new Exception("No se encuentra definido el manejador de base de datos", 1);
        }
        $this->manejadorBD = manejadorBD;
        switch ($this->manejadorBD) {
            case 'PSQL' :
                include_once 'Psql.class.php';
                $this->bd = new PSQLConexion ($this->configuracionBD);
                break;
            case 'MySQL' :
                $this->bd = new Mysql ();
                break;
        }
    }
    /**
     * Funcion para obtener datos de una tabla
     * @method obt
     * 
     */
    function consulta($campos=""){
        if(empty($campos)){
            $campos = implode(",", array_keys($this->propiedades));
        }
        
        if(is_array($campos)){
            $campos = implode(",",$campos);
        }
        $this->query="SELECT $campos from $this->tabla";
        return $this;
    }
    /**
     * Agrega la clausula where a la consulta
     * @method where
     */
    private function where(){
        if(!$this->usoWhere){
            $this->query.=" where ";
            $this->usoWhere=TRUE;
        }
    }
    /**
     * Permite realizar un filtro de la consulta a realizar
     * @method filtro
     * @param array $arrayFiltro el key es el campo y el value el valor a filtrar
     * 
     */
    function filtro($arrayFiltro=array()){
        $this->where();
       if(is_array($arrayFiltro)){
           $i=0;
           foreach ($arrayFiltro as $key => $value) {
               if($i>0)
                    $this->query.=" and ";
           $this->query.=" $key='$value'";
               ++$i;
           }
       }else{
           throw new Exception("No se ha definido correctamente el filtro", 200);
       }
      return $this;
    }//fin función filtro
    /**
     * Permite hacer una consulta like
     * @method like
     * @param array $filtro
     * @param int $tipo 1=intermedio,2=inicio,3=final
     */
    function like($arrayFiltro,$tipo=1){
        $this->where();
        if(is_array($arrayFiltro)){
           $i=0;
           foreach ($arrayFiltro as $key => $value) {
               if($i>0)
                    $this->query.=" and ";
               $this->query.="$key like";
               switch ($tipo) {
                   case 1:
                        $this->query.=" '%$value%'";
                       break;
                   case 2:
                        $this->query.=" '$value%'";
                        break;
                   case 3:
                       
                        $this->query.=" '%$value'";
                        break;
                }
               ++$i;
           }
        }else{
            throw new Exception("No se ha definido correctamente el filtro", 200);
        }
        return $this;
    }//final función like
    function obt(){
        Debug::string($this->query);
        return $this->bd->obtenerDataCompleta($this->query);
    }
    function fila(){
        return $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery($this->query));
    }
    
}

?>