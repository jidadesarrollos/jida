<?PHP
 /**
  * Controlador global del framework
  *
  * @package Framework
  * @author  Julio Rodriguez <jirc48@gmail.com>
  * @date 4/11/2016
  * @since 0.5
  *
  */


namespace Jida\Core\Manager;
use Jida\Helpers as Helpers;
use Jida\Helpers\Debug as Debug;
use Jida\Modelos\ACL as ACL;
use ReflectionClass;
//use Jida\Core\ExcepcionController as Excepcion;
use Exception as Excepcion;
global $JD;
 class JidaController {
 	private $_ce="001";
    /**
     * Define el nombre del Controlador requerido
     * @var string $controlador
     * @access private
     */
 	private $appRoot;

	/**
	 * Define la ruta donde deben buscarse los archivos
	 * @var $_ruta;
	 * @default 'app';
	 * @since 0.5
	 */
	private $_ruta = 'app';
	private $_metodoDefault = 'index';
	private $_controladorDefault = 'Index';
	/**
	 * Arreglo de componentes de la url
	 * @var $_arrayUrl
	 */
	private $_arrayUrl;
	/**
	 * Controlador solicitado
	 * @var $_metodo
	 * @access private
	 * @since 0.5
	 */
	private $_metodo;
	/**
	 * Controlador solicitado
	 * @var string $_controlador
	 * @access private
	 * @since 0.5
	 */
	private $_controlador;
	/**
	 * Nombre del controlador sin namespace
	 * @var string $_nombreControlador
	 */
	private $_nombreControlador;
	/**
	 * Controlador solicitado
	 * @var $_modulo
	 * @access private
	 * @since 0.5
	 */
	private $_modulo;
	/**
	 * Define si una peticion corresponde a un modulo administrador
	 * @var $_esJadmin;
	 * @since 0.5
	 */
	private $_esJadmin;

	/**
	 * Arreglo de lenguajes manejados en la aplicacion
	 * @var array $lenguajes
	 */
	private $idiomas=[];
	/**
	 * Registra la estructura de los valores get pasados por URL para versiones del framework anteriores a 1.4
	 *
	 * @var array $arrayGetCompatibilidad
	 * @ignored
	 */
	private $arrayGetCompatibilidad=[];
	private $idiomaActual;
    /**
     * Objeto controlador instanciado
     * @var object $controladorObject
     * @access private
     */
    private $controladorObject;
    /**
     * Metodo a ejecutar del controlador solicitado
     * @var string $metodo
     * @access private
     */
    private $metodo;
    /**
     * Argumentos pasados al metodo
     * @var string $args
     * @access private
     *
     */
    private $args=array();
    /**
     * Instancia de objeto vista
     * @var object Pagina
     */
    private $vista;

    private $tipoControlador;
    /**
     * Define el modulo a usar en caso de que exista
     * @var strng $modulo
     */
    private $modulo="";
    /**
     * Nombre del subdominio en uso si existe;
     */
    private $subdominio;
    /**
     * Define si se accede a un modulo a partir de un subdominio
     * @var $moduloSubdominio
     */
    private $moduloSubdominio=FALSE;
    /**
     * Arreglo de modulos existentes
     *
     * El arreglo se obtiene por medio de la funcion obtenerModulos, la cual debe
     * existir en el archivo de configuracion del framework
     * @var array $modulosExistentes
     */
     private $modulosExistentes=[];
    function __construct(){
        try{
            /**
             * Registro de tiempo inicial de ejecución
             */
            Helpers\Sesion::set('__TIEjecucion',microtime(true) );
            /**
             * Seteo de zona horaria
             */
            date_default_timezone_set(ZONA_HORARIA);
			/**
			 * validacion lenguajes existentes
			 */
            if(array_key_exists('idiomas', $GLOBALS)){
            	$this->idiomas=$GLOBALS['idiomas'];
            }
            Helpers\Sesion::destroy('__formValidacion');

            $_SERVER = array_merge($_SERVER,getallheaders());
            if(array_key_exists('modulos', $GLOBALS)){
                $this->modulosExistentes=$GLOBALS['modulos'];
            }else{
                throw new Exception("No se encuentra definida la variable global modulos, verifique el archivo de configuracion", 1);
            }

            $_SESSION['urlAnterior'] = isset($_SESSION['urlActual'] )?$_SESSION['urlActual'] :"";
			JD('URL_ANTERIOR',Helpers\Sesion::get('urlActual'));
			Helpers\Sesion::set('urlActual',$_GET['url']);

			JD('URL_COMPLETA',"/".$_GET['url']);

            /*Manejo de url*/
            if(isset($_GET['url'])){

                $_GET['url'] = utf8_encode($_GET['url']);
                $url = filter_input(INPUT_GET, 'url',FILTER_SANITIZE_URL);
                $url = explode('/', str_replace(array('.php','.html','.htm'), '', $url));
                $this->_arrayUrl = array_filter($url);

				if(array_key_exists($this->_arrayUrl[0], $this->idiomas)){
					$this->idiomaActual=$this->_arrayUrl[0];
					array_shift($this->_arrayUrl);
					if(count($this->_arrayUrl)<1){
						$this->_arrayUrl[0]='index';
					}
				}
            }

            unset($_GET['url']);
            if(count($_GET)>0)   $this->args=$_GET;

            $this->appRoot = str_replace(['index.php'], "", $_SERVER['PHP_SELF']);
			$GLOBALS['__URL_APP'] = $this->appRoot;

			$ini = substr($this->appRoot, 1);

			Helpers\Sesion::set('URL_ACTUAL', $ini.Helpers\Sesion::get('URL_ACTUAL'));
			JD('URL',Helpers\Sesion::get('URL_ACTUAL'));
            /**
             * variable global con todos los parametros pasados via url
             */
            $GLOBALS['arrayParametros'] = $url;

            // $this->getSubdominio($url);

            $this->procesarURL();
            if(count($this->args)>0){
                $this->procesarArgumentos();
            }

            $GLOBALS['_MODULO_ACTUAL'] = $this->_modulo;
			// Helpers\Debug::imprimir($this->_nombreControlador,$this->_metodo,$this->_modulo,$this->_ruta,true);
            $this->vista = new Pagina($this->_nombreControlador,$this->_metodo,$this->_modulo,$this->_ruta,$this->_esJadmin);
            $this->vista->idioma=$this->idiomaActual;
			$this->generarVariables();
            $this->validacion();
        }catch(Exception $e){
            $this->procesarExcepcion($e);
        }

    }//fin constructor
    /**
	 * Gestiona variables para acceso global en la aplicacion
	 *
	 * Esta funcion debe ser revisada
	 * @since 1.4
	 */

    private function generarVariables(){
    	JD('Controlador',$this->_nombreControlador);
		JD('Vista',$this->vista);
		JD('Metodo',$this->_metodo);
		JD('Modulo',$this->_modulo);
    }
    /**
     * Procesa el contenido de la url
     * @internal Valida los modulos, controladores y metodos a consultar
     * @method procesarURL
     */
    private function procesarURL(){

		$primerParam =array_shift($this->_arrayUrl);
		if($primerParam=='jadmin'){
			$this->_esJadmin=TRUE;
			$this->_procesarJadmin();
		}else{

		}
    }
	/**
	 * Procesa las urls dirigidas al administrador de la aplicacion
	 * @param procesarJadmin
	 * @since 0.5;
	 */
	private function _procesarJadmin(){
		$posModulo = (count($this->_arrayUrl)>0)?$this->validarNombre(array_shift($this->_arrayUrl),1):"Jadmin";
		$checkModulo = FALSE;

		if(in_array($posModulo,$this->modulosExistentes))
		{

		}else{
			//Accede aqui si se busca un modulo del Framework
			$namespace = 'Jida\\Jadmin\\';
			$this->_ruta='framework';
			if(Helpers\Directorios::validar(DIR_FRAMEWORK."Jadmin/Modulos/".$posModulo)){

				$this->_modulo = $posModulo;
				if(class_exists($namespace.'Modulos\\'.$posModulo.'\\'.$this->validarNombre($this->_arrayUrl[0],1)."Controller")){
					/**
					 * Accede acá si existe el modulo como carpeta
					 */
					$this->_controlador = $namespace.$posModulo.'\\'.$ctrl;
					$this->_modulo = $posModulo;
					$this->_nombreControlador = $ctrl;
				}
			}else{

				if(class_exists($namespace."Controllers\\".$posModulo."Controller")){
					$this->_controlador = $namespace."Controllers\\".$posModulo."Controller";

					$this->_nombreControlador = $posModulo;
					$this->procesarMetodo();
				}else{
					$this->_controlador = $namespace."Controllers\\JadminController";
					$this->_nombreControlador = 'jadmin';
					$this->procesarMetodo();
				}
			}

		}


	}
	/**
	 * Procesa el metodo a ejecutar
	 * @method procesarMetodo
	 * @deprecated
	 */
	private function procesarMetodo(){
		if(count($this->_arrayUrl)>0){

			$clase = new ReflectionClass($this->_controlador);
			$metodoOriginal = array_shift($this->_arrayUrl);
			$metodo = $this->validarNombre($metodoOriginal,2);

			if(method_exists($this->_controlador, $metodo) and $clase->getMethod($metodo)->isPublic()){
			//if(method_exists($this->_controlador, $metodo))
				return $this->_metodo = $metodo;
			}
			array_unshift($this->_arrayUrl,$metodoOriginal);
			$metodo = $this->_metodoDefault;
		}
		$this->_metodo = $this->_metodoDefault;
		if(!method_exists($this->_controlador, $this->_metodo))
			throw new Excepcion("No existe el metodo solicitado", 404);

		return $this->_metodo;



	}
    private function _procesarURL($url){

        $primerParam = array_shift($url);

		$URL = "/".$primerParam;
        $param = $this->validarNombre($primerParam,1);


        if(!in_array($param,$this->modulosExistentes) or $this->moduloSubdominio===TRUE){

            if(!Directorios::validar(DIR_APP)):
                /**
                 * Entra aca si es una app nueva
                 */
                $this->modulo="Jadmin";
                $this->controlador = 'init';
				$this->metodo="index";
				$init = substr($this->appRoot, 1);
				Helpers\Sesion::set('URL_ACTUAL', $init.'jadmin/init');

            else:

                //Se verifica si existe el controlador
                if($this->checkController($param."Controller")){
                    $this->controlador=$param;

                    if(count($url)>0 ){

                    	$paramDos =array_shift($url);
						$URL.="/".$paramDos;
                        $param =$this->validarNombre($paramDos,1);
                        $this->checkMetodo($param,TRUE);
                    }else{
                        $this->metodo='index';
                    }
                }else{
                    /**
                     * Si entra aqui el controlador a ejecutar es el Index publico
                     * */
                    if($this->moduloSubdominio===TRUE){
                        $this->controlador =$this->validarNombre($this->modulo, 1);
                    }else
                        $this->controlador='Index';


                    $this->checkMetodo($param,TRUE);
                }
            endif;

        }else{

            $this->modulo=$param;

            if(count($url)>0){

            	$paramDos = array_shift($url);

				$URL.="/".$paramDos;
                $param =$this->validarNombre($paramDos,1);

                //Se valida si existe un controlador en la url
                if($this->checkController($param."Controller")){
                    $this->controlador=$param;


                    if(count($url)>0){
                    	$paramTres = array_shift($url);
						$URL.="/".$paramTres;
                        $param =$this->validarNombre($paramTres,1);
						$this->checkMetodo($param,true);
                    }else{

						$this->metodo='index';
                    }


                }else{
                    $this->controlador=$this->modulo;
                    $this->checkMetodo($param,TRUE);
                }
            }else{
                $this->controlador=$this->modulo;
                $this->metodo='index';
            }
        }


		// Debug::mostrarArray($this->args);
		JD('QueryString',$this->args);

        Helpers\Sesion::set('URL_ACTUAL', $URL);

        $this->args = array_merge($this->args, $url);

    }
    /**
     * Verifica la existencia de un metodo solicitado
     * @method checkMetodo
     * @param string $metodo Nombre del metodo a consultar
     */
    private function checkMetodo($metodo='index',$insertArg=FALSE){

        if(method_exists($this->controlador."Controller", $this->validarNombre($metodo,2))){
            $clase = new ReflectionClass($this->controlador."Controller");
            if($clase->getMethod($this->validarNombre($metodo,2))->isPublic()){
                $this->metodo=$metodo;
                return true;
            }else{

                 $this->metodo="index";
                if($insertArg and !empty($metodo)){
                    $this->args[]=strtolower($metodo);
                }
            }

        }else{

            $this->metodo="index";
            if($insertArg and !empty($metodo)){
                $this->args[]=strtolower($metodo);
            }
            return false;
        }
    }
    /**
     * Verifica si existe un controlador
     * @method checkController
     * @param string $controller Nombre del controlador a consultar
     * @return boolean True si existe false caso contrario
     */
    private function checkController($controller){
    	$controlador="";
		if(!empty($this->modulo)){

			$controlador=$this->modulo."\\";
		}
        if(class_exists($controller)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Verifica si se encuentra un modulo definido para un subdominio de la aplicación
     * @method checkModulo
     * @access private
     *
     */
    private function getSubdominio(){

        $divisionUrlArray = explode('.', $_SERVER['SERVER_NAME']);
        if(count($divisionUrlArray)>0){
            $this->subdominio = $divisionUrlArray[0];
        }else{
            $this->subdominio="";
        }


    }


    private function validarExistenciaController(){

    }

    /**
     * CREA arreglo de parametros get
     * @method procesarAgumentos
     * @access private
     *
     */
    private function procesarArgumentos($tipo=1){
            $band = 0;
            $clave = TRUE;

			$this->args = array_filter($this->args,function($value){
				return !empty($value);
			});

			$totalClaves = count($this->args);
            $gets=array();
            if($totalClaves>=2){
                for($i = 0; $i<=$totalClaves;$i++){

                    if($clave===TRUE){
                        if(isset($this->args[$i]) and isset($this->args[$i+1]))
                            $gets[$this->args[$i]]=$this->args[$i+1];
                    }
                    $i++;
                }
            }if($tipo>1){

                $GLOBALS['getsIndex']= "otro";
            }
			$this->arrayGetCompatibilidad = array_merge($this->args,$gets);

            $totalClaves = count($this->args);

            $gets=array();

            // $_GET = array_merge($this->args,$gets);
            $_GET = $this->args;
			$_REQUEST = array_merge($_POST,$_GET);
    }
    function get(){
        Debug::mostrarArray($this);
    }
    /**
     * Ejecuta la solicitud realizada
     *
     * Valida existencia del controlador, comprobando si pertenece a un controlador
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
    function validacion(){
        try{
            if(BD_REQUERIDA===TRUE){
				$acl = new ACL();
            	$acceso = $acl->validarAcceso($this->_controlador,$this->validarNombre($this->metodo, 2),strtolower($this->modulo));

			}else $acceso=TRUE;

           if($acceso===TRUE){
            	global $dataVista;
			   	//Helpers\Debug::imprimir($this->_modulo,$this->_nombreControlador,$this->_metodo,true);
                $dataVista= new DataVista($this->_modulo,$this->_nombreControlador,$this->_metodo,$this->_esJadmin);
            	$this->vista->data = $dataVista;
				$this->ejecucion($this->_controlador);


           }else{

                 throw new Exception("No tiene permisos", 403);

           }
           if(isset($controlador)){

               $this->ejecucion($controlador);
           }
         }catch(Exception $e){

            $this->procesarExcepcion($e);
        }


    }//final funcion validacion

    /**
     * Obtiene el controlador requerido de un modulo especifico
     */
    private function obtenerControladorModulo($nombreArchivo){

        $rutaModulo="";
        if(!empty($this->modulo)){
            $rutaModulo =app_dir . "Modulos/" .$this->modulo."/Controller/".$nombreArchivo;
        }else{
            $rutaModulo = app_dir.'Controller/'.$nombreArchivo;
        }
        return $rutaModulo;

    }
    /**
     * Ejecuta el metodo solicitado
     *
     * Realiza una instancia del controlador requerido y hace llamado al
     * metodo solicitado haciendo uso de la clase ReflectionMethod para obtener
     * los parametros devueltos y pasarlos al metodo mostrarContenido
     *
     * @method ejecucion
     * @param object $controlador <br>Objeto Controlador a instanciar
     * @access private
     */
    private function ejecucion($controlador){

        $controlador = $this->ejecutarController($controlador);

        $this->mostrarContenido($controlador->vista);


    }//fin funcion ejecucion
    /**
     * Verifica las propiedades de los directorios Layout
     * @method checkDirectoriosView
     */
    private function checkDirectoriosView(){
        if(is_object($this->controladorObject)):

            $this->vista->layout = $this->controladorObject->layout;
            $this->vista->definirDirectorios();

        endif;

    }
    /**
     * Realiza la ejecución del Controlador a instanciar
     * @method ejecutarController
     */
    private function ejecutarController($controlador,$params=[],$checkDirs=true){

        $args = $this->args;
        $metodo = Helpers\Cadenas::lowerCamelCase($this->_metodo);
        $retorno= array();
        #se instancia el controlador solicitado
        $nombreControlador = $controlador;
		$this->vista->data->idioma=$this->idiomaActual;
		$GLOBALS['dv']=$this->vista->data;


		if(!class_exists($controlador)){

			new Excepcion("La clase pedida no existe ".$this->modulo."\\".$controlador,$this->_ce.'1');
		}
        $this->controladorObject = new $controlador();
        $this->controladorObject->modulo=$this->modulo;
        $controlador=& $this->controladorObject;

        if(method_exists($controlador, $metodo)){
			//Validacion de ejecucion de un metodo previo al solicitado por url
			if(!empty($controlador->preEjecucion) and method_exists($controlador, $controlador->preEjecucion)){

				call_user_func_array([$controlador,$controlador->preEjecucion], $args);
			}

            if($metodo==$controlador->preEjecucion or $metodo==$controlador->postEjecucion){
				throw new \Exception("aaa", 404);
            }
			// Ejecucion del metodo solicitado
			if($controlador->manejoParams){

				call_user_func_array([$controlador,$metodo], $args);
            }else{

            	$_GET 		= $this->arrayGetCompatibilidad;
				$_REQUEST 	= array_merge($_POST,$_GET);
				$controlador->validarVarGlobales(true);
            	$controlador->$metodo(null);
			}
			//Validacion ejecucion post metodo
			if(!empty($controlador->postEjecucion) and method_exists($controlador, $controlador->postEjecucion)){
				call_user_func_array([$controlador,$controlador->postEjecucion], $args);
			}
        }else{
            throw new Excepcion("No existe el metodo $metodo del controlador $nombreControlador", 404);

        }
        if($checkDirs){
            $this->checkDirectoriosView();
        }
        #llamada al metodo solicitado via url

        return $controlador;
    }
    /**
     * Procesa una excepción capturarda
     *
     * @method procesarExcepcion
     */
    private function procesarExcepcion(Exception $excepcion){
        try{
        	//if(ENTORNO_APP=='dev' and $excepcion->getCode()!=404)
        	global $dataVista;

            if(strpos($this->controlador, 'Controller')===false)
                $ctrlError = $this->controlador."Controller";
            else
                $ctrlError = $this->controlador;

            if($ctrlError!=CONTROLADOR_EXCEPCIONES)
                $this->controladorObject = new $ctrlError;

            if(!defined('CONTROLADOR_EXCEPCIONES')){
                $this->controlador='ExcepcionController';
            }else {
                $this->controlador=CONTROLADOR_EXCEPCIONES;
            }

            $this->vista->data = $dataVista;

            $this->vista->procesarExcepcion(new JExcepcion($excepcion,$ctrlError),$this->controlador);

        }catch(Exception $e){
        	Debug::mostrarArray($e,0);


            $metodo = $this->metodo;


            $this->vista->data->setVistaAsTemplate('error');
            $this->vista->establecerAtributos(['modulo'=>'jadmin']);
			$this->vista->pathLayout('Framework/Layout');
            $this->controladorObject =$ctrlExcepcion;

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
     * @param array $retorno
     * @param string $vista [opcional] Nombre de la vista requerida, si no se pasa el valor se busca un archivo con el nombre
     * del metodo
     * @access private
     *
     */
    private function mostrarContenido($vista=""){
        global $dataVista;
        $this->vista->data = $dataVista;
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
	 * @return string $nombre Cadena Formateada resultante
     */
    private function validarNombre($str,$tipoCamelCase){
        if(!empty($str)){
            if($tipoCamelCase==1){
                $nombre = str_replace(" ","",Helpers\Cadenas::upperCamelCase(str_replace("-", " ",$str)));
            }else{
                $nombre = str_replace(" ","",Helpers\Cadenas::lowerCamelCase(str_replace("-", " ",$str)));
            }
            return $nombre;
        }

    }
 } // END

