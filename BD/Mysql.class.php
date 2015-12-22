<?PHP 
/**
 * Clase para RDBMS MySQL
 * 
 * Emula todas las funcionalidades requeridas por el framework y usadas con
 * los manejadores como postgres para que pueda trabajar con MySQL de forma
 * transparente para el programador.
 * 
 * Hace uso de la API de PHP Mysqli
 * 
 * @author Julio Rodriguez <jirc48@hotmail.com>
 * @version 1.4 03/04/2014
 * @package framework
 * 
 * @category Base de Datos
 * 
 */
#require_once 'ConexionBD.class.php';
#require_once 'BaseDeDatos.interface.php';

class Mysql extends ConexionBD{
    var $enTransaccion=false;
    var $valoresReservados = [
        'current_timestamp',
    ];
    private $transaccionIniciada=false;
    /**
     * Contabiliza total de errores en ejecucion de una transaccion
     * @var Error
     * 
     */
    private $errorTransaccion=0;
    private $detalleError=array();
    /**
     * Indica si se debe codificar los valores del query para cambiar los acentos y caracteres
     * especiales al momento de ejecutar la consulta, el valor por defecto es TRUE, sin embargo
     * el DBContainer lo coloca en FALSE para hacer las inserciones
     * @var boolean $codificarHTML
     * @access public;
     * 
     */
    var $codificarHTML=CODIFICAR_HTML_BD;
    /**
     * Total de columnas obtenidas en una consulta.
     */
    var $totalRegistros;
    /**
     * Define la consulta a realizar en base de datos
     */
    var $query;
    /**
     * Arreglo que contiene el resultado del query ejecutado
     * @var $dataResult
     */
    var $dataResult;
    /**
     * Guarda el id resultante de la consulta ejecutada
     */
    var $idResult;
    /**
     * Resultado retornado de una sentencia a base de datos
     * @var string $result
     */
    public $result;
    protected $idCampo;
    
    /**
     * Instacia de la extensión mysql de PHP
     * @var $mysqli
     */
    private $mysqli;
    
    
    private $totalCampos;
    /**
     * Establece la conexión a base de datos
     */
    function establecerConexion(){
		        
        $this->mysqli = @new mysqli($this->servidor,$this->usuario,$this->clave,$this->bd);
      	
        if($this->mysqli->connect_error){
            
            throw new Exception("No se establecido la conexi&oacute;n a base de datos ".$this->mysqli->connect_error, 1);
            
        }else{
            return true;
        }
        
    }// final funcion establecerConexión
     /**
      * Ejecuta una consulta a base de datos
      * 
      * @param $query Consulta SQL a ejecutar
      * @param $tipoQuery Indica si es un query unico o una consulta multiple, 
      * por defecto es 1 
      * En caso de ser una consulta multiple no se devuelve el total de registros
      * 
      * <ul>
      * <li> 1: Unica Consulta</li>
      * <li>2 : Consulta Multiple</li>
      * </ul>
      * 
      */
    function ejecutarQuery($query="",$tipoQuery=1){
        if(!empty($query)){
            $this->query=$query;    
        }
        $this->establecerConexion();
        
        $this->mysqli->query("SET NAMES 'utf8'");
        if($this->codificarHTML===TRUE)
            $this->query=$this->query;
        if($tipoQuery==2){
            $this->result  = $this->mysqli->multi_query($this->query);
        }else{
            $this->result  = $this->mysqli->query($this->query);    
        }
        
        if(!$this->result){
            
            throw new Exception("No se pudo ejecutar el query <br/> <strong>$query</strong><br/> (".$this->mysqli->errno.") ".$this->mysqli->error, 200);       
        }
        $this->totalCampos = $this->mysqli->field_count;
        $this->idResult = $this->mysqli->insert_id;
        if(isset($this->result->num_rows))
            $this->totalRegistros = $this->result->num_rows;
        
        return $this->result;
        
    }
    /**
     * Escapa los caracteres especiales de un string
     * @method escaparString
     */
    function escaparString($string=""){
        if(!$this->mysqli)
            $this->establecerConexion();    
        return $this->mysqli->real_escape_string($string);
        
        
    }
    /**
     * Ejecuta una inserción en Base de datos
     * @param string $nombreTabla
     * @param array  $camposTabla
     * @param array  $valoresCampos
     * @return array $result
     * @deprecated Solo debe ser usado si se trabaja con clase DBContainer
     * 
     * @see self::insertar
     * 
     */
    function insert($nombreTabla,$camposTabla,$valoresCampos,$id,$unico){
                
            $insert = sprintf("insert into %s (%s) VALUES (%s)",
                                    $nombreTabla,
                                    implode(', ',$camposTabla),
                                    implode(', ',$valoresCampos)
                                   );
            
            $result = array("query"=>$insert,'idResultado'=>"");
            
            if(!Session::get('__queryInsert')){
                $validadoUnico=FALSE;
                $validarExistencia=0;
                if(count($unico)>=1){
                    $queryCheck = "select $id from $nombreTabla where ";
                    $validadoUnico=TRUE;
                    $i=0;
                    foreach ($unico as $campo) {
                        
                        $valor = array_search($campo, $camposTabla);
                        if($i>0){
                            $queryCheck.=" and ";
                        }
                        $queryCheck.="$campo = $valoresCampos[$valor]";
                        $i++;
                    }//fin foreach
                    $resultUnico = $this->ejecutarQuery($queryCheck);
                    $validarExistencia  = $this->totalRegistros;
                    
                }
                
                if($validarExistencia===0){
                    $this->ejecutarQuery($insert);
                    Session::set('__queryInsert',$insert);
                    if($this->mysqli->insert_id!=""){$ejecutado=1;}else{$ejecutado=0;}
                    $result['idResultado'] = $this->mysqli->insert_id;
                    $result['ejecutado']=$ejecutado;
                    $result['unico'] = 0;
                }else{
                    $result['ejecutado']=0;
                    $result['unico'] = 1;
                }                       
            }else{   
                /**
                 * Este else se ejecuta cuando se esta volviendo a realizar la peticion (reenvio de petición)
                 */
                $result['ejecutado']=0;
                $result['unico'] = 0;
            }
            $result['unico']=1;
        return $result;
    }

    /**
     * Realiza la inserción de un nuevo registro en base de datos
     * @method insertar
     */
    function insertar($insert){
        $this->query = $insert;
        $this->ejecutarQuery($this->query);
        $this->idResult = $this->mysqli->insert_id;
        return $this->result;
    }


    /**
     * Cierra una conexión a Base de Datos
     */
    function cerrarConexion(){
    	
        $this->mysqli->close();
    }
    
    function addLimit($limit,$offset,$query=""){
        
        $this->query=(!empty($query))?$query:$this->query;
        $this->query="$this->query limit $offset,$limit";
        return $this->query;
        
    }
    
    function obtenerTotalCampos(){
        return $this->totalCampos;
        
    }
    /**
     * Devuelve un arreglo con la información solicitada de base de datos
     * 
     * @method obtenerDataCompleta
     * @param string $query Consulta a base de datos
     * @param string $key  campo que se desee usar como key de la matriz a devolver, si es omitido los
     * keys serán autonumericos
     * @return array $dataCompleta 
     * 
     */
    function obtenerDataCompleta($query="",$key=""){
        if(is_string($query)){
            $this->query = ($query=="")?$this->query:$query;
            $this->ejecutarQuery($this->query);    
        }else
        if(is_object($query)){
            $this->result=$query;
        }
        $dataCompleta = array();
        if($this->result){
            
             
            while($data = $this->result->fetch_assoc() and count($data)>0){
                
                if(!empty($key)){
					if($this->codificarHTML===TRUE){
						$dataCompleta[$data[$key]]=$data;	
					}else{
						$dataCompleta[$data[$key]]=String::codificarArrayToHTML($data);
					}	
                    
                }else {
                	if($this->codificarHTML===TRUE){
                    	$dataCompleta[]=String::codificarArrayToHTML($data);
					}else{
						$dataCompleta[]=$data;
					}
                }
                
            }   
        $this->cerrarConexion();
        }else{
            throw new Exception("El query $this->query , no retorna resultado", 1);
            
        }
        return $dataCompleta;
        
            
    }
    /**
     * Devuelve un arreglo a partir de un result de base de datos
     * 
     */
    function obtenerArray($result=""){
        if($result!=""){
            $this->result = $result;
        }
        if($this->result){
			if($this->codificarHTML===TRUE){
				$arr = String::codificarArrayToHTML($this->result->fetch_array());	
			}else{
				$arr = $this->result->fetch_array();
			}       
          
        }else{
            throw new Exception("El result de $this->query no trae información", 1);
            
        }
        return $arr;
    }
    function obtenerArrayAsociativo($result=""){
        
          if($result){
            $this->result = $result;
          }
          if($this->codificarHTML===TRUE){
          	$arr = String::codificarArrayToHTML($this->result->fetch_assoc());	
          }else{
          	$arr = $this->result->fetch_assoc();
          }
          
          
          return $arr;
    }  
    /**
     * undocumented function
     *
     * @return void
     * @author  
     */
    function comenzarTransaccion() {
    }
    /**
     * undocumented function
     *
     * @return void
     * @author  
     */
    private function commit() {
    }
    /**
     * undocumented function
     *
     * @return void
     * @author  
     */
    private function rollback() {
    }
    /**
     * undocumented function
     *
     * @return void
     * @author  
     */
    function establecerPuntoControl() {
    }
    /**
     * undocumented function
     *
     * @return void
     * @author  
     */
    function finalizarTransaccion() {
    }
    /**
     * undocumented function
     *
     * @return void
     * @author  
     */
    function fetchRow() {
        return $this->result->fetch_row();
    }
    
    function totalField() {
        return $this->totalCampos;
    }
    /**
     * Alias FetchField POO
     * 
     * Obtiene toda la data de las columnas
     * @return array
     */
     function obtenerDatosColumnas($result=""){
        if(!empty($result)){
            $this->result = $result;
        }
        return $this->result->fetch_fields();
     }
    /**
     * Devuelve el nombre  del campo de una consulta, en caso de que el campo tenga
     * un alias, devuelve el alias.
     * 
     * @param $result Objeto Result de la consulta
     * @param $i Indice del campo a consultar
     * @return string $name Nombre del campo
     */
    function obtenerNombreCampo($result,$i) {
        $datosColms = $this->obtenerDatosColumnas($result);
        if(empty($datosColms[$i]->name))
            $nombre = $datosColms[$i]->orgname;
        else
            $nombre = $datosColms[$i]->name;
        return $nombre;
        
    }//fin funcion
    /***
     * Devuelve un arreglo con todas las tablas de la base de datos
     * 
     * @param string esquema (opcional)
     * 
     */
    function obtenerTablasBD($esquema=""){
      
           $tablasBDResult = $this->ejecutarQuery("SHOW TABLES");
            $tablasBD = array();
            while($tablas = $this->obtenerArray()){
                $tablasBD[$tablas[0]] = $tablas[0];
            }
            return $tablasBD;   
      
        
    }
    /**
     * Verifica si hay mas resultados de una consulta
     * @see mysqli::more_results
     */
    function checkProximoResultado(){
        
    }
    /**
     * DEvuelve un arreglo asociativo con los results de queries realizados
     * en una multiConsulta
     * @method obtenerDataMultiQuery
     */
    function obtenerDataMultiQuery($result="",$keys=[]){
        if(empty($result))
        $result = $this->result;
        $arrayResult = array();
        $i=0;
        do{
            if($result = $this->mysqli->store_result()){
                
                $e=0;
				$key = $i;
				if(array_key_exists($i, $keys)) $key = $keys[$i];
				
                $arrayResult[$key]['totalRegistros'] = $result->num_rows;
				$arrayResult[$key]['result']=[];
                while ($data = $this->obtenerArrayAsociativo($result)) {
                    $arrayResult[$key]['result'][$e]=$data;
                    $e++;
                }
                    
            }
             $i++;
        }while($this->mysqli->more_results() and $this->mysqli->next_result());
        
        return $arrayResult;
   }
    
    
    
    function limit($limit,$offset){
        return $this->addLimit($limit, $offset);
    }
    function __get($propiedad){
        if(property_exists($this, $propiedad)){
            return $this->$propiedad;
        }else{
            return false;
        }
    }
    
    function getValoresReservados(){
        return $this->valoresReservados;
    }
	/**
     * Retorna el listado de tablas de la base de datos
     * 
     * @method obtTablasBD
     * 
     */
  	function obtTablasBD(){
         $q = "select table_name,table_type, table_collation, create_time 
                from information_schema.tables  where table_schema='".$this->bd."'
                and table_type!='VIEW' and  table_name not like 's_%'
            ;";
        $data = $this->obtenerDataCompleta($q);
      return $data;   
    }
	 /**
     * Retorna las columnas de una tabla
     * @method obtColumnasTabla
     * @param array $tabla
     */
    function obtColumnasTabla($tabla){
        $q= "select table_schema,table_name,column_name,data_type,column_type,column_key from 
        information_schema.columns where table_schema='".$this->bd."' ";
        if(is_array($tabla))
        $q.= "and tables in (".implode(",",$tabla)."";
        else {
            $q.="and table_name='$tabla'";
        }

        return $this->obtenerDataCompleta($q);  
    }
    
}//final clase Mysql
