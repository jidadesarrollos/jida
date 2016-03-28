<?php
/**
 * Clase RDBMS PostgreSQL
 *
 * @category    framework
 * @package     BD
 *
 * @author      Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * @license     http://www.gnu.org/copyleft/gpl.html    GNU General Public License
 * @version     0.1 - 09/09/2013
 *
 */
class Psql extends ConexionBD {
    public $ejecucionQuery = TRUE;
    /**
     * 
     * @var boolean
     */
    public $enTransaccion = false;
    public $valoresReservados=[];
    /**
     * Total de los Registro
     * @var int total registro
     */
    public $totalRegistros;
    
    /**
     * Consulta SQL
     * 
     * @var string query
     */
    public $query;
    
    /**
     * Indica si se ha iniciado una Transaccion
     * @var unknown
     */
    private $transaccionIniciada = false;
    
    /**
     * Contabiliza total de errores en
     * ejecucion de una transaccion
     * 
     * @var int $errorTransaccion numero de errores
     */
    private $errorTransaccion = 0;
    
    /**
     * Detalle de los Errores Generados
     * 
     * @var array $detalleError 
     */
    private $detalleError = array();

    /**
     * 
     * @var int id campos
     */
    protected $idCampo;
    
    
    function __construct($configuracion){
        parent::__construct($configuracion);
    }
    /**
     * Establece la Conexion a la Base de Datos
     * 
     * @return int id conexion
     */
    public function establecerConexion() {
    	
        $stringConexion = "host=$this->servidor port=$this->puerto user=$this->usuario password=$this->clave dbname=$this->bd";         
        #Debug::mostrarArray($stringConexion,FALSE);
		#Debug::mostrarArray(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS,3),false);
        $this->conexionID = pg_connect ( $stringConexion );
		
        if (! $this->conexionID) {
            throw new Exception ( "No se realiza la conexion a base de datos", 10 );
        }
            
        return $this->conexionID;

    }

    /**
     * Cierra una conexion establecida a la Base de Datos
     *
     * @return void
     */
    private function cerrarConexion() {
        pg_close($this->conexionID);
    }
    
    /**
     * Metodo para Realizar Insert en Base de Datos
     * 
     * @param string $nombreTabla
     * @param array $camposTabla
     * @param array $valoresCampos
     * @param id $id
     * @return mixed retorna un array o un boolean
     */
    public function insert($nombreTabla, $camposTabla, $valoresCampos, $id,$unico) {
        
            $validadoUnico=FALSE;
            $queryCheck = "select $id from $nombreTabla where ";
            /**
             * @var $nivelCheck
             * Identifica el nivel de chequeo realizado 1 si es un query igual 0 en caso de aplicarse
             * validacion sobre el arreglo "unico" del objeto
             */
            $nivelCheck=1;
            if(count($unico)<1){
                for($i = 0; $i < count($camposTabla); $i ++) {
                    if ($i > 0) {
                        $queryCheck .= " and ";
                    }
                    if($camposTabla[$i]!='id_usuario_creador' or $camposTabla[$i]!='id_usuario_modificador' or
                        $camposTabla[$i]!='fecha_creacion' or $camposTabla[$i]!='fecha_modificacion'){
                            $queryCheck .= " $camposTabla[$i]=$valoresCampos[$i]";      
                    }
                    
                }
                $nivelCheck=1;
                
            }else{
                $validadoUnico=TRUE;
                $i=0;
                
                foreach ($unico as $campo) {
                        $valor = array_search($campo, $camposTabla);
                        if($i>0){
                            $queryCheck.=" and ";
                        }
                        if(array_key_exists($valor, $valoresCampos) and 
                        (is_null($valoresCampos[$valor]) or $valoresCampos[$valor]=="" or $valoresCampos[$valor]=='null')){
                            $queryCheck.="$campo is $valoresCampos[$valor]";
                        }else{
                            $queryCheck.="$campo = $valoresCampos[$valor]"; 
                        }
                        
                        $i++;
                }
                
                $nivelCheck=0;
            }
            
            $resultado = $this->ejecutarQuery($queryCheck);
            
            if ($this->totalRegistros == 0) {
                
                $insert = sprintf("insert into %s (%s) VALUES (%s) returning %s", $nombreTabla, implode(', ', $camposTabla),implode(', ',$valoresCampos), $id);
                
                $id = $this->ejecutarQuery($insert);
                $resultado = $this->obtenerArray($id);
                if (is_array($resultado)) {
                    $ejecutado = 1;
                } else {
                    $ejecutado = 0;
                }
                $result = array (
                        "idResultado" => $resultado[0],
                        "query" => $insert,
                        "ejecutado" => $ejecutado,
                        "unico"=>0 
                );
            } else {
                $total = $this->obtenerArray($resultado);
                $ejecutado = 1;
                
                $result = array (
                        "idResultado" => $resultado[0],
                        "query" => $queryCheck,
                        "ejecutado" =>$nivelCheck,
                        "unico"=>($nivelCheck==0)?1:0, 
                );
            }
            
            return $result;
        
    }

    /**
     * 
     * @param unknown $limit
     * @param unknown $offset
     * @param string $query
     * @return string
     */
    public function addLimit($limit, $offset, $query = "") {
        $this->query = (! empty ( $query )) ? $query : $this->query;
        $this->query = "$this->query limit $limit offset $offset";
        
        return $this->query;
    }
    
    /**
     * Ejecuta un query a base de datos y retorna el resultado
     *
     * La funcion apertura la conexión a base de datos, ejecuta el query y posteriormente
     * cierra la conexión
     * 
     * @return object result de la consulta
     * @return int $this->totalRegistros Total de registros de un query
     *        
     * @param string $query
     *          consulta a ejecutar
     * @param int $tipoQuery
     *          En caso de ser una consulta multiple no se devuelve el total de registros
     *          
     *          <ul>
     *          <li> 1: Unica Consulta</li>
     *          <li>2 : Consulta Multiple</li>
     *          </ul>
     */
    public function ejecutarQuery($query = "", $tipoquery = 2) {
        if (! empty ( $query )) {
            $this->query = $query;
        }
        
        ;
        // Establece la conexion solo si no existe transaccion
        if (! $this->enTransaccion) {
        	#echo "ejecutarQuery<br>".$this->query;
			#Debug::mostrarArray($this,FALSE);
            $this->con = $this->establecerConexion ();
			#echo "<hr><hr>";Debug::mostrarArray($this->con,FALSE);echo "<hr><hr>";
        }
		$this->result = pg_query ( $this->conexionID, $this->query );
        
        if (! $this->result) {
            $error = "No se pudo ejecutar la consulta : <br>
            $this->query";
            
            error_log($error);
            error_log(pg_errormessage($this->conexionID ));
            
            if ($this->enTransaccion) {
                $this->detalleError [] = "<p>$error</p><p>" . pg_errormessage ( $this->conexionID ) . "</p>";
            }
            
            $this->errorTransaccion = $this->errorTransaccion + 1;
        } else {
            
            $this->totalRegistros = pg_num_rows ( $this->result );
        }
        
        //Cierra la Conexion
        if (! $this->enTransaccion) {
            
            $this->cerrarConexion($this->con);
        }
        $this->ejecucionQuery =(!$this->result)?FALSE:TRUE;

        return $this->result;
        
        
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
    public function obtenerDataCompleta($query = "",$key="") {
    
        $this->ejecutarQuery ( $query );
        $result = $this->result;
        $data = array();
		
        while($row = $this->obtenerArrayAsociativo($result)){
          if(!empty($key))
                $data[$row[$key]]=Cadenas::codificarArrayToHTML($row);
            else {
                $data[]=Cadenas::codificarArrayToHTML($row);
            }            
        }
		
        $this->query = ($query == "") ? $this->query : $query;
        
        return $data;
    }
    
    /**
     * Obtener todal de columnas de una consulta
     */
    public function obtenerTotalCampos($query) {
        $total = pg_num_fields($q);
        return $total;
    }
    
    /**
     * Devuelve un array con data de una fila deñl registro de bd.
     *
     * @param resultset $result
     *          Resultado de consulta de base de datos
     * @return array $arr Data del registro de una columna del result
     *        
     */
    public function obtenerArray($result = "",$mayus=false) {
            
        if ($result != "") {
            $this->result = $result;
        }
        if($this->result){
            $arr = Cadenas::codificarArrayToHTML(pg_fetch_array ( $this->result ),$mayus);   
        }else{
            throw new Exception("El query $this->query no retorna valor", 1);
            
        }
        
        
        return $arr;
    }
    
    /**
     * Recuperda una fila del result de BD como un array Asociativo
     */
    public function obtenerArrayAsociativo($result = "",$mayus=false) {
        
        if ($result != "") {
            $this->result = $result;
        }
        if($this->result){
            
            $arr = Cadenas::codificarArrayToHTML(pg_fetch_assoc ( $result ));	
            return $arr;
        }else{
            return False;
        }
        
    }

    /**
     * Inicializa una Transaccion
     * 
     * @return boolean true
     */
    public function comenzarTransaccion() {
    	echo "<hr>transaccion<hr>";
        $this->establecerConexion ();
        $this->enTransaccion = true;
        $this->ejecutarQuery ( "BEGIN" );
        
        return true;
        $cn->bd->comenzarTransaccion ();
    }
    
    /**
     * Ejecuta commit sobre una transaccion
     * 
     * @return boolean true
     */
    private function commit() {
        $this->ejecutarQuery('COMMIT;');
        $this->cerrarConexion($this->idConexion);
        $this->enTransaccion = false;
        return true;
    }

    /**
     * Ejecuta rollback sobre una transaccion
     * 
     * @param string $nombrePunto
     * @return boolean true
     */
    private function rollback($nombrePunto = "") {
        $rollback = ($nombrePunto == "") ? "ROLLBACK;" : "ROLLBACK TO $nombrePunto;COMMIT;";

        $this->ejecutarQuery ( $rollback );
        $this->cerrarConexion ( $this->idConexion );
        $this->enTransaccion = false;
        if (ERROR_PHP == 'dev') {
            foreach($this->detalleError as $key => $value ) {
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
     * 
     * @param string $nombrePunto
     * @return boolean true
     */
    public function establecerPuntoControl($nombrePunto) {
        $this->ejecutarQuery("SAVEPOINT $nombrePunto;");
        return true;
    }

    /**
     * Verifica el estatus de una transaccion y ejecuta la accion correspondiente
     *
     * (COMMIT o ROLLBACK según sea el caso)
     * 
     * @param string $punto Nombre de savepoint creado si desea hacerse un rollback hasta el.
     *          
     */
    public function finalizarTransaccion($punto = "") {
        if ($this->errorTransaccion == 0) {
            $this->commit ();
        } else {
            $this->rollback ( $punto );
        }
    }
    
    /**
     * Devuelve una fila de resultados como un array numerico
     * 
     * @param unknown $result
     * @return multitype:
     */
    public function fetchRow($result,$mayus=false) {
        $fetch = Cadenas::codificarArrayToHTML(pg_fetch_row($result),$mayus);
        return $fetch;
    }
    
    /**
     * Retorna el Total de Columna de una Tabla
     * 
     * @param string 
     * @return int Numero de Columna
     */
    public function totalField($q) {
    	if(empty($q)) $q = $this->result;
        $total = pg_num_fields($q);
        return $total;
    }
    
    /**
     * Retorna el nombre de un Campo del Query
     * 
     * @param Object $q Result de la Consulta
     * @param int $i Numero del Campo
     * @return string
     */
    public function obtenerNombreCampo($q, $i) {
        $fetch = pg_field_name($q, $i);
        
        return $fetch;
    }
    
    /**
     * Devuelve un arreglo con todas las tablas
     * de un determinado esquema de base de datos
     * 
     * @param string $esquema
     * @return array lista de Esquemas
     */
    public function obtenerTablasBD($esquema = "") {
        $tablasBDResult = $this->ejecutarQuery("select * from pg_tables where tablename='s_formularios'");
        $tablasBD['s_formularios'] = 's_formularios';
        if ($this->totalRegistros > 0) {
            $tablasBD['s_formularios'] = 's_formularios';
        }
        return $tablasBD;
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
    
}
