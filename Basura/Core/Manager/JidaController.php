<?PHP
/**
 * Arranque global del framework
 *
 * @package Framework
 * @author  Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @date    4/11/2016
 * @since   0.5
 *
 */

//_-----------------------------------------
namespace Jida\Core\Manager;

use Jida\Helpers as Helpers;
use Jida\Helpers\Debug as Debug;
use Jida\Core\Manager\ACL as ACL;
use ReflectionClass;
//use Jida\Core\ExcepcionController as Excepcion;
use Exception as Excepcion;
use Jida\Core\Manager\JExcepcion as JExcepcion;
use App as App;
use Jida as Jida;

//_-----------------------------------------
global $JD;

class JidaController {
    private $_ce = "001";
    /**
     * Define el nombre del Arranque requerido
     *
     * @var string $controlador
     * @access private
     */
    private $appRoot;

    /**
     * Define la ruta donde deben buscarse los archivos
     *
     * @var $_ruta ;
     * @default 'app';
     * @since   0.5
     */
    private $_ruta = 'app';
    private $_metodoDefault = 'index';
    private $_controladorDefault = 'Index';
    /**
     * Define el namespace usado
     *
     * @var string $_namespace ;
     */
    private $_namespace;
    /**
     * Arreglo de componentes de la url
     *
     * @var $_arrayUrl
     */
    private $_arrayUrl;
    /**
     * Arranque solicitado
     *
     * @var $_metodo
     * @access private
     * @since  0.5
     */
    private $_metodo;
    /**
     * Arranque solicitado
     *
     * @var string $_controlador
     * @access private
     * @since  0.5
     */
    private $_controlador;
    /**
     * Nombre del controlador sin namespace
     *
     * @var string $_nombreControlador
     */
    private $_nombreControlador;
    /**
     * Arranque solicitado
     *
     * @var $_modulo
     * @access private
     * @since  0.5
     */
    private $_modulo;
    /**
     * Define si una peticion corresponde a un modulo administrador
     *
     * @var boolean $_esJadmin ;
     * @since 0.5
     */
    private $_esJadmin = false;
    /**
     * Define si un modulo jadmin pertenece a la aplicacion o al Framework
     *
     * @var boolean $_esApp
     * @since 0.5.1
     */
    private $_esApp = false;

    /**
     * Arreglo de lenguajes manejados en la aplicacion
     *
     * @var array $lenguajes
     */
    private $idiomas = [];
    /**
     * Registra la estructura de los valores get pasados por URL para versiones del framework anteriores a 1.4
     *
     * @var array $arrayGetCompatibilidad
     * @ignored
     */
    private $arrayGetCompatibilidad = [];
    private $idiomaActual;
    /**
     * Objeto controlador instanciado
     *
     * @var object $controladorObject
     * @access private
     */
    private $controladorObject;
    /**
     * Metodo a ejecutar del controlador solicitado
     *
     * @var string $metodo
     * @access private
     */
    private $metodo;
    /**
     * Argumentos pasados al metodo
     *
     * @var string $args
     * @access private
     *
     */
    private $args = [];
    /**
     * Instancia de objeto vista
     *
     * @var object Pagina
     */
    private $vista;

    private $tipoControlador;
    /**
     * Define el modulo a usar en caso de que exista
     *
     * @var strng $modulo
     */
    private $modulo = "";
    /**
     * Nombre del subdominio en uso si existe;
     */
    private $subdominio;
    /**
     * Define si se accede a un modulo a partir de un subdominio
     *
     * @var $moduloSubdominio
     */
    private $moduloSubdominio = false;

    /**
     * Objeto Perseador de Urls
     *
     * @var object parser
     * @since 0.5
     */
    private $_parser;
    /**
     * Arreglo de modulos existentes
     *
     * El arreglo se obtiene por medio de la funcion obtenerModulos, la cual debe
     * existir en el archivo de configuracion del framework
     *
     * @var array $modulosExistentes
     */
    private $modulosExistentes = [];

    /**
     * @var $_conf
     */
    private $_conf;

    /**
     * JidaController constructor.
     */

    function __construct () {

        try {
            /**
             * Registro de tiempo inicial de ejecución
             */
            Helpers\Sesion::set('__TIEjecucion', microtime(true));
            /**
             * Seteo de zona horaria
             */

            $configuracion = (is_array($GLOBALS['JIDA_CONF'])) ? Helpers\Arrays::convertirAObjeto($GLOBALS['JIDA_CONF']) : $GLOBALS['JIDA_CONF'];

            if (is_object($configuracion)) {

                $this->_conf = $configuracion;

                $this->idiomas = $this->_conf->idiomas;
                $this->modulosExistentes = $this->_conf->modulos;
            }

            //$this->_parser = new Parser($this->modulosExistentes);

            $_SESSION['urlAnterior'] = isset($_SESSION['URL_ACTUAL']) ? $_SESSION['URL_ACTUAL'] : "";
            JD('URL_ANTERIOR', Helpers\Sesion::obt('URL_ACTUAL'));
            Helpers\Sesion::set('URL_ACTUAL', $_GET['url']);

            JD('URL_COMPLETA', "/" . $_GET['url']);
            JD('URL', "/" . $_GET['url'] . "/");
            /*Manejo de url*/
            if (isset($_GET['url'])) {

                $_GET['url'] = utf8_encode($_GET['url']);
                $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
                $url = explode('/',
                               str_replace([
                                               '.php',
                                               '.html',
                                               '.htm'
                                           ],
                                           '',
                                           $url));

                $this->_arrayUrl = array_filter($url,
                    function ($var) {

                        return ($var !== null && $var !== false && $var !== '');
                    });

                if (array_key_exists($this->_arrayUrl[0], $this->idiomas)) {
                    $this->idiomaActual = $this->_arrayUrl[0];

                    array_shift($this->_arrayUrl);
                }
            }

            unset($_GET['url']);
            if (count($_GET) > 0)
                $this->args = $_GET;
            $this->appRoot = str_replace(['index.php'], "", $_SERVER['PHP_SELF']);
            $GLOBALS['__URL_APP'] = $this->appRoot;
            //$ini = substr($this->appRoot, 1);
            $ini = $this->appRoot;
            Helpers\Sesion::set('URL_ACTUAL', $ini . Helpers\Sesion::obt('URL_ACTUAL'));
            //Helpers\Debug::imprimir($ini,true);
            JD('URL', Helpers\Sesion::obt('URL_ACTUAL'));
            /**
             * variable global con todos los parametros pasados via url
             */
            $GLOBALS['arrayParametros'] = $url;
            //se procesa la URL

            $this->procesarURL();

            $this->vista = new Pagina($this->_nombreControlador,
                                      $this->_metodo,
                                      $this->_modulo,
                                      $this->_ruta,
                                      $this->_esJadmin);
            $this->vista->idioma = $this->idiomaActual;
            $this->generarVariables();
            $this->validacion();
        }
        catch (Excepcion $e) {

            $this->jidaExcepcion($e);
        }

    }//fin constructor

    /**
     * Gestiona variables para acceso global en la aplicacion
     *
     * Esta funcion debe ser revisada
     *
     * @since 1.4
     */

    private function generarVariables () {

        JD('Arranque', $this->_nombreControlador);
        JD('Vista', $this->vista);
        JD('Metodo', $this->_metodo);
        JD('Modulo', $this->_modulo);
    }

    /**
     * Procesa el contenido de la url
     *
     * @internal Valida los modulos, controladores y metodos a consultar
     * @method procesarURL
     */
    private function procesarURL () {

        $primerParam = array_shift($this->_arrayUrl);

        if ($primerParam == 'jadmin') {
            $this->_esJadmin = true;

            $this->_procesarJadmin();
        }
        else {

            array_unshift($this->_arrayUrl, $primerParam);
            $namespace = 'App\\';
            if ($this->esModulo()) {

                $namespace .= "Modulos\\" . $this->_modulo . "\\Controllers\\";
                //Se verifica controlador
                $posController = array_shift($this->_arrayUrl);
                //El controlador por defecto tiene el nombre del modulo
                $this->_controladorDefault = $this->_modulo;
            }
            else {

                $namespace .= "Controllers\\";
                $posController = array_shift($this->_arrayUrl);
            }

            if (!$this->esControlador($namespace, $posController)) {
                array_unshift($this->_arrayUrl, $posController);
            }

            $this->procesarMetodo();
        }

        $this->procesarArgumentos();

    }

    /**
     * Procesa el metodo a ejecutar
     * @method procesarMetodo
     */
    private function procesarMetodo () {

        $band = false;
        if ($this->_arrayUrl) {

            $posMetodo = array_shift($this->_arrayUrl);

            if (!$this->esMetodoValido($posMetodo)) {
                $metodo = $this->_metodoDefault;
            }
            else $band = true;

        }
        else $metodo = $this->_metodoDefault;

        // buscara el metodo por defecto y arrojara un error sino lo consigue.
        if (!$band)
            $this->esMetodoValido($metodo, true);

    }

    /**
     * Verifica que el metodo exista
     * @method validarMetodo
     *
     * @param string $metodo Nombre del metodo a validar
     *
     * @return boolean
     */
    private function esMetodoValido ($metodo, $error = false) {

        if (class_exists($this->_controlador)) {
            $clase = new ReflectionClass($this->_controlador);
            $nombreOriginal = $metodo;
            $metodo = $this->validarNombre($metodo, 2);

            if (method_exists($this->_controlador, $metodo) and $clase->getMethod($metodo)->isPublic()) {
                $this->_metodo = $metodo;

                return true;
            }
            else {
                array_unshift($this->_arrayUrl, $nombreOriginal);
            }

            if ($error)
                throw new Excepcion("El metodo no es valido " . $metodo, 404);

            return false;
        }
        else {
            throw new Excepcion("No existe el controlador soicitado " . $this->_controlador . " para el metodo : " . $metodo,
                                $this->_ce . '4');
        }
    }

    /**
     * Verifica si el parametro pasado es un controlador
     * @method esControlador
     *
     * @param string $namespace Namespace sobre el cual se validara la existencia del controlador
     * @param string $controller Nombre del posible controlador pedido
     */
    private function esControlador ($namespace, $posController) {

        $band = true;

        if (empty($posController)) {
            $band = false;
        }
        else {
            $nombreValido = $this->validarNombre($posController, 1);
            $nombre = $namespace . $nombreValido;
            $nombreSufijo = $nombre . 'Controller';
        }

        if ($band and (class_exists($nombre) or class_exists($nombreSufijo))) {

            if (class_exists($nombreSufijo) or class_exists($nombre)) {
                $controlador = (class_exists($nombre)) ? $nombre : $nombreSufijo;
            }
            else {
                $controlador = $nombreSufijo;
            }

            $this->_nombreControlador = $nombreValido;
            $this->_namespace = $namespace;
            $this->_controlador = $controlador;

            return true;

        }
        else {
            $nombreValido = $this->validarNombre($this->_controladorDefault, 1);
            $nombre = $namespace . $nombreValido;
            $nombre = (class_exists($nombre)) ? $nombre : $nombre . 'Controller';
            $this->_controlador = $nombre;
            $this->_nombreControlador = $this->_controladorDefault;
        }

        return false;
    }

    /**
     * Verifica si el parametro apsado es un modulo cargado
     * @method esModulo
     */
    private function esModulo ($jadmin = false) {

        $this->_arrayUrl = array_filter($this->_arrayUrl);

        if (!empty($this->_arrayUrl)) {

            $nombreOriginal = array_shift($this->_arrayUrl);
            $posModulo = $this->validarNombre($nombreOriginal, 1);

            if ($jadmin) {

                $namespace = 'Jida\\Jadmin\\Modulos';

                if (Helpers\Directorios::validar(DIR_FRAMEWORK . 'Jadmin/Modulos/' . $posModulo)) {

                    $this->_controladorDefault = $posModulo;
                    $this->_namespace = $namespace . '\\' . $posModulo . '\\Controllers\\';
                    $this->_modulo = $posModulo;

                    return true;
                }

            }
            else if (in_array($posModulo, $this->modulosExistentes)) {
                $this->_modulo = $posModulo;

                return true;
            }

            array_unshift($this->_arrayUrl, $posModulo);
        }

        return false;
    }

    /**
     * Procesa las urls dirigidas al administrador de la aplicacion
     *
     * @param procesarJadmin
     *
     * @since 0.5;
     */
    private function _procesarJadmin () {

        $checkModulo = false;
        $path = '\\App\\Jadmin\\Controllers\\';
        $posController = array_shift($this->_arrayUrl);

        if (!$this->esControlador($path, $posController)) {

            if ($posController) {
                array_unshift($this->_arrayUrl, $posController);
            }

            if ($this->esModulo()) {

                //Accede aqui se se busca un segmento jadmin dentro de un modulo de la app.

                $this->_ruta = "app";
                $this->_namespace = 'App\\Modulos\\' . $this->_modulo . '\\Jadmin\\Controllers\\';
                $this->_controladorDefault = $this->_modulo;
                $this->_esApp = true;

            }
            else {

                array_unshift($this->_arrayUrl, $posController);
                $posController = array_shift($this->_arrayUrl);
                $this->_namespace = 'Jida\\Jadmin\\';
                //validacion modulo interno jadmin
                $this->_ruta = 'framework';

                if ($this->esModulo(true)) {
                    //Accede aqui si se busca un modulo del Framework
                    $namespace = $this->_namespace;
                }
                else {
                    //controlador por defecto jadmin;
                    $this->_controladorDefault = 'Jadmin';
                    $this->_namespace = 'Jida\\Jadmin\\Controllers\\';
                }

            }

            $posController = array_shift($this->_arrayUrl);

            if (!$this->esControlador($this->_namespace, $posController)) {
                array_unshift($this->_arrayUrl, $posController);
            }

        }

        $this->procesarMetodo();
    }

    /**
     * CREA arreglo de parametros get
     * @method procesarAgumentos
     *
     * @access private
     *
     */
    private function procesarArgumentos ($tipo = 1) {

        if (!empty($this->_arrayUrl)) {
            $this->args = array_merge($this->_arrayUrl, $this->args);
        }

        $band = 0;
        $clave = true;

        // $this->args = $this->_arrayUrl;
        $this->args = array_filter($this->args,
            function ($value) {

                return !empty($value) or $value == 0;
            });

        $totalClaves = count($this->args);
        $gets = [];

        if ($totalClaves >= 2) {

            for ($i = 0; $i <= $totalClaves; $i++) {

                if ($clave === true) {
                    if (isset($this->args[$i]) and isset($this->args[$i + 1])) {
                        $gets[$this->args[$i]] = $this->args[$i + 1];
                    }
                }
                $i++;
            }

        }
        if ($tipo > 1) {
            $GLOBALS['getsIndex'] = "otro";
        }
        $this->arrayGetCompatibilidad = array_merge($this->args, $gets);

        $_GET = $this->args;
        $_REQUEST = array_merge($_POST, $_GET);

    }

    /**
     * Ejecuta la solicitud realizada
     *
     * @internal Valida existencia del controlador, comprobando si pertenece a un controlador
     * de la aplicacion o controlador del framework, inicializa la propiedad "rutaPagina" de la
     * clase Pagina (propiedad vista del controlador) en 1 si es un controlador de la app. y en 2 si
     * es un controlador del administrador del framework
     *  requerido y hace uso
     * del metodo ejecución
     *
     * @method validacion
     * @return void
     *
     */
    function validacion () {

        try {

            if (BD_REQUERIDA === true) {
                $acl = new ACL();
                $acceso = $acl->validarAcceso($this->_controlador,
                                              $this->validarNombre($this->metodo, 2),
                                              strtolower($this->modulo));
            }

            $acceso = true;

            if ($acceso === true) {
                global $dataVista;

                $dataVista = new DataVista($this->_modulo,
                                           $this->_nombreControlador,
                                           $this->_metodo,
                                           $this->_esJadmin,
                                           $this->_esApp);
                $this->vista->data = $dataVista;
                $this->ejecucion($this->_controlador);

            }
            else {
                throw new Excepcion("No tiene permisos", 403);
            }

            if (isset($controlador))
                $this->ejecucion($controlador);

        }
        catch (Excepcion $e) {

            $this->procesarExcepcion($e);
        }
    }//final funcion validacion

    /**
     * Ejecuta el metodo solicitado
     *
     * Realiza una instancia del controlador requerido y hace llamado al
     * metodo solicitado haciendo uso de la clase ReflectionMethod para obtener
     * los parametros devueltos y pasarlos al metodo mostrarContenido
     *
     * @method ejecucion
     * @param object $controlador <br>Objeto Arranque a instanciar
     *
     * @access private
     */
    private function ejecucion ($controlador) {

        $controlador = $this->ejecutarController($controlador);
        $this->mostrarContenido($controlador->vista);

    }//fin funcion ejecucion

    /**
     * Verifica las propiedades de los directorios Layout
     * @method checkDirectoriosView
     */
    private function checkDirectoriosView () {

        if (is_object($this->controladorObject)):
            $this->vista->layout = $this->controladorObject->layout;
            $this->vista->definirDirectorios();
        endif;

    }

    /**
     * Realiza la ejecución del Arranque a instanciar
     * @method ejecutarController
     */
    private function ejecutarController ($controlador, $params = [], $checkDirs = true) {

        $args = $this->args;
        #Helpers\Debug::imprimir("jida",$params);
        $metodo = Helpers\Cadenas::lowerCamelCase($this->_metodo);
        $retorno = [];

        #se instancia el controlador solicitado
        $nombreControlador = $controlador;
        $this->vista->data->idioma = $this->idiomaActual;

        $GLOBALS['dv'] = $this->vista->data;

        $this->controladorObject = new $controlador();
        $this->controladorObject->modulo = $this->modulo;
        $controlador =& $this->controladorObject;
        $controlador->idioma = $this->idiomaActual;

        if (method_exists($controlador, $metodo)) {
            //Validacion de ejecucion de un metodo previo al solicitado por url
            if (!empty($controlador->preEjecucion) and method_exists($controlador, $controlador->preEjecucion)) {

                call_user_func_array([
                                         $controlador,
                                         $controlador->preEjecucion
                                     ],
                                     $args);
            }

            if ($metodo == $controlador->preEjecucion or $metodo == $controlador->postEjecucion) {
                throw new Excepcion("aaa", 404);
            }
            // Ejecucion del metodo solicitado
            if ($controlador->manejoParams) {
                call_user_func_array([
                                         $controlador,
                                         $metodo
                                     ],
                                     $args);
            }
            else {

                $_GET = $this->arrayGetCompatibilidad;
                $_REQUEST = array_merge($_POST, $_GET);
                $controlador->validarVarGlobales(true);
                $controlador->$metodo(null);
            }
            //Validacion ejecucion post metodo
            if (!empty($controlador->postEjecucion) and method_exists($controlador, $controlador->postEjecucion)) {
                call_user_func_array([
                                         $controlador,
                                         $controlador->postEjecucion
                                     ],
                                     $args);
            }
        }
        else {
            throw new Excepcion("No existe el metodo $metodo del controlador $nombreControlador", 404);

        }
        if ($checkDirs) {
            $this->checkDirectoriosView();
        }

        return $controlador;
    }

    private function jidaExcepcion (Excepcion $excepcion) {

        Helpers\Debug::imprimir($excepcion, true);
    }

    /**
     * Procesa una excepción capturarda
     *
     * @method procesarExcepcion
     */
    private function procesarExcepcion (Excepcion $excepcion) {

        try {
            //if(ENTORNO_APP=='dev' and $excepcion->getCode()!=404)
            global $dataVista;
            // Helpers\Debug::imprimir($excepcion);
            if (strpos($this->_controlador, 'Controller') === false)
                $ctrlError = $this->_controlador . "Controller";
            else
                $ctrlError = $this->_controlador;

            if ($ctrlError != CONTROLADOR_EXCEPCIONES)
                if (class_exists($ctrlError)) {
                    $this->controladorObject = new $ctrlError;
                }
                else {
                    $this->controladorObject = null;
                }

            if (!defined('CONTROLADOR_EXCEPCIONES')) {
                $this->controlador = '\Jida\Core\Excepcion';
            }
            else {
                $this->controlador = CONTROLADOR_EXCEPCIONES;
            }

            $this->vista->data = $dataVista;
            $this->vista->procesarExcepcion(new JExcepcion($excepcion, $ctrlError), $this->controlador);

        }
        catch (Excepcion $e) {

            $metodo = $this->metodo;
            $dataVista->usarPlantilla('error');
            $this->vista->establecerAtributos(['modulo' => 'jadmin']);
            $this->vista->pathLayout('Framework/Layout');
            $this->controladorObject = 'Jida\Core\Excepcion';
            Helpers\Debug::imprimir($e);
            //$this->mostrarContenido($ctrlExcepcion->vista);
        }
    }

    /**
     * Muestra contenido de la vista y controlador requeridos
     *
     * Ejecuta el metodo renderizar del objeto Vista para obtener
     * la vista correspondiente, pasa el array retorno como arreglo
     * con parametros para uso en la vista
     * @method mostrarContenido
     *
     * @param array $retorno
     * @param string $vista [opcional] Nombre de la vista requerida, si no se pasa el valor se busca un archivo con el
     *                      nombre del metodo
     *
     * @access private
     *
     */
    private function mostrarContenido ($vista = "") {

        global $dataVista;
        $this->vista->data = $dataVista;
        $this->vista->_namespace = $this->_namespace;
        $this->vista->_controller = $this->_nombreControlador;

        $this->vista->_modulo = ($this->_modulo);

        $this->vista->renderizar($vista);

    }

    /**
     * Ajusta el nombre de los Controladores y Metodos
     *
     * Realiza una modificación del string para crear nombres
     * de clases controladoras y metodos validas
     *
     * @method validarNombre
     * @param string $str Cadena a formatear
     * @param int $tipoCamelCase 1 Upper 2 Lower
     *
     * @return string $nombre Cadena Formateada resultante
     */
    private function validarNombre ($str, $tipoCamelCase) {

        if (!empty($str)) {
            if ($tipoCamelCase == 1) {
                $nombre = str_replace(" ", "", Helpers\Cadenas::upperCamelCase(str_replace("-", " ", $str)));
            }
            else {
                $nombre = str_replace(" ", "", Helpers\Cadenas::lowerCamelCase(str_replace("-", " ", $str)));
            }

            return $nombre;
        }

    }
} // END