<?php
/**
 * Clase Padre de modelos
 *
 * @internal   Encargada de todo el manejo y logica para el ORM del Framework
 * @author     Julio Rodriguez
 * @package    Framework
 * @subpackage BD
 * @version    01
 * @category   Model
 */

namespace Jida\BD;

use Exception;
use Jida\Manager\Excepcion;
use Jida\Medios as Medios;
use Jida\Medios\Debug as Debug;
use Jida\Manager\Estructura;
use ReflectionClass;
use ReflectionProperty;

//include_once 'ResultBD.class.php';
class DataModel {

    use \Jida\Core\ObjetoManager;

    protected $debug = false;
    protected $tablaBD;
    protected $esquema;
    protected $manejadorBD;
    /**
     * Permite definir un prefijo utilizado en la tabla de base de datos
     *
     * Si se define un prefijo "r" el objeto "object" intentara buscar
     * los registros en la tabla "r_object"
     *
     * @var $prefijoBD
     */
    protected $prefijoBD = PREFIJO_TABLA;
    protected $fecha_creacion = FECHA_CREACION;
    protected $fecha_modificacion = FECHA_MODIFICACION;
    /**
     * @var int $nivelORM Define el nivel de navegación del ORM
     */
    protected $nivelORM = NIVEL_ORM;
    protected $prefijoRelacional = PREFIJO_RELACIONAL;
    /**
     * Permite registrar validaciones para registros unicos
     *
     * @var array $unico
     * @example ['nombre',['valor1','valor2'],'valor3'];
     */
    protected $unico = [];
    protected $registroMomentoGuardado = true;
    protected $registroUser = true;
    /**
     * Arreglo que define las relaciones uno a uno del objeto
     *
     * @var $tieneUno
     * @access  protected
     * @example [
     *
     * 'objetoRelacion',
     * // En caso de que la relacion no posea un nombre estandar o se encuentre
     * // declarada en el objeto instanciado.
     * 'objetoRelacion'=>['pk'=>'claveRelacion']
     * ]
     *
     *
     */
    protected $tieneUno = [];
    /**
     * Arreglo que define las relaciones uno a muchos de un objeto
     *
     * @var array $tieneMuchos
     */
    protected $tieneMuchos = [];
    /**
     * Arreglo que define las relaciones muchos a muchos del objeto
     *
     * @property $pertenece
     * @access protected
     *
     */
    protected $pertenece = [];
    /**
     * Registra la relacion inversa Uno a Muchos
     *
     * @property $pertenece
     * @access protected
     *
     */

    protected $perteneceAUno = [];
    /**
     * Nombre de la Clave primaria de base de datos
     *
     * @var string $pk
     */
    protected $pk;
    /**
     * Objeto de conexión a Base de datos
     *
     * @var object $bd
     */
    protected $bd;
    /**
     * Define la configuración a usar para la conexión a base de datos
     *
     * @internal Este valor debe corresponder a una propiedad declarada
     * en el objeto \App\Config\BD
     *
     * @var string $configuracionBD
     * @access   protected
     */
    protected $configuracionBD = "default";
    /**
     * @var boolean $usoBD Dedermina si es requerido el uso de base de datos
     */
    protected $usoBD = false;
    /**
     * Instancia de objeto para retorno de data de Base de Datos
     *
     * @param object $resultBD
     */
    protected $resultBD;
    /**
     * Nro de registros a mostrar por página
     *
     * @var int $filasPagina
     * @since 0.5
     */
    protected $filasPagina = 12;
    /**
     * Numero total de registros para una consulta.
     *
     * Si la consulta se pide paginada, el valor de esta propiedad conrrespondera
     * al total de los registros
     *
     * @var int $totalRegistros
     * @since 0.5
     */
    protected $_totalRegistros;
    private $consultaMultiple = false;
    private $join = false;
    private $usoLimit = false;
    private $_ce = '006';
    private $condicion = 'and';
    /**
     * @var string $limit estring de la clausula limit
     */
    private $_limit = "";
    /**
     * Consulta de base de datos construida
     *
     * @var $query
     */
    private $query;
    /**
     * Define el nivel actual del ORM
     */
    private $nivelActualORM = 0;
    private $tablaQuery;
    /**
     * Arreglo con las propiedades de base de datos del objeto
     *
     * @var array $propiedades
     */
    private $propiedades = [];
    /**
     * @var string $consultaRelaciones Registra el string de las consultas para
     * obtener la información de todas las relaciones creadas explicitamente en el objeto
     * instanciado
     */
    private $consultaRelaciones = [];
    /**
     * Permite identificar si la consulta contiene una clausula where;
     *
     * @var $usoWhere
     */
    private $usoWhere = false;
    /**
     * @property string $_groupby Registra la clausula order by de una consulta
     */
    private $_groupBy = "";
    /**
     * @var string $order Registra la clausula order de una sentencia a ejecutar
     */
    private $order = "";
    /**
     * Registra el numero de inserciones en el metodo salvarTodo
     *
     * @var int $totalInserciones
     */
    private $totalInserciones;
    /**
     * Arreglo de ids resultantes de una insercion múltiple
     *
     * @var $idsResultantes
     */
    private $idsResultantes = [];
    /**
     * Registra los valores iniciales al realizar una instancia de base de datos
     *
     * @var array $valoresIniciales
     */
    private $valoresIniciales = [];
    /**
     * Nombre de la clase instanciada
     *
     * @var $_clase
     * @access private
     *
     */
    private $_clase;
    /**
     * Objeto ReflectionClass instanciado con el objeto
     *
     * @var object Reflection
     */
    private $reflector;
    /**
     * Define si una consulta realizada deberá ser paginada
     *
     * @var boolean $paginar
     * @since 0.5
     */
    private $_paginar = false;
    /**
     * @var int $_paginaConsultada Numero de pagina consultada en la paginacion
     */
    private $_paginaConsultada = 1;
    /**
     * Numero de paginas resultante de una consulta paginada
     *
     * @var int $_paginas
     */
    private $_paginas;

    /**
     * Funcion constructora
     * @method __construct
     */
    private static $instancia;

    function __construct($id = false) {

        $numeroParams = func_num_args();
        $this->_validarBD();
        $param = func_get_args(0);
        $this->_clase = get_class($this);

        $this->namespace = Estructura::$namespace;
        //instancia objecto reflection
        $this->reflector = new ReflectionClass(get_class($this));

        if (empty($this->pk)) {
            $this->obtenerpk();
        }
        //si se pasa un segundo parametro, el mismo es el nivel del ORM

        //Se obtienen propiedades publicas
        $this->obtenerPropiedadesObjeto();

        if ($numeroParams > 1) {
            if (func_get_arg(1)) {

                if (is_array(func_get_arg(1))) {
                    $this->debug(func_get_arg(1), 1);
                    //$this->nivelORM = array_keys(func_get_arg(1))[0];
                    $this->nivelActualORM = func_get_arg(1)[array_keys(func_get_arg(1))[0]] + 1;
                }
                else {
                    $this->nivelActualORM = func_get_arg(1) + 1;
                }

            }
        }
        else {

            //se obtienen propiedades de relacion de pertenencia

        }
        if ($id) {
            $this->instanciarObjeto($id);
        }
        else {
            $this->instanciarTieneUno()->instanciarTieneMuchos();
        }

        if ($this->bd) {
            $this->bd->mantener = false;
            $this->bd->cerrarConexion();
        }

    }

    /**
     * @since 0.6.1
     *
     */
    function _validarBD() {

        $this->usoBD = !!class_exists('\App\Config\BD');

        if ($this->usoBD) {

            $this->tablaQuery = $this->tablaBD;
            $bd = new \App\Config\BD();
            $this->manejadorBD = $bd->manejador;
            $this->initBD();
            $this->bd->mantener = true;

        }

        $this->usoBD = $this->manejadorBD;

    }

    /**
     * Inicializa el objeto correspondiente para el manejo de la base de datos
     * @method initBD
     */
    private function initBD($manejador = "") {

        if (!empty($manejador))
            $this->manejadorBD = $manejador;
        if (empty($this->manejadorBD)) {
            throw new Exception("No se encuentra definido el manejador de base de datos", 1);
        }
        switch ($this->manejadorBD) {
            case 'PSQL' :
                #include_once 'Psql.class.php';
                $this->bd = new Psql ($this->configuracionBD);
                break;
            case 'MySQL' :
                #include_once 'Mysql.class.php';
                $this->bd = new Mysql($this->configuracionBD, $this->_clase);
                break;
            default:
                throw new Exception("No se ha definido correctamente el manejador de base de datos", 3);

        }

        $this->resultBD = new ResultBD($this);
    }

    /**
     * Obtiene el nombre de la clave primaria de la tabla de base de datos
     *
     * El nombre es construido en base al estandard
     *
     * @var obtenerpk
     */
    private function obtenerpk() {

        $clase = $this->_clase;
        if (!empty($clase)) {
            $pk = ($this->pk != "") ? $this->pk : "id_" . $clase;
            $this->pk = strtolower($pk);
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

        $this->reflector = new ReflectionClass($this->_clase);
        $propiedades = $this->reflector->getProperties(ReflectionProperty::IS_PUBLIC);
        $arrayPropiedades = [];

        foreach ($propiedades as $propiedad) {
            if (!$propiedad->isStatic()) {
                $arrayPropiedades[$propiedad->getName()] = $propiedad->getValue($this);
            }
        }
        $this->propiedades = $arrayPropiedades;

    }

    private function debug($data, $string = true, $condicion = false) {

        if ($this->debug) {
            ($string === true) ? Debug::string($data, $condicion) : Debug::mostrarArray($data, $condicion);
        }

        return $this;
    }

    /**
     * Inicializa un objeto a partir de Base de Datos
     * @method inicializarObjeto
     *
     * @param int $id Identificador unico del registro;
     */
    private function instanciarObjeto($id, $data = []) {

        if (count($data) < 1) {
            $data = $this->__obtConsultaInstancia($id)->fila();
        }

        $this->valoresIniciales = $data;

        $this->establecerAtributos($data, $this->_clase);

        if ($this->nivelActualORM <= $this->nivelORM) {

            $this->identificarObjetosRelacion();
            $this->obtenerDataRelaciones();
        }

        return $this;
    }

    function __obtConsultaInstancia($id) {

        return $this->consulta()->filtro([$this->pk => $id]);
    }

    /**
     * Permite realizar un filtro de la consulta a realizar
     * @method filtro
     *
     * @param array $arrayFiltro [opcional] el key es el campo y el value el valor a filtrar
     * @param array $arrayOr [opcional] Permite definir una condicion or de multiples valores
     *
     * @return object $this Objeto DataModel instanciado
     *
     *
     */

    function filtro($arrayFiltro = [], $arrayOr = []) {

        $this->where();
        if (is_array($arrayFiltro)) {
            $i = 0;
            $o = 0;

            foreach ($arrayFiltro as $key => $value) {
                if ($i > 0)
                    $this->query .= " and ";

                if (is_array($value)) {

                    if (!strpos($key, ".")) {

                        if (empty($value[0]) || $value[0] == '')
                            $this->query .= $key . $value[1] . " ";
                        else
                            $this->query .= "$this->tablaQuery.$key" . $value[1] . '\'' . $value[0] . "' ";

                    }
                    else {
                        $this->query .= $key . $value[1] . $value[0] . " ";
                    }
                }
                else {

                    if (!strpos($key, "."))
                        $this->query .= "$this->tablaQuery.$key='$value'";
                    else
                        $this->query .= "$key='$value'";
                }

                ++$i;
            }

            if (is_array($arrayOr) and count($arrayOr) > 0) {
                $this->query .= " or (";
                foreach ($arrayOr as $key => $value) {
                    if ($o > 0)
                        $this->query .= " and ";

                    if (!strpos($key, "."))
                        $this->query .= " $this->tablaQuery.$key='$value'";
                    else
                        $this->query .= $key . "='$value'";
                    ++$o;
                }
                $this->query .= ")";
            }
        }
        else {
            throw new Exception("No se ha definido correctamente el filtro", 201);
        }

        return $this;
    }

    /**
     * Agrega la clausula where a la consulta
     * @method where
     */
    private function where($condicion = " and") {

        if (!$this->usoWhere) {
            $this->query .= " where ";
            $this->usoWhere = true;
        }
        else {
            $this->query .= " $condicion ";
        }
    }

    /**
     * Funcion para obtener datos de una tabla
     * @method consulta
     *
     * @param array $campos Nombre de los campos a obtener
     * @param mixed $pagina [Opcional] Si es pasado algun valor, la
     *                      consulta será paginada segun los valores de las propiedasdes
     *                      $filasPagina
     *
     */
    function consulta($campos = "", $nroPaginacion = false) {

        $banderaJoin = false;
        $join = "";

        if (empty($campos) or $campos == '*') {
            $campos = array_keys($this->propiedades);
        }

        if (is_array($campos)) {

            array_walk($campos,
                function (&$key, $valor, $tabla) {
                    $key = $tabla . "." . $key;
                },
                $this->tablaQuery);

            $campos = implode(", ", $campos);
        }
        if ($nroPaginacion !== false) {
            $this->paginar($nroPaginacion);
        }
        if ($this->consultaMultiple)
            $this->query .= "SELECT $campos ";
        else $this->query = "SELECT $campos ";
        if ($banderaJoin === true)
            $this->query .= ", " . $campos; //TODO: Originalmente existia $camposJoin
        $this->query .= " from $this->tablaQuery " . $join;
        $this->usoWhere = false;

        return $this;
    }

    /**
     * Genera una consulta paginada
     * @method paginar
     *
     * @since 0.5
     */
    function paginar($pagina) {

        $this->_establecerPaginacion($pagina);

        return $this;
    }//fin función inicializarObjeto

    /**
     * Activa la configuracion para una consulta paginada
     * @method pagina
     *
     * @param int $nroPagina Numero de la pagina que se desea de la consulta paginada
     */
    private function _establecerPaginacion($nroPagina = 1) {

        $this->_paginaConsultada = $nroPagina;
        $this->_paginar = true;

        return $this;
    }

    private function identificarObjetosRelacion() {

        foreach ($this->propiedades as $prop => $value) {
            if (substr($prop, 0, 2) == 'id' and $prop != $this->pk) {

                $objeto = Medios\Cadenas::upperCamelCase(str_replace("_", " ", str_replace("id_", "", $prop)));
                if (class_exists($objeto) and !in_array($objeto, $this->tieneUno) and !array_key_exists($objeto,
                        $this->tieneUno))
                    $this->tieneUno[$objeto] = [
                        'obj' => $objeto,
                        'pk'  => $prop
                    ];
            }
        }
    }
    /**
     * obtiene las relaciones declaradas uno a muchos del objeto
     * @method obtenerPertenencias
     */

    /**
     * Verifica las relaciones declaradas del Objeto
     */
    protected function obtenerDataRelaciones() {

        $this->obtTieneUno()->obtTieneMuchos()->obtPerteneceAUno()->obtPertenece()->instanciarRelaciones();

    }

    /**
     * Define todas las propiedades de relación del objeto instanciado
     * @method instanciarRelaciones
     */
    private function instanciarRelaciones() {

        $this->bd->mantener = true;

        $data = $this->bd->obtenerDataMultiQuery(
            $this->bd->ejecutarQuery(implode(";", $this->consultaRelaciones), 2),
            array_keys($this->consultaRelaciones));
        $this->bd->mantener = false;
        $this->bd->cerrarConexion();
        foreach ($data as $relacion => $info) {

            $claseSola = $this->obtClaseNombre($relacion);

            if (in_array($relacion, $this->tieneMuchos) or array_key_exists($relacion,
                    $this->tieneMuchos) or array_key_exists($relacion,
                    $this->pertenece)) {

                $this->{$this->obtClaseNombre($claseSola)} = [];
                if ($info['totalRegistros'] > 0) {

                    foreach ($info['result'] as $key => $value) {
                        $this->{$claseSola}[$key] = $value;

                    }
                }

            }
            else if (in_array($relacion, $this->tieneUno) or array_key_exists($relacion, $this->tieneUno)) {

                $rel = new $relacion();
                if ($info['totalRegistros'] > 0) {
                    $rel->__establecerAtributos($info['result'][0]);
                }

                $this->{$claseSola} = $rel;

            }
            else if (array_key_exists($relacion, $this->perteneceAUno)) {

                $rel = new $relacion();
                if (array_key_exists(0, $info['result']))
                    $rel->__establecerAtributos($info['result'][0]);
                else {

                }
                $this->{$claseSola} = $rel;

            }
            else {

                $this->debug("no existe $relacion");
            }
        }//fin foreach
        $this->instanciarTieneUno();

    }

    /**
     * Instancia las relaciones uno a uno de un objeto
     *
     * Crea un objeto vacio para cada relacion "tieneUno" definida en un objeto nuevo
     * @method instanciarTieneUno
     *
     */
    private function instanciarTieneUno() {

        foreach ($this->tieneUno as $key => $class) {

            if (!is_string($class) and is_string($key) and (class_exists($key) and !property_exists($this, $key))) {

                if (is_string($key) and is_array($class)) {
                    $relacion =& $key;

                    $explode = explode('\\', $key);
                    $nombreClass = array_pop($explode);

                    if (array_key_exists('objeto', $class)) {
                        $relacion = $class['objeto'];
                    }

                    if (array_key_exists('fk', $class))
                        $this->$nombreClass = new $relacion($this->{$class['fk']}, $this->nivelActualORM);

                }
                else {
                    throw new Exception("No se encuentra definida correctamente la relacion para " . $this->_clase, 1);
                }
            }
            else {
                if (is_string($class) and class_exists($class) and !property_exists($this, $class)) {

                    $explode = explode('\\', $class);
                    $nombreClass = array_pop($explode);

                    $obj = new $class();
                    $this->{$nombreClass} = new $class(null, $this->nivelActualORM);

                }
            }

        }

        return $this;
    }//fin indentificarRefereincas

    /**
     * Genera las consultas para las relaciones MaN
     * @method obtPertenece
     *
     * @since 0.5
     */
    private function obtPertenece() {

        $dataOrm = ($this->nivelORM > NIVEL_ORM) ? [$this->nivelORM => $this->nivelActualORM] : $this->nivelActualORM;

        foreach ($this->pertenece as $nombreRelacion => $data) {
            if (is_array($data)) {
                $nombreObj = (array_key_exists('objeto', $data)) ? $data['objeto'] : $nombreRelacion;
                $objRelacion = new $nombreObj();
                $campos = array_key_exists('campos', $data) ? $data['campos'] : '';

                $objRelacion->consulta($campos);
                $relacion = (array_key_exists('relacion', $data)) ? $data['relacion'] : false;
                $camposRelacion = (array_key_exists('campos_relacion', $data)) ? $data['campos_relacion'] : [];
                if ($relacion) {
                    $objRelacion->join($relacion,
                        $camposRelacion)->filtro([$relacion . "." . $this->pk => $this->{$this->pk}]);
                }

                $this->consultaRelaciones[$nombreRelacion] = $objRelacion->obtQuery();
            }
        }

        return $this;
    }

    private function obtPerteneceAUno() {

        foreach ($this->perteneceAUno as $key => $relacion) {

            $rel = new $key();
            $this->consultaRelaciones[$key] = $rel->consulta()->filtro([$relacion['pk'] => $this->{$relacion['pk']}])->obtQuery();

        }

        return $this;
    }

    /**
     * Genera la consulta para las relaciones 1 : M del Objeto
     *
     * @method obtTieneMuchos
     *
     */
    private function obtTieneMuchos() {

        $dataOrm = ($this->nivelORM > NIVEL_ORM) ? [$this->nivelORM => $this->nivelActualORM] : $this->nivelActualORM;

        foreach ($this->tieneMuchos as $nombreRelacion => $data) {

            if (is_array($data)) {

                $nombreObj = (array_key_exists('objeto', $data)) ? $data['objeto'] : $nombreRelacion;

                $objRelacion = new $nombreObj();
                $campos = array_key_exists('campos', $data) ? $data['campos'] : '';
                $objRelacion->consulta($campos);

                $relacion = false;
                if ((array_key_exists('relacion', $data))) {

                    $explode = explode('\\', $data['relacion']);

                    if (count($explode) > 1) {
                        $objJoin = new $data['relacion']();
                        $relacion = $objJoin->tablaBD;
                    }
                    else {
                        $relacion = $data['relacion'];
                    }
                }

                $camposRelacion = (array_key_exists('campos_relacion', $data)) ? $data['campos_relacion'] : [];

                if ($relacion) {

                    $objRelacion
                        ->join($relacion, $camposRelacion)
                        ->filtro(["{$relacion}.{$this->pk}" => $this->{$this->pk}])
                        ->agrupar($camposRelacion);
                }
                else {
                    //entra aqui si es una relacion 1 -> N
                    $objRelacion->filtro([$this->pk => $this->{$this->pk}]);
                }

            }
            else {
                //logica repetida?
                $objRelacion = new $data();
                $relacion = $objRelacion->__get('tablaBD');
                $camposRelacion = array_keys($objRelacion->obtenerPropiedades());
                $nombreRelacion = $data;
                $objRelacion->consulta()->filtro([$this->pk => $this->{$this->pk}]);
            }

            $this->consultaRelaciones[$nombreRelacion] = $objRelacion->obtQuery();
        }

        return $this;
    }//fin metodo initBD

    /**
     * Genera las consultas para las relaciones 1:1 del Objeto
     *
     * Genera las consultas de las relaciones 1 a 1 del objeto donde el objeto
     * es el objeto padre de la cardinalidad
     * @method
     */
    private function obtTieneUno() {

        $dataOrm = ($this->nivelORM > NIVEL_ORM) ? [$this->nivelORM => $this->nivelActualORM] : $this->nivelActualORM;
        foreach ($this->tieneUno as $key => $relacion) {

            if (is_string($relacion) and class_exists($relacion)) {

                $rel = new $relacion();
                $this->consultaRelaciones[$relacion] = $rel->consulta()->filtro([$this->pk => $this->{$this->pk}])->obtQuery();

            }
            else if (is_string($key) and class_exists($key)) {
                $rel = new $key();

                if (array_key_exists('fk', $relacion)) {

                    $this->consultaRelaciones[$key] = $rel->consulta()->filtro([
                            $rel->pk => $this->{$relacion['fk']}]
                    )->obtQuery();

                }

            }

        }

        return $this;
        //	$this->debug($consultas);
    }

    private function instanciarTieneMuchos() {

        foreach ($this->tieneMuchos as $key => $value) {
            if (!is_array($value)) {
                $explode = explode('\\', $value);
                $class = array_pop($explode);

                $this->{$class} = [];

            }
            else {
                $explode = explode('\\', $key);
                $class = array_pop($explode);
                $this->{$key} = [];
            }
        }
    }

    /**
     * Permite instanciar un objeto ya inicializado
     * @method instanciar
     *
     */
    function instanciar($id, $data = []) {

        return $this->instanciarObjeto($id, $data);
    }

    /**
     * Permite acceder a propiedades privadas o protegidas del objeto instanciado
     * @method __get()
     *
     * @param string $propiedad Nombre de la propiedad a obtener
     */
    function __get($propiedad) {

        if (property_exists($this, $propiedad)) {

            return $this->$propiedad;
        }
        else {
            throw new Exception("La propiedad " . $propiedad . " solicitada no existe", 123);
        }
    }

    function __establecerAtributos($arr) {

        $this->establecerAtributos($arr, $this->_clase);

        return $this;
    }

    /**
     * Agrega la union de campos al query
     * @method join
     *
     * @param string $clase tabla o modelo con el que se desea unir
     * @param mixed $campos Campo o Campos a solicitar de la tabla join
     */
    function join($clase, $campos = "", $data = [], $tipoJoin = "") {

        $tablaRelacion = $this->tablaBD;

        if (class_exists($clase)) {

            $clase = new $clase();
            $tablaJoin = $clase->__get('tablaBD');
            $clavePrimaria = $clase->__get('pk');
            $clave = $this->pk;
            $claveRelacion = $this->pk;
            if (empty($campos)) {
                $campos = array_keys($clase->obtenerPropiedades());
            }

        }
        else {
            $tablaJoin = $clase;
        }
        if (count($data) > 0) {
            if (array_key_exists('clave_relacion', $data)) {
                $claveRelacion = $data['clave_relacion'];
            }
            if (array_key_exists('clave', $data)) {
                $clave = $data['clave'];
            }
            else {
                $clave = $data['clave_relacion'];
            }
            if (array_key_exists('tabla_join', $data)) {
                $tablaRelacion = $data['tabla_join'];
            }

        }
        else {
            $clave = $claveRelacion = $this->pk;

        }

        if (!empty($campos)) {
            $_queryExplode = explode('from', $this->query);
            if (is_array($campos)) {
                $camposJoin = "";

                for ($i = 0; $i < count($campos); ++$i) {
                    if ($i > 0)
                        $camposJoin .= ", ";
                    $camposJoin .= $tablaJoin . "." . $campos[$i];
                }
            }
            else {
                $camposJoin = $campos;
            }

            $_queryExplode[0] .= ", " . $camposJoin;
            $this->query = implode(" from ", $_queryExplode);
        }
        $this->join = true;
        $joinParteA = $tablaJoin . '.' . $clave;
        $joinParteB = $tablaRelacion . '.' . $claveRelacion;
        if (strpos($claveRelacion, "."))
            $joinParteB = $claveRelacion;
        if (strpos($clave, '.'))
            $joinParteA = $clave;
        $this->query .= sprintf("%s JOIN %s on (%s=%s)", $tipoJoin, $tablaJoin, $joinParteA, $joinParteB);

        return $this;

    }

    /**
     * Emula el in de base de datos
     * @method in
     *
     * @return object $this Objeto instanciado
     * @var string $clave [opcional] Campo para realizar clausula, si se omite
     * será tomada la clave primaria
     * @var        $filtro Arreglo de campos a filtrar
     */
    function in($filtro, $clave = "", $condicion = "and") {

        $this->where($condicion);
        if (is_array($filtro)) {

            if (empty($clave))
                $clave = $this->tablaQuery . "." . $this->pk;

            else {
                if (!strpos($clave, ".")) {
                    $clave = $this->tablaQuery . "." . $clave;
                }
            }
            $this->query .= $clave . " in ('" . implode("','", $filtro) . "')";
        }

        return $this;
    }

    function consultaSola() {

        if (empty($campos)) {
            $campos = array_keys($this->propiedades);
        }
        if (is_array($campos)) {
            array_walk($campos,
                function (&$key, $valor, $tabla) {

                    $key = $tabla . "." . $key;
                },
                $this->tablaQuery);

            $campos = implode(", ", $campos);
        }

        $this->query = "SELECT $campos ";

        $this->query .= " from $this->tablaQuery ";
        $this->usoWhere = false;

        return $this;
    }

    function query($campos = [], $tabla) {

        if (count($campos) < 1)
            $campos = ["*"];
        $this->query = "SELECT ";
        $this->query .= implode(",", $campos);
        $this->tablaQuery = $tabla;
        $this->query .= " from " . $this->tablaQuery;
        $this->usoWhere = false;

        return $this;
    }

    /**
     * Permite registrar una relacion uno a uno del objeto
     *
     * @method agregar
     * @param string $relacion Nombre de la relacion. ejemplo Usuario -> Perfil
     * @param array $datos Datos a guardar
     */
    function agregar($relacion, $datos) {

        if (in_array($relacion, $this->tieneMuchos)) {
            $fk = $this->pk;
            $rel = new $relacion();
            $rel->establecerAtributos($datos);
            $rel->$fk = $this->$fk;

            return $rel->salvar();
        }

    }

    function agregarMuchos() {

    }

    /**
     * Permite acceder a las relaciones uno a uno y uno a muchos de un objeto
     *
     * @method obtener
     * @param string Nombre de la relacion
     *
     * @return array $datos Datos de la consulta
     */
    function obtener($relacion) {

        if (class_exists($relacion)) {
            $rel = new $relacion();
            $pk = $this->pk;

            return $rel->obtenerBy($this->$pk, $this->pk);
        }
        else {
            throw new Exception("el objeto $relacion solicitado no existe", 1);

        }

    }//fin función filtro

    /**
     * Realiza la agrupación de la consulta
     * @method agrupar
     *
     * @param mixed $agrupacion Campo o conjunto de campos por los que se desea agrupar
     *
     * @return object $this Objeto instanciado
     */
    function agrupar($agrupacion) {

        if (is_array($agrupacion))
            $this->_groupBy .= " group by " . implode(",", $agrupacion);
        else
            $this->_groupBy .= " group by " . $agrupacion;

        return $this;
    }

    function condicion($cond) {

        $this->condicion = $cond;

        return $this;
    }

    /**
     * Permite hacer una consulta like
     * @method like
     *
     * @param array $filtro
     * @param string $condicion or u and
     * @param int $tipo 1=intermedio,2=inicio,3=final
     */
    function like($arrayFiltro, $condicion = "or", $tipo = 1) {

        $this->where($this->condicion);

        if (is_array($arrayFiltro)) {
            $i = 0;
            $this->query .= "(";
            foreach ($arrayFiltro as $key => $value) {

                if (is_array($value)) {
                    $a = 0;

                    foreach ($value as $id => $valor) {
                        if ($a > 0)
                            $this->query .= " $condicion ";
                        $this->query .= "$key like";

                        switch ($tipo) {
                            case 1:
                                $this->query .= " '%$valor%'";
                                break;
                            case 2:
                                $this->query .= " '$valor%'";
                                break;
                            case 3:

                                $this->query .= " '%$valor'";
                                break;
                        }
                        ++$a;
                    }

                }
                else {
                    if ($i > 0)
                        $this->query .= " $condicion ";
                    $this->query .= "$key like";
                    switch ($tipo) {
                        case 1:
                            $this->query .= " '%$value%'";
                            break;
                        case 2:
                            $this->query .= " '$value%'";
                            break;
                        case 3:

                            $this->query .= " '%$value'";
                            break;
                    }
                }

                ++$i;
            }
            $this->query .= ")";
        }
        else {
            throw new Exception("No se ha definido correctamente el filtro", 200);
        }

        return $this;
    }

    /**
     * Permite hacer una consulta regExp
     *
     * Funcion que recibe el campo y la expresion regular para consultar.
     * $arrayFiltro contiene el campo a consultar y la expresion regular a utilizar
     * @method regExp
     *
     * @param array $arrayFiltro
     */
    function regExp($arrayFiltro) {

        $this->where();

        foreach ($arrayFiltro as $campo => $valor)
            $this->query .= " " . $campo . " regexp '$valor'";

        return $this;
    }

    /**
     * Retorna la data resultante de una consulta paginada
     * Retorna un arreglo con los siguientes keys : filasPagina, registros, pagina, paginas
     * @method dataPaginacion
     *
     * @return array
     * @since 0.5
     */
    function dataPaginacion() {

        return [
            'consulta'     => $this->query,
            'filasPagina'  => $this->filasPagina,
            'registros'    => $this->_totalRegistros,
            'paginaActual' => $this->_paginaConsultada,
            'paginas'      => $this->_paginas
        ];
    }//final función like

    function addConsulta() {

        $this->query .= ";";
        $this->consultaMultiple = true;
        $this->consulta();

        return $this;
    }//final función regExp

    /**
     * Retorna el resultado de multiples consultas
     *
     * Funcional para trabajar con Mysql. Retorna el resultado de multiples consultas solicitadas
     *
     * @see Mysql::mysqli_multi_query
     * @method obtMultiple
     *
     */
    function obtMultiple($keys) {

        $this->consultaMultiple = false;

        return $this->bd->obtenerDataMultiQuery($this->bd->ejecutarQuery($this->query, 2), $keys);

    }

    /**
     * Retorna todos los registros de Base de datos
     * @method obtenerTodo
     *
     * @param string $key valor a usar de key en la matriz devuelta
     *
     * @return array $data
     */
    function obtenerTodo($key = "", $order = "") {

        if (empty($order))
            $order = $this->pk;

        return $this->select()->order($order)->obt($key);
    }

    /**
     * Retorna una matriz como resultado de una consulta realizada
     *
     * @param string $key [opcional] campo de la consulta a usar como clave en la matriz resultante
     *
     * @return array $data Matriz resultante
     * @see BDObject::obtenerDataCompleta
     *
     */
    function obt($key = "") {

        if (!empty($this->_groupBy)) {
            $this->query .= ' ' . $this->_groupBy;
            $this->_groupBy = "";
        }
        if (!empty($this->order)) {

            $this->query .= " " . $this->order;
            $this->order = "";
        }
        if (!empty($this->_limit)) {
            if ($this->_paginar) {
                throw new Exception("No puede agregar la clausula limit a una consulta paginada", $this->_ce . '09');
            }
            $this->query .= " " . $this->_limit;
        }

        if ($this->_paginar) {
            $this->_paginarConsulta($key);
        }

        return $this->bd->obtenerDataCompleta($this->query, $key);
    }

    /**
     * Convierte una consulta en una consulta paginada
     * @method _paginarConsulta
     *
     * @since 0.5
     */
    private function _paginarConsulta($key) {

        $data = explode('from', $this->query);

        $qCount = 'SELECT count(*) from ' . array_pop($data);

        $data = $this->bd->obtenerDataCompleta($this->query, $key);

        $this->_totalRegistros = $this->bd->totalRegistros;
        $division = $this->_totalRegistros / $this->filasPagina;

        if (is_float($division))
            $division = ceil($division);

        $this->_paginas = $division;

        $inicio = ($this->_paginaConsultada == 0) ? 0 : $this->_paginaConsultada * $this->filasPagina;

        $fin = ($this->_paginaConsultada == 0) ? $inicio + $this->filasPagina : $inicio + $this->filasPagina;

        $this->query .= ' ' . $this->bd->limit($this->filasPagina, $inicio);

        //$this->limit($this->filasPagina,$inicio);

    }

    /**
     * Permite ordenar una consulta
     *
     * @method order
     * @param mixed $order nombre de campo o arreglo de campos por los que se desea ordenar
     * @param string $type Tipo de ordenado "asc" o "desc" por default es asc
     */
    function order($order, $type = 'asc') {

        if (is_array($order))
            $order = implode(",", $order);

        $campoOrden = strpos($order, '.') ? $order : $this->tablaQuery . "." . $order;
        $this->order = "Order by " . $campoOrden . " " . $type;

        return $this;
    }

    /**
     * Alias de metodo Consulta
     * @method select
     *
     * @see self::consulta
     */
    function select($campos = "") {

        $this->consulta($campos);

        return $this;

    }

    /**
     * Retorna un vector asociativo de un registro obtenido de base de datos
     * @method fila
     */
    function fila() {

        if (!empty($this->order))
            $this->query .= " " . $this->order;

        return $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery($this->query));
    }

    /**
     * Permite registrar el objeto actual
     * @method salvar
     */
    function salvar($data = "") {

        if (is_array($data)) {
            $this->establecerAtributos($data);
        }
        $this->obtenerPropiedadesObjeto();

        if (empty($this->propiedades[$this->pk])) {

            return $this->insertar();

        }
        else {
            return $this->modificar();
        }

        //return $this->resultBD->setValores($this);
    }

    /**
     * Crea un nuevo registro unico
     * @method insertar
     *
     * @param array $data Data a insertar
     */
    private function insertar($data = "") {

        if (!$this->verificarUnicos()->esUnico()) {

            $data = $this->estructuraInsert();

            $insert = sprintf("insert into %s (%s) VALUES (%s)",
                $this->tablaBD,
                implode(",", $this->obtenerCamposQuery()),
                implode(",", $data));

            if ($this->bd->insertar($insert)) {
                $pk = $this->pk;
                $this->$pk = $this->bd->idResult;
                $this->resultBD->setValores($this);

            }
        }
        else {
            $this->resultBD->__set('ejecutado', false);
        }

        #$this->bd->cerrarConexion();
        return $this->resultBD;
    }

    /**
     * Valida las restricciones unicas creadas por medio del array unico antes
     * de realizar una inserción.
     * @method verificarUnicos
     *
     * @see self::unicos
     */
    private function verificarUnicos($datos = "") {

        if (!is_array($this->unico))
            throw new Exception("No se ha creado correctamente el arreglo unico", 212);
        if (count($this->unico) > 0) {
            $filtro = [];
            foreach ($this->unico as $key => $valorUnico) {
                if (is_array($valorUnico)) {
                    $i = 0;
                    $v = "( ";
                    foreach ($valorUnico as $key => $valor) {
                        if ($i > 0)
                            $v .= " and ";
                        if (!empty($valor)) {
                            $v .= "$valor='" . $this->propiedades[$valor] . "'";
                            ++$i;
                        }
                    }
                    $filtro[] = $v . " ) ";
                }
                else {
                    $filtro[] = "$valorUnico='" . $this->propiedades[$valorUnico] . "'";
                }
            }

            $this->query = "select $this->pk from $this->tablaBD WHERE " . implode("or ", $filtro);

            $this->bd->ejecutarQuery($this->query);

            if ($this->bd->totalRegistros > 0) {
                $this->resultBD->setValores($this)->setUnico(true);
            }
            else {
                $this->resultBD->setValores($this)->setUnico(false);
            }

        }

        return $this->resultBD;
    }

    private function estructuraInsert($data = [], $insertPK = false) {

        if (count($data) < 1) {
            $data = $this->propiedades;
        }

        foreach ($data as $campo => $valor) {

            if (($campo != $this->pk) || $insertPK) {
                switch ($valor) {
                    case '':
                        if (!filter_var($valor, FILTER_VALIDATE_INT) and $valor !== 0) {
                            $valores[] = "null";
                        }
                        else {
                            $valores[] = $valor;
                        }
                        break;
                    default:
                        if (
                            strpos($valor, '0x') === FALSE and
                            !in_array($valor, $this->bd->getValoresReservados())
                        ) {
                            $valores[] = "'" . $this->bd->escaparTexto($valor) . "'";
                        }
                        else {
                            $valores[] = "'" . $valor . "'";
                        }
                        break;

                }
            }
        }//fin foreach
        if ($this->registroMomentoGuardado === true) {
            $valores[] = "'" . Medios\FechaHora::datetime() . "'";
            $valores[] = "'" . Medios\FechaHora::datetime() . "'";
        }

        if ($this->registroUser) {
            if (Medios\Sesion::obt('Usuario')) {
                $user = Medios\Sesion::obt('Usuario');
                if (is_array($user) and array_key_exists('id_usuario', $user))
                    $idUser = $user['id_usuario'];
                else if (is_object($user) and property_exists($user, 'id_usuario'))
                    $idUser = ($user->id_usuario > 0) ? $user->id_usuario : 0;
            }
            else {
                if (is_array(Medios\Sesion::obt('usuario')) and array_key_exists('id_usuario',
                        Medios\Sesion::obt('usuario')))
                    $idUser = Medios\Sesion::obt('usuario')['id_usuario'];
                else
                    $idUser = 0;

            }

            $valores[] = $idUser;
            $valores[] = $idUser;
        };

        return $valores;

    }

    /**
     * Obtiene los campos de Base de datos utilizados para realizar una inserción
     * o modificación.
     *
     * @method obtenerCamposQuery
     * @return array $campos
     */
    private function obtenerCamposQuery($campos = "", $unsetPK = true) {

        if (empty($campos) or !is_array($campos))
            $campos = $this->propiedades;

        if ($unsetPK)
            unset($campos[$this->pk]);

        $campos = array_keys($campos);
        if ($this->registroMomentoGuardado) {
            $campos[] = 'fecha_creacion';
            $campos[] = 'fecha_modificacion';
        }
        if ($this->registroUser) {
            $campos[] = "id_usuario_creador";
            $campos[] = "id_usuario_modificador";
        }

        return $campos;
    }

    private function modificar() {

        $dataUpdate = [];
        if (!is_array($this->valoresIniciales)) {
            $this->valoresIniciales = [];
        }

        $dataUpdate = array_diff_assoc($this->propiedades, $this->valoresIniciales);

        if (count($dataUpdate) > 0) {
            if ($this->registroUser) {
                $idUser = Medios\Sesion::obt('id_usuario');
                $dataUpdate['id_usuario_modificador'] = 0;
                if (Medios\Sesion::activa()) {
                    if (is_object(Medios\Sesion::obt('Usuario')))
                        $dataUpdate['id_usuario_modificador'] = Medios\Sesion::obt('Usuario')->id_usuario;
                    else if (array_key_exists('id_usuario', [Medios\Sesion::obt('usuario')])) {
                        $dataUpdate['id_usuario_modificador'] = Medios\Sesion::obt('usuario', 'id_usuario');
                    }
                }
            }

            $update = "UPDATE $this->tablaBD SET ";
            $i = 0;

            foreach ($dataUpdate as $campo => $valor) {
                if ($i > 0)
                    $update .= ",";
                switch ($valor) {
                    case '':
                        if (!is_numeric($valor)) {
                            $campoValor = "null";
                        }
                        else {
                            $campoValor = $valor;
                        }
                        break;

                    default:
                        if (!in_array($valor, $this->bd->getValoresReservados())) {
                            $campoValor = "'" . $this->bd->escaparTexto($valor) . "'";
                        }
                        else {
                            $campoValor = $this->bd->escaparTexto($valor);
                        }

                        break;
                }

                $update .= " $campo=$campoValor";
                ++$i;
            }

            $pk = $this->pk;
            $update .= " WHERE $this->pk=" . $this->$pk;

            $this->query = $update;

            if ($this->bd->ejecutarQuery($this->query)) {
                $this->establecerAtributos($dataUpdate);
            }

        }
        else {

            $this->query = "";
        }
        $this->resultBD->setValores($this);

        #$this->bd->cerrarConexion();
        return $this->resultBD;

    }

    /**
     * Elimina uno o multiples registros de base de datos
     *
     * Si no se pasa ningun elemento se eliminará el objeto instanciado.
     * @method eliminar
     *
     * @param array  [$arrayDatos ] Arreglo de valores a ser eliminados
     * @param string $campo Campo o propiedad por medio de la cual se eliminaran los objetos, si no es pasado sera usada
     *                      la clave primaria.
     *
     * @return boolean
     */
    function eliminar($arrayDatos = "", $campo = "", $cond = "and") {

        $totalParams = func_num_args();
        if (empty($campo))
            $campo = $this->pk;
        if ($totalParams == 0) {
            $pk = $this->pk;
            $datos[] = $this->$pk;
        }
        else {
            $datos = [];
            if (is_array($arrayDatos)) {
                foreach ($arrayDatos as $key => $value) {
                    if (is_numeric($value)) {
                        $datos [] = "$value";
                    }
                    else {
                        $datos [] = "\"$value\"";
                    }
                }
            }
            else {
                $datos[] = "\"" . $arrayDatos . "\"";
            }
        }
        if (is_array($campo)) {
            $i = 0;
            $where = "";
            foreach ($campo as $key => $filtro) {
                if ($i > 0)
                    $where . ' ' . $cond . ' ';
                $where .= "$key='" . $filtro . "'";
                ++$i;

            }
            $query = sprintf("DELETE FROM %s where %s", $this->tablaBD, $where);
        }
        else {
            $query = sprintf("DELETE FROM %s where $campo in (%s)", $this->tablaBD, implode(',', $datos));
        }

        if ($this->bd->ejecutarQuery($query)) {
            #$this->bd->cerrarConexion();
            return true;
        }
        else {
            #$this->bd->cerrarConexion();
            return false;
        }
    }

    /**
     * Permite instanciar el objeto por medio de una propiedad;
     * @method getBy
     *
     * @param mixed $valor Patrón de busqueda
     * @param string $propiedad de busqueda
     */
    function obtenerBy($valor, $property = "") {

        if (empty($property))
            $property = $this->pk;

        if (array_key_exists($property, $this->propiedades)) {

            $data = $this->consulta()->filtro([$property => $valor])->fila();

            if ($this->bd->totalRegistros > 0) {

                $this->valoresIniciales = $data;
                $this->establecerAtributos($data, $this->_clase);

                if ($this->nivelActualORM <= $this->nivelORM) {

                    $this->identificarObjetosRelacion();
                    $this->obtenerDataRelaciones();
                }

                return $this;
            }
            else {
                return false;
            }

        }
        else {
            throw new Exception("la propiedad pasada para obtener el objeto no existe", 124);

        }

    }

    /**
     * Retorna un arreglo con las propiedades publicas del objeto
     * @method objectAsArray Alias obtenerPropiedades
     *
     * @return array
     * @deprecated
     */
    function objectAsArray() {

        $this->obtenerPropiedadesObjeto();

        return $this->propiedades;
    }

    /**
     * Retorna un arreglo con las propiedades publicas del objeto
     * @method obtenerPropiedades
     *
     * @return array Arreglo con propiedades publicas del objeto
     */
    function obtenerPropiedades($relaciones = false) {

        $this->obtenerPropiedadesObjeto();
        $propiedadesRelaciones = [];
        if ($relaciones !== false) {
            foreach ($this->perteneceAUno as $key => $valores) {
                if (property_exists($this, $key)) {
                    $propiedadesRelaciones[$key] = $this->{$key}->obtenerPropiedades();
                }

            }
        }

        return array_merge($this->propiedades, $propiedadesRelaciones);
    }

    /**
     * Inserta multiples registros en Base de Datos
     * @method crearTodo
     *
     * @param array $data Data a insertar
     * @param boolean [$insertPK] bandera para indicarle al metodo si incluye la clave primaria en el query
     *
     * @return object ResultBD
     * @see ResultBD
     */
    function salvarTodo($data, $insertPK = false) {

        if (!is_array($data)) {
            $msj = "El arreglo pasado no se encuentra creado correctamente";
            Excepcion::procesar($msj, 111);
        }

        $campos = $this->obtenerCamposQuery(array_slice($data, 0, 1)[0], $insertPK);

        $insert = "INSERT INTO {$this->tablaBD} ";
        $insert .= '(' . implode(",", $campos) . ') VALUES ';

        for ($i = 0; $i < count($data); ++$i) {

            if ($i > 0) $insert .= ',';
            $datos = $this->estructuraInsert($data[$i], $insertPK);
            $insert .= ' (' . implode(',', $datos) . ')';

        }

        $this->totalInserciones = count($data);
        $this->bd->ejecutarQuery($insert);
        $this->armarIdsInsertados();

        return $this->resultBD->setValores($this);

    }

    /**
     * Retorna los ids generados a partir de una insercion multiple
     *
     * @method idsInsertados
     * @return array $idsInsertados
     */
    private function armarIdsInsertados() {

        $lastId = $this->bd->idResult;
        $this->idsResultantes = [];
        for ($i = 0; $i < $this->totalInserciones; ++$i) {

            $this->idsResultantes[] = $lastId;
            $lastId++;
        }
    }//fin crearInsert

    function obtIdsResultados() {

        return $this->idsResultantes;
    }

    /**
     * Limita la consulta a base de datos
     *
     * @param int $limit campo sobre el que empieza la consulta
     * @param int $offset limite de registros a traer
     */
    function limit($limit = 100, $offset = 0) {

        if (!$this->usoLimit) {
            $this->_limit = $this->bd->limit($limit, $offset);
            $this->usoLimit = true;
        }

        return $this;
    }

    /**
     * Retorna el objeto ResultBD obtenido a partir de una consulta a base de datos
     *
     * @method getResult
     * @return object ResultBD();
     */
    function getResult() {

        return $this->resultBD;
    }//fin función

    function envolverFiltro() {

        $consulta = explode('where', $this->query);
        if (count($consulta > 0))
            $this->query = $consulta[0] . ' where (' . $consulta[1] . ')';

        return $this;
    }

    /**
     * Registra el total de registros de una tabla
     * @method totalRegistros
     */
    function totalRegistros($filtro = false) {

        $this->query = "Select count(*) as total from " . $this->tablaBD . " ";
        if ($filtro)
            return $this;
        else
            return $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery("select count(*) as total from " . $this->tablaBD));
    }

    function imprimir($propiedad = "query", $exit = 1) {

        Debug::string($this->{$propiedad}, $exit);
    }

    /**
     * Utiliza la clausula between de mysql
     * @method entre
     *
     * @param string $campo Nombre del campo a usar
     * @param mixed $ini Valor inicial
     * @param mixed $fin Valor final
     */
    function entre($campo, $ini, $fin) {

        $this->query .= $campo .= ' between \'' . $ini . '\' and \'' . $fin . '\'';

    }

    /**
     * Utiliza la clausula COUNT de mysql
     * @method contar
     */
    function contar($campo = '*', $distinct = false) {

        if ($campo != '*')
            $campo = $this->tablaQuery . '.' . $campo;

        if ($distinct)
            $campo = 'DISTINCT ' . $campo;

        $this->query = "SELECT  COUNT($campo) as total_count from $this->tablaQuery ";
        $this->usoWhere = false;

        return $this;
    }

    /**
     * Retorna el query armado
     * @method obtQuery
     */
    protected function obtQuery() {

        return $this->query;
    }

    protected function guardarRelacion($arrayData) {

    }

    /**
     * Verifica las relaciones existentes
     *
     * Valida las relaciones uno a muchos y muchos a muchos del objeto. Si el objeto
     * está instanciado, obtiene la data de cada relacion basandose en el limite definido
     * en la constante
     * @method validarRelaciones
     */
    private function validarRelaciones() {

        if ($this->nivelActualORM < $this->nivelORM) {
            foreach ($this->tieneMuchos as $id => $key) {

                if (is_integer($id) and class_exists($key)) {
                    $keyObject = new $key(null, $this->nivelActualORM);
                    $pk = $this->pk;
                    $this->$key = $keyObject->obtenerBy($this->$pk, $pk);
                }

            }
        }
    }

    /**
     * Identifica el nombre de la tabla de base de datos
     * @method obtenerTablaBD
     */
    private function obtenerTablaBD() {

    }

    /**
     * Verifica que clases son identificadas como objetos
     *
     * @method identificarPropertyObjects
     */
    private function _identificarObjetosRelacion() {

        if ($this->nivelActualORM < $this->nivelORM) {

            foreach ($this->propiedades as $prop => $val) {
                if (substr($prop, 0, 2) == 'id' and $prop != $this->pk) {
                    $propiedad = str_replace("id_", "", $prop);

                    $objeto = Medios\Cadenas::upperCamelCase(str_replace("_", " ", $propiedad));

                    if ($propiedad != $this->_clase and class_exists($objeto)) {
                        //Se pasa la constante NIVEL_ORM +1 para que no sea instanciado
                        //ninguna relacion del objeto relacionado
                        $obj = new $objeto(null, 2);
                        $pk = $this->pk;
                        if (!empty($this->$pk)) {

                            $obj->obtenerBy($this->$prop, $prop);
                            $this->$objeto = $obj;
                        }
                        else {
                            $this->$objeto = new $objeto(null, 2);
                        }
                        $this->pertenece[$objeto] = $this->$objeto;

                    }
                    //$this->propiedadesObjetos[$prop]=new $objeto();
                }
            }//fin foreach
        }
    }

    /**
     * Devuelve el plural de una palabra
     * @method obtenerPlural
     */
    private function obtenerPlural($palabra) {

        $vocales = [
            'a',
            'e',
            'i',
            'o',
            'u'
        ];
        $ultima = substr($palabra, -1);
        if (in_array($ultima, $vocales)) {
            return $palabra . PLURAL_ATONO;
        }
        else {
            return $palabra . PLURAL_CONSONANTE;
        }

    }

    private function _obtenerSingular($palabra) {

        $arrayPalabra = [];
        $palabra = preg_split('#([A-Z][^A-Z]*)#', $palabra, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        foreach ($palabra as $key => $word) {
            if (substr($word, strlen($word) - 2) == PLURAL_CONSONANTE) {
                $arrayPalabra[] = substr($word, 0, strlen($word) - 2);
            }
            else if (substr($word, strlen($word) - 1) == PLURAL_ATONO) {
                $arrayPalabra[] = substr($word, 0, strlen($word) - 1);
            }
            else {
                $arrayPalabra[] = $word;
            }
        }

        return implode($arrayPalabra);
    }

    static function sp($sp, $parametros = []) {
        if (!self::$instancia instanceof self) {
            self::$instancia = new self;
        }

        if (!!$parametros) {
            if (is_string($parametros)) {
                $parametros = (array)$parametros;
            }
            $parametros = "'" . implode("', '", $parametros) . "'";
            self::$instancia->query = "CALL " . $sp . "({$parametros});";
        }
        else {
            self::$instancia->query = "CALL " . $sp . ";";
        }
        Debug::imprimir([self::$instancia->query]);
        $result = self::$instancia->bd->ejecutarQuery(self::$instancia->query);
        return is_object($result) ? self::$instancia->bd->obtenerDataCompleta($result) : $result;
    }

}//fin clase;
