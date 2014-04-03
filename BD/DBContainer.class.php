<?php
//require_once 'Config/constantesConfiguracion.php';
/**
 * Clase ORM DBContainer
 * 
 * @category    framework
 * @package     BD
 *
 * @author      Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * @license     http://www.gnu.org/copyleft/gpl.html    GNU General Public License
 * @version     0.1 - 09/09/2013
 *
 */


class DBContainer {
    
    /**
     * 
     * @var unknown
     */
    private $result;
    
    /**
     * Define el manejador de base de datos (mysql o psql)
     * @var string $manejadorBD
     */
    private $manejadorBD;
    
    /**
     * Instancia del manejador de base de datos
     * 
     * @access protected
     * @var object $bd
     */
    protected $bd;
    
    /**
     *
     * @var $con id de la conexión establecida
     */
    private $con;
    
    /**
     * Nombre de la tabla instanciada
     * 
     * @var $nombretabla
     */
    protected $nombreTabla;
    
    /**
     * Propiedades publicas de la clase
     */
    protected $propiedadesPublicas = array();
    
    /**
     * Indica la clave primaria en base de datos la cual debe tener
     * el mismo nombre de la tabla con el sufijo "id_"
     * 
     * @var string $clavePrimaria
     */
    protected $clavePrimaria;
    
    /**
     * 
     * @var unknown
     */
    protected $momentoSalvado = FALSE;
    
    /**
     * 
     * @var unknown
     */
    protected $noNumber = array ();
    /**
     * Arreglo que define que campos juntos no pueden tener data
     * repetida
     * @var array $unico
     */
    protected $unico=array();
    
    
    /**
     * Define la conexion a base de datos a utilizar
     * @var string $configuracionBD
     * @access protected
     */
    protected $configuracionBD="default";
    /**
     * Define las propiedades del objeto que sean columnas de la tabla en base
     * de datos, por defecto son las propiedades publicas
     * @var $propiedadesBD
     */
    private $propiedadesBD;
    /**
     * Define la clase u objeto Instanciado
     * @var $clase
     * @access private
     */
    private $clase;
    
    /**
     * Contructor del BDContainer
     *
     * Inicializa el objeto de conexión a base de datos para
     * la funcionalidad completa de la clase.
     * 
     * @param int $id Identificador del objeto modelo
     * @param object $clase Objeto clase que hereda del DBContainer
     */
    public function __construct($clase = "", $id = "") {
        try {
            if (!defined('manejadorBD')) {
                throw new Exception("No se encuentra definido el manejador de base de datos", 1);
            }
            
            $this->manejadorBD = manejadorBD;
            $this->clavePrimaria = $this->obtenerClavePrimaria();
            
            switch ($this->manejadorBD) {
                case 'PSQL' :
                    
                    include_once 'Psql.class.php';
                    $this->bd = new PSQLConexion ($this->configuracionBD);
                    break;
                case 'MySQL' :
                    
                    // include_once 'Mysql.class.php';
                    $this->bd = new Mysql ();
                    break;
            }
            if(!empty($clase)){
                
                $this->propiedadesBD = $this->obtenerPropiedadesObjeto();
                $this->clase = $clase;
            }
            if (!empty($id)) {
                $this->inicializarObjeto($id, $clase);
            }
            
        } catch (Exception $e) {
            Excepcion::controlExcepcion($e);
        }
    }
    /**
     * Inicializa un objeto a partir de Base de Datos
     * @method inicializarObjeto
     * @param $id Identificador de la clase
     * @param $clase metodo magico __CLASS__
     */
    
    protected function inicializarObjeto($id, $clase = "") {
        try {
        	$clase = (empty($clase)) ? $this->clase : $clase;
            $query = "select * from $this->nombreTabla where $this->clavePrimaria=$id";
            $result = $this->bd->obtenerArrayAsociativo ( $this->bd->ejecutarQuery ( $query ) );
            $this->establecerAtributos ( $result, $clase );
        } catch ( Exception $e ) {
            Excepcion::controlExcepcion ( $e );
        }
    }
    
    /**
     * Establece los atributos de una clase.
     *
     * Valida si los valores pasados en el arreglo corresponden a los atributos de la clase en uso
     * y asigna el valor correspondiente
     * 
     * @access protected
     * @param array @arr Arreglo con valores
     * @param instance @clase Instancia de la clase
     */
    protected function establecerAtributos($arr, $clase="") {
        if(empty($clase)){
            $clase=$this->clase;
        }
        try {
            $metodos = get_class_vars($clase);
            foreach($metodos as $k => $valor) {
                
                if (isset($arr[$k])) {
                    $this->$k = $arr[$k];
                }
            }
        } catch (Exception $e) {
            Excepcion::controlExcepcion($e);
        }
    }
    
    /**
     * Convierte la data obtenida de un formulario en un Array Asociativo
     *
     * @access protected
     * @param array $post arreglo $_POST obtenido del formulario
     * @return array $data arreglo asociativo generado        
     */
    protected function postEnArray($post) {
        $data = array();
        foreach ($post as $key => $value ) {
            $data[$key] = $value;
        }
        return $data;
    }
    
    /**
     * Salva un objeto instanciado
     *
     * Verifica los datos del objeto y si el mismo existe, realiza un save (insert o update)
     * 
     * @access protected
     * @method salvarObjeto
     * @deprecated
     * @param object $clase objeto de la clase instanciada
     * @param array $datos arreglo con datos a guardar
     * @param boolean $momentoGuardado
     *          Indica si se registra la fecha y hora del registro
     *          si $momentoGuardado se encuentra activado la tabla de base de datos debe tener los campos
     *          fecha_creaciono,hora_creado,usuario_creado y si es un update
     *          los campo fecha_modificado,hora_modificado,usuario_modificado
     *          salvarObjeto(__CLASS__);
     */
    function salvarObjeto($clase, $datos = "", $momentoGuardado = FALSE) {
        try {
            if (gettype ( $this->bd ) != 'object') {
                throw new Exception ( "No se encuentra definido el objeto de base de datos, porfavor verifique que se llame el contructor del dbContainer correctamente", 1 );
            }
            // si se pasan los datos se establecen los
            // valores en las propiedades de la clase
            if (is_array ( $datos )) {
                $this->establecerAtributos ( $datos, $clase );
            }
            
            $propiedades = $this->obtenerPropiedadesObjeto ();
            $accion = "";
            
            if (empty($this->propiedadesPublicas[$this->clavePrimaria])) {
                
                $result = $this->insertarObjeto($momentoGuardado);
                $accion = "Insertado";
            } else {
                
                $result = $this->modificarObjeto($momentoGuardado);
                $accion = "Modificado";
            }
            $retorno = $result;
            $retorno['result'] = $result;
            $retorno['accion'] =  $accion; 
            return $retorno;
        } catch ( Exception $e ) {
            Excepcion::controlExcepcion ( $e );
        }
    }
    /**
     * Salva un objeto instanciado
     *
     * Verifica los datos del objeto y si el mismo existe, realiza un save (insert o update)
     * 
     * @access public
     * @method salvar
     * @param object $clase objeto de la clase instanciada
     * @param array $datos arreglo con datos a guardar
     * @param boolean $momentoGuardado
     *          Indica si se registra la fecha y hora del registro
     *          si $momentoGuardado se encuentra activado la tabla de base de datos debe tener los campos
     *          fecha_creado,hora_creado,usuario_creado y si es un update
     *          los campo fecha_modificado,hora_modificado,usuario_modificado
     * 
     */
    
    function salvar($datos = "", $momentoGuardado = FALSE) {
        try {
            if (gettype ( $this->bd ) != 'object') {
                throw new Exception ( "No se encuentra definido el objeto de base de datos, porfavor verifique que se llame el contructor del dbContainer correctamente", 1 );
            }
            // si se pasan los datos se establecen los
            // valores en las propiedades de la clase
            if (is_array ( $datos )) {
                $this->establecerAtributos ( $datos, $this->clase );
            }
            
            $propiedades = $this->obtenerPropiedadesObjeto ();
            $accion = "";
            
            if (empty($this->propiedadesPublicas[$this->clavePrimaria])) {
                
                $result = $this->insertarObjeto($momentoGuardado);
                $accion = "Insertado";
            } else {
                
                $result = $this->modificarObjeto($momentoGuardado);
                $accion = "Modificado";
            }
            $retorno = $result;
            $retorno['result'] = $result;
            $retorno['accion'] =  $accion; 
            return $retorno;
        } catch ( Exception $e ) {
            Excepcion::controlExcepcion ( $e );
        }
    }
    /**
     * Obtiene todas las propiedades públicas de un objeto instanciado.
     *
     * Hace uso de la clase Reflection, nativa de php
     * 
     * @return array $arrayPropiedades Arreglo con propiedades publicas
     */
    private function obtenerPropiedadesObjeto() {
        $reflector = new ReflectionClass(get_class($this));
        $propiedades = $reflector->getProperties(ReflectionProperty::IS_PUBLIC);
        $arrayPropiedades = array();
        foreach($propiedades as $propiedad ) {
            if (!$propiedad->isStatic()) {
                $arrayPropiedades[$propiedad->getName()] = $propiedad->getValue($this);
            }
        }
        $this->propiedadesPublicas = $arrayPropiedades;
    }
    
    /**
     * Registra un Objeto (INSERT)
     * 
     * @param string $momentoGuardado
     * @return unknown 
     */
    private function insertarObjeto($momentoGuardado = false) {
        try {
            $campos = array();
            $valores = array();
            
            foreach($this->propiedadesPublicas as $campo => $valor) {
                
                if ($campo != $this->clavePrimaria) {
                    
                    $campos[] = $campo;
                    switch ($valor) {
                        case '':
                            if(!is_numeric($valor)){
                                $valores[]="null";
                            } else {
                                $valores[]=$valor;
                            }
                            break;
                        case 'fn_unix_actual()':
                            $valores[]=$valor;
                            break;
                        default:
                            $valores[]="'".$valor."'";
                            break;
                    }
					
					
                }
            }
            if($momentoGuardado===true){
						$campos[]='fecha_creacion';
						$campos[]='fecha_modificacion';
						$valores[]= "'".FechaHora::datetime()."'";
						$valores[]= "'".FechaHora::datetime()."'";
					}
            $result = $this->bd->insert($this->nombreTabla, $campos, $valores, $this->clavePrimaria,$this->unico);
			Session::destroy('__queryInsert');
            return $result;
        } catch(Exception $e) {
            Excepcion::controlExcepcion($e);
        }
    }
    

    /**
     * Modifica un Objeto (UPDATE)
     * 
     * @param string $momentoGuardado
     * @return multitype:string number multitype:
     */
    private function modificarObjeto($momentoGuardado = FALSE) {
        $update = "update " . $this->nombreTabla . " set ";
        $i = 0;
        
        foreach ( $this->propiedadesPublicas as $campo => $valor ) {
            
            if ($campo != $this->clavePrimaria) {
                if ($i > 0) {
                    $update .= ",";
                }
                 $campos[] = $campo;
                    switch ($valor) {
                        case '':
                            if(!is_numeric($valor)){
                                $campoValor="null";
                            } else {
                                $campoValor=$valor;
                            }
                            break;
                        case 'fn_unix_actual()':
                            $campoValor=$valor;
                            break;
                        default:
                            $campoValor="'".$valor."'";
                            break;
                    }
                $update .= "$campo=$campoValor ";
                
                $i ++;
            }
        }
        if ($momentoGuardado === TRUE) {
            $update .= ',fecha_modificacion=current_timestamp ';
        }
        $update .= "where $this->clavePrimaria=" . $this->propiedadesPublicas [$this->clavePrimaria] . ";";
        
        $this->bd->ejecutarQuery ( $update );
        if ($this->bd->ejecutarQuery ( $update )) {
            $ejecutado = 1;
        } else {
            $ejecutado = 0;
        }
        $result = array (
                "idResultado" => $this->propiedadesPublicas [$this->clavePrimaria],
                "query" => $update,
                "ejecutado" => $ejecutado,
                "unico"=>0 
        );
        
        return $result;
    }
    
    /**
     * Verifica si un objeto existe o no.
     */
    private function validarExistenciaObjeto() {}

    /**
     * Devuelve el nombre de la clase usada
     * 
     * @param object $clase
     *          Instancia de clase utilizada
     * @return string Nombre de clave primaria de la clase
     */
    private function obtenerClavePrimaria() {
        $clase = $this->nombreTabla;
        $clavePrimaria = ($this->clavePrimaria != "") ? $this->clavePrimaria : "id_" . $clase;
        return strtolower($clavePrimaria);
    }
    
    /**
     * Elimina un Registro
     *
     * Elimina un Registro de Una Tabla
     *
     * @param array $datos          
     * @return boolean Retorna True O False si la Consulta se Ejecuta
     */
    protected function eliminarDatos($datos) {
        $query = "DELETE FROM " . $this->nombreTabla;
        
        if (is_array ( $datos )) {
            $query .= " WHERE ";
            $i = 1;
            foreach ( $datos as $campo => $valor ) {
                $valor = is_numeric ( $valor ) ? $valor : "'$valor'";
                $query .= $campo . "=" . $valor;
                if ($i < count ( $datos )) {
                    $query .= " AND ";
                }
                $i ++;
            }
            $query .= ";";
        }
        $result = $this->bd->ejecutarQuery ( $query );
        
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Elimina multiples registros a partir de un campo especificado
     *
     * @param array $arrayDatos Arreglo de valores claves para eliminar
     * @param string $campo Nombre del campo en base de datos sobre el cual se realiza el filtro
     * @example eliminarMultiplesDatos(array(1,5,4,10),'id_form')
     */
    function eliminarMultiplesDatos($arrayDatos, $campo) {
        $datos = array ();
        foreach ( $arrayDatos as $key => $value ) {
            if (is_numeric ( $value )) {
                $datos [] = "$value";
            } else {
                $datos [] = "\"$value\"";
            }
        }
        $query = sprintf ( "DELETE FROM %s where $campo in (%s)", $this->nombreTabla, implode ( ',', $datos ) );
        if ($this->bd->ejecutarQuery ( $query )) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Elimina un objeto en base de datos
     *
     * Esta funcion puede ser utilizada solo si es un objeto instanciado c
     */
    function eliminarObjeto() {
        $clavePrimaria = $this->clavePrimaria;
        
        $query = "delete from $this->nombreTabla where $this->clavePrimaria = " . $this->$clavePrimaria;
        if ($this->bd->ejecutarQuery ( $query )) {
            return true;
        } else {
            return false;
        }
    }
    
    
    private function validarTipoDatoSQL(){
        
    }
    /**
     * 
     */
    function obtenerTabla($campos=null,$where){
        if(!is_array($campos)){
            $campos = $this->propiedadesPublicas;
            $selectCompleto=TRUE;
        }
        
        $query ="Select ";
        $where=" ";
        $cont=0;
        foreach ($campos as $key => $value) {
            if($selectCompleto!==TRUE){
                if(!is_numeric($key)){
                    if($cont>0){
                        $query.=",";
                    }
                    $query.="$key";
                    
                    
                }
            }
            $cont++;
        }//fin
        
    }//fin
    
}