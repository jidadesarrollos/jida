<?php
/**
* Clase Padre de modelos
* @author Julio Rodriguez
* @package Framework
  @subpackage BD
* @version 01
* @category Model
*/
include_once 'ResultBD.class.php';
class DataModel{
    
    protected $tablaBD;
    protected $prefijo;
    protected $fecha_creacion;
    protected $fecha_modificacion;
    protected $result;
    
    protected $registroMomentoGuardado=TRUE;
    /**
     * Consulta de base de datos construida
     * @var $query
     */
    private $query;
    /**
     * Nombre de la Clave primaria de base de datos
     * @var string $pk
     */
    protected $pk;
    
    /**
     * Objeto de conexión a Base de datos
     * @var object $bd
     */
    protected $bd;
    /**
     * Define la conexion a base de datos a utilizar
     * @var string $configuracionBD
     * @access protected
     */
    protected $configuracionBD;
    /**
     * Arreglo con las propiedades de base de datos del objeto
     * @var array $propiedades
     */
    private $propiedades=array();
    /**
     * Arreglo que registra las propiedades de la clase que son objetos
     * @var $propiedadesObjetos = array();
     */
     private $propiedadesObjetos;
     
     /**
     * Permite identificar si la consulta contiene una clausula where;
     * @var $usoWhere
     */
    private $usoWhere=FALSE;
    
    /**
     * Nombre de la clase instanciada
     * @var $clase
     * @access private
     * 
     */
     private $clase;
     /**
      * Instancia de objeto para retorno de data de Base de Datos
      * @param object $resultBD
      */
     private $resultBD;
     
    
    /**
     * Funcion constructora
     * @method __construct
     */
    function __construct($id=false){
        $numeroParams = func_num_args();
        $param = func_get_args(0);
        $this->clase = get_class($this);
        $this->initBD();
        if(empty($this->tablaBD)){
            throw new Exception("Debe definir el nombre de la tabla de base de datos", 1);
                
        }
        if(empty($this->pk)){
            $this->obtenerpk();
        }
        $this->obtenerPropiedadesObjeto();
        if($id and is_int($id)){
            $this->instanciarObjeto($id);   
        }
        
    }
    /**
     * Inicializa un objeto a partir de Base de Datos
     * @method inicializarObjeto
     * @param int $id Identificador unico del registro;
     */
    protected function instanciarObjeto($id) {
        $this->identificarPropertyObjects();
            $data = $this->consulta()
                    ->filtro([$this->pk=>$id])
                    ->obtFila();
            $this->establecerAtributos ( $data, $this->clase );
    }//fin función inicializaarObjeto
    /**
     * Identifica el nombre de la tabla de base de datos
     * @method obtenerTablaBD
     */
    private function obtenerTablaBD(){
        
        
    }
     /**
      * Verifica que clases son identificadas como objetos
      * 
      * @method identificarPropertyObjects
     */
    private function identificarPropertyObjects(){
        foreach ($this->propiedades as $prop => $val) {
            
            if (substr($prop, 0,2)=='id' and $prop!=$this->pk){
                $propiedad = str_replace("id_", "", $prop);
                $objeto =String::upperCamelCase(str_replace("_", " ", $propiedad));
                if($propiedad!=$this->clase and class_exists($objeto))
                    $this->propiedadesObjetos[$prop]=new $objeto(); 
            }   
        }//fin foreach
        
    }//fin indentificarRefereincas
    /**
     * Permite acceder a propiedades privadas o protegidas del objeto instanciado
     * @method _get()
     * @param string $propiedad Nombre de la propiedad a obtener
     */
    function __get($propiedad){
        if(property_exists($this, $propiedad)){
            return $this->$propiedad;
        }else{        
            throw new Exception("La propiedad solicitada no existe", 123);   
        }
    }
    
    function __establecerAtributos($arr){
        $this->establecerAtributos($arr);
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
        
        $metodos = get_class_vars($clase);
        foreach($metodos as $k => $valor) {
            
            if (isset($arr[$k])) {
                $this->$k = $arr[$k];
            }
        }
        
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
                // include_once 'Mysql.class.php';
                $this->bd = new Mysql ();
                break;
        }
        $this->resultBD=new ResultBD($this);
    }//fin metodo initBD
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
        $this->propiedades = $arrayPropiedades;
    }
    
    
     /**
     * Funcion para obtener datos de una tabla
     * @method obt
     * 
     */
    protected function consulta($campos=""){
        $banderaJoin = FALSE;
        $join="";
        if(count($this->propiedadesObjetos)>0){
            $camposJoin = "";
            $i=0;
            foreach ($this->propiedadesObjetos as $campo => $object) {
                $banderaJoin=TRUE;
                $tabla = "";
                $pk="";
                
                if(property_exists($object, 'tablaBD')){
                    
                    $tabla = $object->_get('tablaBD');
                    $pk = $object->_get('pk');
                    $props= $object->_get('propiedades');
                    
                }elseif(property_exists($object, 'nombreTabla')){
                    $tabla = $object->_get('nombreTabla');
                    $pk = $object->_get('pk');
                    $props = $object->_get('propiedadesPublicas');
                }//fin if
                    $camposTabla = array_keys($props);
                    array_walk($camposTabla,function(&$ele,$clave,$tabla){
                                                    $ele = $tabla.".".$ele;
                                    },$tabla);
                    if($i>0)
                        $camposJoin.=",";
                    $camposJoin .=" ".implode(", ",$camposTabla);
                if(!empty($tabla) and !empty($pk)){
                    $join .= sprintf(" JOIN %s on (%s.%s = %s.%s)",$tabla,$this->tablaBD,$this->pk,$tabla,$pk);
                }
                ++$i;
            }//foreach
        }//fin if para joins

        if(empty($campos)){
            $campos =  array_keys($this->propiedades);
        }
        if(is_array($campos)){
            array_walk($campos,function(&$key,$valor,$tabla){
                             $key=$tabla.".".$key;
            },$this->tablaBD);
        
            $campos = implode(", ",$campos);
        }
        $this->query="SELECT $campos ";
        if($banderaJoin===TRUE)
            $this->query .=", ".$camposJoin;
        $this->query.=" from $this->tablaBD ".$join;
        
        return $this;
    }
    /**
     * Obtiene el nombre de la clave primaria de la tabla de base de datos
     * 
     * El nombre es construido en base al estandard
     * @var obtenerpk
     */
    private function obtenerpk() {
        $clase = $this->clase;
        if(!empty($clase)){
            $pk = ($this->pk != "") ? $this->pk : "id_" . $clase;
            $this->pk = strtolower($pk);
        }
            
    }
    private function validarRelaciones(){
        
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
    protected function filtro($arrayFiltro=array()){
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
    protected function like($arrayFiltro,$tipo=1){
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
    protected function obt(){
        
        return $this->bd->obtenerDataCompleta($this->query);
    }
    protected function obtFila(){
        return $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery($this->query));
    }
    /**
     * Permite registrar el objeto actual
     * @method salvar
     */
    function salvar($data=""){
        if(is_array($data)){
            $this->establecerAtributos($data);
        }
        $this->obtenerPropiedadesObjeto();
        if(empty($this->propiedades[$this->pk])){
            $this->insertar();
        }else{
            $this->modificar();
        }
        return $this->result->setValores($data);
    }
    /**
     * Crea un registro
     */
    private function insertar($data=""){
          $data = $this->estructuraInsert();
          
          $insert = sprintf("insert into %s (%s) VALUES (%s)",
                            $this->tablaBD,
                            implode(",",$this->obtenerCamposQuery()),
                            implode(",",$data['valores']));
        if($this->bd->insertar($insert)){
            $this->$this->pk = $this->bd->idResult;
        }
        
    }
    
    /**
     * Obtiene los campos de Base de datos utilizados para realizar una inserción
     * o modificación.
     * 
     * @method obtenerCamposQuery
     * @return array $campos
     */
    private function obtenerCamposQuery(){
        $campos = $this->propiedades;
        unset($campos[$this->pk]);
        $campos = array_keys($campos);
        if($this->registroMomentoGuardado){ 
            $campos[]='fecha_creacion';
            $campos[]='fecha_modificacion';
        }
        return $campos;
    }
    /**
     * Inserta multiples registros en Base de Datos
     * @method crearTodo
     * @param array $data Data a insertar
     */
    function salvarTodo($data){
        if(is_array($data)){
            $insert = "INSERT INTO ".$this->tablaBD." ";

            $insert.="(".implode(",", $this->obtenerCamposQuery()).") VALUES ";
            $i=0;
            foreach ($data as $key => $registro) {
                if($i>0) $insert.=",";
                $datos = $this->estructuraInsert($registro);
                $insert.=" (".implode(',', $datos).")";
                ++$i;
            }
            $this->bd->ejecutarQuery($insert);
            
            return $this->resultBD->setValores($this);
        }else{
            throw new Exception("El arreglo pasado no se encuentra creado correctamente", 111);
        }
    }
    private function estructuraInsert($data=array()){
            
        if(count($data)<1){
            $data = $this->propiedades;
            
        }
        
        foreach($data as $campo => $valor) {
            if ($campo != $this->pk) {
                
                switch ($valor) {
                    case '':
                        if(!is_numeric($valor)){
                            $valores[]="null";
                        } else {
                            $valores[]=$valor;
                        }
                        break;
                    default:
                        $valores[]="'".$valor."'";
                        break;
                }
            }
        }//fin foreach
        if($this->registroMomentoGuardado===TRUE){
            $valores[]= "'".FechaHora::datetime()."'";
            $valores[]= "'".FechaHora::datetime()."'";
        }
        return $valores;
    
        
    }//fin crearInsert
    private function modificar(){
        
    }
    /**
     * Permite guardar multiples registros
     * @method guardarTodos
     */
    
}
