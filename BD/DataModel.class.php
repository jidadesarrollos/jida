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
    /**
     * Permite registrar validaciones para registros unicos
     * @var array $unico
     * @example ['nombre',['valor1','valor2'],'valor3'];
     */
    protected $unico=array();
    protected $registroMomentoGuardado=TRUE;
    protected $registroUser=TRUE;
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
     * Registra los valores iniciales al realizar una instancia de base de datos
     * @var array $valoresIniciales
     */
    private $valoresIniciales=array();
    /**
     * Nombre de la clase instanciada
     * @var $_clase
     * @access private
     * 
     */
     private $_clase;
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
        $this->_clase = get_class($this);
        $this->initBD();
        
        if(empty($this->tablaBD)){
            throw new Exception("Debe definir el nombre de la tabla de base de datos", 1);
                
        }
        if(empty($this->pk)){
            $this->obtenerpk();
        }
        $this->obtenerPropiedadesObjeto();
        
        if($id){
            $this->instanciarObjeto($id);   
        }
        
    }
    /**
     * Inicializa un objeto a partir de Base de Datos
     * @method inicializarObjeto
     * @param int $id Identificador unico del registro;
     */
    function instanciarObjeto($id) {
        
        $this->identificarPropertyObjects();
            $data = $this->consulta()
                    ->filtro([$this->pk=>$id])
                    ->fila();
            $this->valoresIniciales = $data;
            $this->establecerAtributos ( $data, $this->_clase );
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
                if($propiedad!=$this->_clase and class_exists($objeto))
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
            $clase=$this->_clase;
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
    
    protected function select($campos=""){
          return $this->consulta();
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
                    
                    $tabla = $object->__get('tablaBD');
                    $pk = $object->__get('pk');
                    $props= $object->__get('propiedades');
                    
                }elseif(property_exists($object, 'nombreTabla')){
                    $tabla = $object->__get('nombreTabla');
                    $pk = $object->__get('pk');
                    $props = $object->__get('propiedadesPublicas');
                }//fin if
                    $camposTabla = array_keys($props);
                    array_walk($camposTabla,function(&$ele,$clave,$tabla){
                                                    $ele = $tabla.".".$ele;
                                    },$tabla);
                    if($i>0)
                        $camposJoin.=",";
                    $camposJoin .=" ".implode(", ",$camposTabla);
                if(!empty($tabla) and !empty($pk)){
                    $join .= sprintf(" JOIN %s on (%s.%s = %s.%s)",$tabla,$this->tablaBD,$pk,$tabla,$pk);
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
        $clase = $this->_clase;
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
    protected function fila(){
        
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
            return $this->insertar();
            
        }else{
            return $this->modificar();
        }
        //return $this->resultBD->setValores($this);
    }
    /**
     * Crea un nuevo registro unico
     * @method insertar
     * @param array $data Data a insertar
     */
    private function insertar($data=""){
        if(!$this->verificarUnicos()->esUnico()){
            
        
              $data = $this->estructuraInsert();
              $insert = sprintf("insert into %s (%s) VALUES (%s)",
                                $this->tablaBD,
                                implode(",",$this->obtenerCamposQuery()),
                                implode(",",$data));
            if($this->bd->insertar($insert)){
                $pk = $this->pk;
                $this->$pk = $this->bd->idResult;
                $this->resultBD->setValores($this);
                
            }
        }else{
            $this->resultBD->__set('ejecutado', false);
        }
        return $this->resultBD;
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
        }if($this->registroUser){
            $campos[]="id_usuario_creador";
            $campos[]="id_usuario_modificador";
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
        if($this->registroUser){
            
            $idUser = (Session::checkLogg())?Session::get('usuario','id_usuario'):0;
            $valores[] =$idUser;
            $valores[] =$idUser;
        };
        return $valores;
    
        
    }//fin crearInsert
    private function modificar(){
        $dataUpdate = array_diff($this->propiedades,$this->valoresIniciales);
        if($this->registroUser){
            $idUser = Session::get('id_usuario');
            $dataUpdate['id_usuario_modificador'] =(Session::checkLogg())?Session::get('usuario','id_usuario'):0;
        };
        
        $update = "UPDATE $this->tablaBD SET ";
        $i=0;
        foreach ($dataUpdate as $campo => $valor) {
            if($i>0) $update.=",";
            $update.=" $campo='$valor'";
            ++$i;
        }
        
        $pk = $this->pk;
        $update.=" WHERE $this->pk=".$this->$pk;
        
        $this->query = $update;
        if($this->bd->ejecutarQuery($this->query)){
            $this->resultBD->setValores($this);
        }
        return $this->resultBD;
        
    }
    /**
     * Elimina uno o multiples registros de base de datos
     * 
     * Si no se pasa ningun elemento se eliminará el objeto instanciado.
     * @method eliminar
     * @param array [$arrayDatos ] Arreglo de valores a ser eliminados
     * @param string $campo Campo o propiedad por medio de la cual se eliminaran los objetos, si no es pasado sera usada
     * la clave primaria.
     */
    function eliminar($arrayDatos="",$campo=""){
        $totalParams = func_num_args();
        if(empty($campo))
            $campo = $this->pk;
        if($totalParams==0){
            $pk = $this->pk;
            $datos[]=$this->$pk;
        }else{
            $datos = array ();
            if(is_array($arrayDatos)){
                foreach ( $arrayDatos as $key => $value ) {
                    if (is_numeric ( $value )) {
                        $datos [] = "$value";
                    } else {
                        $datos [] = "\"$value\"";
                    }
                }
            }else{
                $datos[]=$arrayDatos;
            }
        }        
        $query = sprintf ( "DELETE FROM %s where $campo in (%s)", $this->tablaBD, implode ( ',', $datos ) );
        
        if ($this->bd->ejecutarQuery ( $query ))
            return true;
        else 
            return false;    
    }
    
    /**
     * Valida las restricciones unicas creadas por medio del array unico antes
     * de realizar una inserción.
     * @method verificarUnicos
     * @see self::unicos
     
     */
    private function verificarUnicos($datos=""){
        
         if(!is_array($this->unico))
            throw new Exception("No se ha creado correctamente el arreglo unico", 212);
         if(count($this->unico)>0){
             $filtro = array();
             foreach ($this->unico as $key => $valorUnico) {
                    if(is_array($valorUnico)){
                        $i=0;
                        $v="( ";
                        foreach ($valorUnico as $key => $valor) {
                            if($i>0)$v.=" and ";
                            if(!empty($valor)){
                                $v.= "$valor='".$this->propiedades[$valor]."'";
                                ++$i;
                            }
                        }
                        $filtro[]=$v." ) ";
                    }else{
                        $filtro[]="$valorUnico='".$this->propiedades[$valorUnico]."'";
                    }
             }
             $this->query = "select $this->pk from $this->tablaBD WHERE ".implode("or ",$filtro);
             $this->bd->ejecutarQuery($this->query);
             if($this->bd->totalRegistros>0){
                $this->resultBD->setValores($this)->setUnico(TRUE);    
             }else{
                $this->resultBD->setValores($this)->setUnico(false); 
             }
             
             
         }
         return $this->resultBD;
    }//fin función
    /**
     * Permite instanciar el objeto por medio de una propiedad;
     * @method getBy
     * @param mixed $valor Patrón de busqueda
     * @param string $propiedad de busqueda
     */
    function getBy($valor,$property=""){
        if(empty($property)) $property=$this->pk;
        if(array_key_exists($property, $this->propiedades)){
            $data = $this->consulta()
                        ->filtro([$property=>$valor])
                        ->fila();
            if($this->bd->totalRegistros>0){
                $this->valoresIniciales = $data;
                $this->establecerAtributos($data,$this->_clase);
                
                return $data;    
            }else{
                return false;
            }
            
        }else{
            throw new Exception("la propiedad pasada para obtener el objeto no existe", 124);
            
        }
        
    }
    /**
     * Retorna un arreglo con las propiedades publicas del objeto
     * @method objectAsArray
     * @return array 
     */
    function objectAsArray(){
        $this->obtenerPropiedadesObjeto();
        return $this->propiedades;
    }
   
}//fin clase;
