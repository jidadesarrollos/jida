<?PHP 

/**
 * Clase para RDBMS PostgreSQL
 * 
 * @author Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * @version 0.2
 * @package framework
 * @category Base de Datos
 * 
 */
// include_once 'ConexionBD.class.php';
// require_once 'Helpers/String.class.php';
#require_once 'baseDeDatos.interface.php';
class PSQLConexion extends ConexionBD{
	var $enTransaccion=false;
	private $transaccionIniciada=false;
	/**
	 * Contabiliza total de errores en ejecucion de una transaccion
	 * @var Error
	 * 
	 */
	    /**
     * Indica si se debe codificar los valores del query para cambiar los acentos y caracteres
     * especiales al momento de ejecutar la consulta, el valor por defecto es TRUE, sin embargo
     * el DBContainer lo coloca en FALSE para hacer las inserciones
     * @var boolean $codificarHTML
     * @access public;
     * 
     */
    var $codificarHTML=TRUE;
	private $errorTransaccion=0;
	private $detalleError=array();
    var $totalRegistros;
    /**
     * Define la consulta a realizar en base de datos
     */
    var $query;
    protected $idCampo;
	/**
	 * Establece la conexion a la base de datos de PSQL
	 * @return result $conexionID
	 */
	function insert($nombreTabla,$camposTabla,$valoresCampos,$id){
	    try{
	       $this->codificarHTML=FALSE;
			$queryCheck = "select $id from $nombreTabla where ";
			for($i=0;$i<count($camposTabla);$i++){
				if($i>0){
					$queryCheck.= " and ";
				}
				$queryCheck.=" $camposTabla[$i]=$valoresCampos[$i]";
				
			}
			
			$total  = $this->obtenerArray($this->ejecutarQuery($queryCheck));
			
			if($this->totalRegistros==0){
				
			
	            $insert = sprintf("insert into %s (%s) VALUES (%s) returning %s",
	                                $nombreTabla,
	                                implode(', ',$camposTabla),
	                                implode(', ',$valoresCampos),
	                                $id
	                               );
	            
				$id = $this->ejecutarQuery($insert);
				$resultado = $this->obtenerArray($id);
				if(is_array($resultado)){$ejecutado=1;}else{$ejecutado=0;}
	    		$result = array(
	    							"idResultado"=>$resultado[0],
	    							"query"=>$insert,
	    							"ejecutado"=>$ejecutado,
	    						);
			}else{
				$ejecutado=1;
			$result = array(
    							"idResultado"=>$total[0],
    							"query"=>$queryCheck,
    							"ejecutado"=>$ejecutado,
    						);	
			}
			
			
    		
    		return $result;
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
	}
	function establecerConexion(){
		try{
			
			
			$stringConexion="host=$this->servidorBD port=$this->puerto user=$this->usuarioBD password=$this->claveBD dbname=$this->BD";
			$this->conexionID = @pg_connect($stringConexion);
			
			if(!$this->conexionID){
				throw new Exception("No se realizó la conexión a base de datos", 10);
				
			}
	
			return $this->conexionID;
		}catch(Exception $e){
			Excepcion::controlExcepcion($e);
		}
	
	}//fin funcion BDConect
	#---------------------------------------------------
	/**
	 * 
	 * @see 
	 * 
     */
	function addLimit($limit,$offset,$query=""){
	    
	    $this->query=(!empty($query))?$query:$this->query;
	    $this->query="$this->query limit $limit offset $offset";
        
        return $this->query;
	}
	 /**
     * Ejecuta un query a base de datos y retorna el resultado
     * 
     * La funcion apertura la conexión a base de datos, ejecuta el query y posteriormente
     * cierra la conexión
     * @return object result de la consulta
	 * @return int $this->totalRegistros Total de registros de un query
	  *  
     * @param string $query consulta a ejecutar
	  * @param int $tipoQuery  En caso de ser una consulta multiple no se devuelve el total de registros
      * 
      * <ul>
      * <li> 1: Unica Consulta</li>
      * <li>2 : Consulta Multiple</li>
      * </ul>
     */
	function ejecutarQuery($query="",$tipoquery=2){
		try{
			if(!empty($query)){
				$this->query=$query;
			}
            if($this->codificarHTML===TRUE){
                $this->query = String::codificarHTML($this->query);    
            }
			//Establece la conexion solo si no existe transaccion
			if(!$this->enTransaccion){
				$this->con=$this->establecerConexion();
			}
				
			//-------------------------$this->cerrarConexion($this->idConexion);
			
			$this->result=@pg_query($this->conexionID,$this->query);
			
			if(!$this->result){
				$error= "No se pudo ejecutar la consulta : <br>
				$this->query";
				
				error_log($error);
				error_log(pg_errormessage($this->conexionID));
				if($this->enTransaccion){
					$this->detalleError[]="<p>$error</p><p>".pg_errormessage($this->conexionID)."</p>";
				}
			
				$this->errorTransaccion=$this->errorTransaccion+1;
				
					
			}else{
	
				$this->totalRegistros=@pg_num_rows($this->result);	
			}//fin if				
			
			//-------------------------
			#Cierra la conexion solo si no existe transaccion
			if(!$this->enTransaccion){
				
				$this->cerrarConexion($this->con);
			}
            
			return $this->result;
		}catch(Exception $e){
			Excepcion::controlExcepcion($e);	
		}
	}//fin funcion ejecutarQuery
	/**
	 * Cierra una conexión establecida
	 */
	 #=======================================================
	/**
	 * Devuelve un array asociativo multinivel con data de base de datos
	 * @param string $query Consulta a 
	 *
	 * @return array $data Mapa ordenado de datos de la consulta a bd.
	 * 
	 */
	function obtenerDataCompleta($query=""){
	    try{
	    	$this->ejecutarQuery($query);
			$result = $this->result;
			
			$data = pg_fetch_all($result);	
	    }catch(Exception $e){
	    	Excepcion::controlExcepcion($e);
	    }
		$this->query=($query=="")?$this->query:$query;
		
		
		
		
		return $data;
	}//fin funcion
	#=======================================================
	/**
	 * Obtener todal de columnas de una consulta
	 */
	function obtenerTotalCampos($query){
		$total = pg_num_fields($q);
		return $total;
	} //fin function totalDatos 
	/**
	 * Devuelve un array con data de una fila deñl registro de bd.
	 * 
	 * @param resultset $result Resultado de consulta de base de datos
	 * @return array $arr Data del registro de una columna del result
	 * 
	 */
	function obtenerArray($result=""){
		if($result!=""){
			$this->result = $result;
		}
		$arr  = pg_fetch_array($this->result);
		
		return $arr;
	}//fn funcion
	/**
	 * Recuperda una fila del result de BD como un array Asociativo
	 */
	function obtenerArrayAsociativo($result=""){
		if($result!=""){
			$this->result = $result;
		}
		
		$arr  = pg_fetch_assoc($result);
	
		return $arr;
	}//fn funcion
	/**
	 * Cierra una conexion a base de datos establecida
	 */
	private function cerrarConexion(){
		pg_close($this->conexionID);
	}//fin funcion cerrarConexion
	/**
	 * Inicializa una transaccion
	 * como estas tu
	 */
	function comenzarTransaccion(){
		$this->establecerConexion();
		$this->enTransaccion=true;
		$this->ejecutarQuery("BEGIN");
			
		
		return true;$cn->bd->comenzarTransaccion();
	}
	/**
	 * Ejecuta commit sobre una transaccion
	 */
	private function commit(){
		$this->ejecutarQuery('COMMIT;');
		$this->cerrarConexion($this->idConexion);
		$this->enTransaccion=false;
		return true; 
	}
	/**
	 * Ejecuta rollback sobre una transaccion
	 */
	private function rollback($nombrePunto=""){
		$rollback=($nombrePunto=="")?"ROLLBACK;":"ROLLBACK TO $nombrePunto;COMMIT;";
		#echo "<br>entro al $rollback<hr>";
		$this->ejecutarQuery($rollback);
		$this->cerrarConexion($this->idConexion);
		$this->enTransaccion=false;
		if(ERROR_PHP=='dev'){
			foreach ($this->detalleError as $key => $value) {
				echo $value;
			}	
		}
		return true; 
	}
	/**
	 * Establece un punto de control (SAVEPOINT) dentro de una transacccion
	 * 
	 * Los savepoint son utilizados para devolver una transaccion fallida hasta 
	 * el lugar donde se registro el savepoint si se desea.
	 */
	function establecerPuntoControl($nombrePunto){
		$this->ejecutarQuery("SAVEPOINT $nombrePunto;");
		return true;
	}
	/**
	 * Verifica el estatus de una transaccion y ejecuta la accion correspondiente
	 * 
	 * (COMMIT o ROLLBACK según sea el caso)
	 * @param string $punto Nombre de savepoint creado si desea hacerse un rollback hasta el.
	 * 
	 */
	function finalizarTransaccion($punto=""){
		if($this->errorTransaccion==0){
			$this->commit();
		}else{
			$this->rollback($punto);
		}
	}//fin funcion finalizarTransaccion
	
	/**
	 * Devuelve una fila de resultados como un array numerico
	 *
	 * @return array $fetch
	 * @author  
	 */
	function fetchRow($result){
        $fetch=pg_fetch_row($result); 
        return $fetch;
    }//fin function fetchRow
    
    /**
     * Retorna el total de columnas de un result
     */    
    function totalField($q){
        $total = pg_num_fields($q);
        return $total;
    } //fin function totalDatos
    
    /**
     * Retorna el nombre de un campo del query
	 * @param object $q Result de la consulta a base de datos
	 * @param int $i Numero del campo 
     */
    function obtenerNombreCampo($q,$i){
        $fetch=pg_field_name($q,$i); 

        return $fetch;
    }//fin function NombreCampo
    
    	/***
     * Devuelve un arreglo con todas las tablas de la base de datos
     * 
     * @param string esquema (opcional)
     * 
     */
	function obtenerTablasBD($esquema=""){
	    $tablasBDResult = $this->ejecutarQuery("select * from pg_tables where tablename='s_formularios'");
		$tablasBD['s_formularios']='s_formularios';
        if($this->totalRegistros>0){
        	$tablasBD['s_formularios']='s_formularios';
        }
		return $tablasBD;
	}
}
