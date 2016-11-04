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
 * @required Cadenas.class
 */

namespace Jida\BD;
use ReflectionClass;
use ReflectionProperty;
class DBContainer {

    protected $fecha_creacion;
    protected $fecha_modificacion;

    /**
     * Instancia del manejador de base de datos
     *
     * @access protected
     * @var object $bd
     */
    protected $bd;

    /**
     * Nombre de la tabla instanciada
     *
     * @var $nombretabla
     */
    protected $nombreTabla;

    /**
     * Propiedades publicas de la clase
     * @var array $propiedadesPublicas
     * @access protected
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
     * define si se deben registrar las fechas de guardado fecha_creacion
     * y fecha_modificacion
     *
     * @var boolean $registroMomentoGuardado
     */
    protected $registroMomentoGuardado = FALSE;

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
     * Define el manejador de base de datos (mysql o psql)
     * @var string $manejadorBD
     */
    private $manejadorBD;
    /**
     * Define la clase u objeto Instanciado
     * @var $clase
     * @access private
     */
    private $clase;
    /**
     * Define el nivel de navegación entre objetos al ser instanciado
     * @var int $nivelORM
     * @see DBCONTAINER_NIVEL_ORM
     */
    private $nivelORM = NIVEL_ORM;

    /**
     * Registra las propiedades del objeto que tambien son objetos
     * @var $propiedadesObjetos
     */
    private $propiedadesObjetos =array();
    /**
     * Define si se deben convertir los caractes especiales HTML en su código ascii
     * al momento de guardar en base de datos
     * @example
     * Código queria C&oacute;digo
     *
     * @var $convertirAscciToBD
     */
    private $convertAsciiToBD=TRUE;
    /**
     * Define si se deben convertir los caractes especiales HTML en su código ascii
     * al momento de traer data de base de datos
     * @example
     * Código queria C&oacute;digo
     *
     * @var $convertirAscciToBD
     */
    private $convertAsciiFromBD=TRUE;
    /**
     * Define las propiedades del objeto que son referencias a otro objeto
     * @var array $referenciasObjeto
     * @access private
     */
    private $referenciasObjeto = array();
    /**
     * Contructor del BDContainer
     *
     * Inicializa el objeto de conexión a base de datos para
     * la funcionalidad completa de la clase.
     * @method __construct()
     * @param mixed $id Identificador del objeto modelo si es instanciado por el usuario, Array con propiedades
     * si es llamado por el ORM
     * @param object $clase Objeto clase que hereda del DBContainer
     */
    public function __construct($clase = "", $id = "") {
        //instanciar objeto de base de datos

        $this->initBD();
        if(!empty($clase)){
            $this->obtenerPropiedadesObjeto();
            $this->clase = $clase;
        }
        $this->clavePrimaria = $this->obtenerClavePrimaria();
        if (!empty($id)) {
            $this->inicializarObjeto($id);
        }
    }
    /**
     * Permite realizar consultas de base de datos
     * @method obt
     * @access protected
     */
    protected function consulta($campos=""){

        $query  = new Query($this->nombreTabla,$this->propiedadesPublicas);
        return $query->consulta($campos);
    }
    /**
     * Inicializa el objeto correspondiente para el manejo de la base de datos
     * @method initBD
     */
    private function initBD(){
        if (!defined('manejadorBD')) {
            return false;
        }
        $this->manejadorBD = manejadorBD;
        switch ($this->manejadorBD) {
            case 'PSQL' :
                include_once 'Psql.class.php';
                $this->bd = new Psql ($this->configuracionBD);
                break;
            case 'MySQL' :
                // include_once 'Mysql.class.php';
                $this->bd = new Mysql ();
                break;
        }
    }

    /**
     * Verifica que clases son identificadas como
     */
    private function identificarReferencias(){
        foreach ($this->propiedadesPublicas as $prop => $val) {

            if (substr($prop, 0,2)=='id' and $prop!=$this->clavePrimaria){
                $propiedad = str_replace("id_", "", $prop);
                $objeto =Cadenas::upperCamelCase(str_replace("_", " ", $propiedad));
                if($propiedad!=$this->clase and class_exists($objeto,FALSE))
                    $this->propiedadesObjetos[$propiedad]=new $objeto();
            }
        }
    }

    /**
     * Inicializa un objeto a partir de Base de Datos
     * @method inicializarObjeto
     * @param $clase metodo magico __CLASS__
     */
    protected function inicializarObjeto($id) {
        if(is_array($id)){

        }else{
            $this->identificarReferencias();
            $data = $this->consulta()
                    ->filtro([$this->clavePrimaria=>$id])
                    ->fila();
            $this->establecerAtributos ( $data, $this->clase );
        }
    }//fin función inicializaarObjeto

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
    function setEstablecerAtributos($arr,$clase=""){
        $this->establecerAtributos($arr,$clase);
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

        if($momentoGuardado==FALSE){
            $momentoGuardado = ($this->registroMomentoGuardado===TRUE) ?TRUE:FALSE;
        }
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
        $clave=  $this->clavePrimaria;
        $this->$clave = $result['idResultado'];

        $retorno = $result;
        $retorno['result'] = $result;
        $retorno['accion'] =  $accion;
        return $retorno;
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

    }//fin funcion insertarObjeto


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
        $clase = $this->clase;
        if(!empty($clase)){
            $clavePrimaria = ($this->clavePrimaria != "") ? $this->clavePrimaria : "id_" . $clase;
            return strtolower($clavePrimaria);
        }

    }

    /**
     * Elimina un Registro
     *
     * Elimina un Registro de Una Tabla
     *
     * @param array $datos el key es el campo el value los balores
     * @return boolean Retorna True O False si la Consulta se Ejecuta
     */
     function eliminarDatos($datos) {
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
     * @return boolean ;
     */
    function eliminarMultiplesDatos($arrayDatos, $campo) {
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
     * Esta funcion puede ser utilizada solo si es un objeto instanciado
     * @method eliminarObjeto
     */
    function eliminarObjeto($id="") {
        if(empty($id)){
            $id = $this->$$this->clavePrimaria;
        }


        $query = "delete from $this->nombreTabla where $this->clavePrimaria = " . $id;
        if ($this->bd->ejecutarQuery ( $query )) {
            return true;
        } else {
            return false;
        }
    }


    private function validarTipoDatoSQL(){

    }
    /**
     * Devuelve los datos de la tabla
     * @method getTabla
     * @deprecated Esta función será reemplazada posteriormente por el metodo $get
     *
     */
    function getTabla($campos=null,$where,$order="",$claveArreglo=""){
        if(!is_array($campos)){
            $campos = array_keys($this->propiedadesPublicas);
            $selectCompleto=TRUE;
        }else{

        }
        $query ="Select ";
        $cont=0;
        foreach ($campos as $key => $value) {
            if($cont>0){
                $query.=",";
            }
            $query.="$value";
            $cont++;
        }//fin
        $query.=" from $this->nombreTabla";
        if(is_array($where)){
            $i=0;
            $query.=" where";
            foreach ($where as $campo => $valor) {
                if($i>0)
                    $query.=" and ";
                $query.=" $campo = '$valor'";
                $i++;
            }
        }
        if(!empty($order)){
            $query.="order by ".$order;
        }
        return $this->bd->obtenerDataCompleta($query,$claveArreglo);
    }//fin
    /**
     * Registra objetos a partir de los valores pasados
     * @method insert
     * @var mixed $campos Cadenas:: de campo a pasar por valor o arreglo de multiples campos
     * @var array $valores Matriz Que permite pasar multiples valores a insertar, la función intentara
     * insertar un valor por cada posición del arreglo
     * @example $objeto->insert('campo1',array(1,2,3,4,5)). Esto creara 5 registros donde la columna campo1 tendra
     * los valores pasados por el array sucesivamente
     */
    function insert($campos,$valores){
        $insert = "insert into $this->nombreTabla";
        if(is_array($campos) or is_null($campo)){
            if(is_null($campos))
                $campos = $this->propiedadesPublicas;
            $insert.="(".implode(", ", $campos).")";
        }elseif(is_string($campos)){
            $insert.=" ( $campos )";
        }
        $insert.= "values ";
        $i=0;
        foreach ($valores as $key => $value) {
            if($i>0)$insert.=",";
            $insert.="(";
            if(is_array($value)){
                $cont=0;
                foreach($value  as $key => $v){
                    if($cont>0) $insert.=",";
                    $insert.="'$v'";
                    ++$cont;
                }
            }else{
                $insert.="'$value'";
            }
            $insert.=")";
            $i++;
        }

        return $this->bd->ejecutarQuery($insert);
    }//final funcion insert

    private function crearMetodos(){

    }

    function getPropiedadesPublicas(){

        return $this->propiedadesPublicas;
    }
    function getFechaCreacion(){
        return $this->fecha_creacion;
    }
    function getFechaModificacion(){
        return $this->fecha_modificacion;
    }
    /**
     * Permite acceder a propiedades privadas o protegidas del objeto instanciado
     * @method _get()
     * @param string $propiedad Nombre de la propiedad a obtener
     */
    function _get($propiedad){
        if(property_exists($this, $propiedad)){
            return $this->$propiedad;
        }else{
            throw new Exception("La propiedad solicitada no existe ".$propiedad, 123);
        }
    }

    function __get($propiedad){
        return $this->_get($propiedad);
    }

    function salvarTodos($array){
        if(is_array($array)){

        }else{
            throw new Exception("El valor pasado debe ser un array", 1);

        }

    }
}