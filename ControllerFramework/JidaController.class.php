<?PHP
 /**
  * Controlador global del framework
  *
  * @package Framework
  * @author  Julio Rodriguez <jirc48@gmail.com>
  * @date 27/12/2013
  */
 class JidaController {
    /**
     * Define el nombre del Controlador requerido
     * @var string $controlador
     * @access private
     */
 	private $appRoot;
    private $controlador;
	/**
	 * Arreglo de lenguajes manejados en la aplicacion
	 * @var array $lenguajes
	 */
	private $idiomas=[];
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
     private $modulosExistentes=array();
    function __construct(){
        try{
            /**
             * Registro de tiempo inicial de ejecución
             */
            Session::set('__TIEjecucion',microtime(true) );
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
            Session::destroy('__formValidacion');
            $_SERVER = array_merge($_SERVER,getallheaders());

            if(array_key_exists('modulos', $GLOBALS)){
                $this->modulosExistentes=$GLOBALS['modulos'];
            }else{
                throw new Exception("No se encuentra definida la variable global modulos, verifique el archivo de configuracion", 1);
            }

            $_SESSION['urlAnterior'] = isset($_SESSION['urlActual'] )?$_SESSION['urlActual'] :"";
            $_SESSION['urlActual'] = $_GET['url'];


            Session::set('URL_ACTUAL_COMPLETA', $_GET['url']);
            /*Manejo de url*/
            if(isset($_GET['url'])){

                $_GET['url'] = utf8_encode($_GET['url']);
                $url = filter_input(INPUT_GET, 'url',FILTER_SANITIZE_URL);
                $url = explode('/', str_replace(array('.php','.html','.htm'), '', $url));
                $url = array_filter($url);
				if(in_array($url[0], $this->idiomas)){

					$this->idiomaActual=$url[0];
					array_shift($url);
					if(count($url)<1){
						$url[0]='index';
					}
				}


            }

            unset($_GET['url']);
            if(count($_GET)>0){
               $this->args=$_GET;
            }

            $this->appRoot = str_replace(['index.php'], "", $_SERVER['PHP_SELF']);
			$GLOBALS['__URL_APP'] = $this->appRoot;

			$ini = substr($this->appRoot, 1);

			Session::set('URL_ACTUAL', $ini.Session::get('URL_ACTUAL'));
            /**
             * variable global con todos los parametros pasados via url
             */
            $GLOBALS['arrayParametros'] = $url;

            $this->getSubdominio($url);
            $this->procesarURL($url);

            if(count($this->args)>0){
                $this->procesarArgumentos();
            }
            //Debug::mostrarArray($_SERVER);
            $GLOBALS['_MODULO_ACTUAL'] = $this->modulo;
            $this->vista = new Pagina($this->controlador,$this->metodo,$this->modulo);
            $this->vista->idioma=$this->idiomaActual;
            $this->validacion();

        }catch(Exception $e){
            $this->procesarExcepcion($e);
        }

    }//fin constructor
    /**
     * Procesa el contenido de la url
     * Valida los modulos, controladores y metodos a consultar
     * @method procesarURL
     */
    private function procesarURL($url){
        $primerParam = array_shift($url);

		$URL = "/".$primerParam;
        $param = $this->validarNombre($primerParam,1);
       //Se valida si se ha solicitado un modulo por medio de un subdominio
        if(in_array($this->validarNombre($this->subdominio,1),$this->modulosExistentes)){
            $this->modulo=$this->validarNombre($this->subdominio,1);
            $this->moduloSubdominio=TRUE;
        }

        if(!in_array($param,$this->modulosExistentes) or $this->moduloSubdominio===TRUE){

            if(!Directorios::validar(app_dir)):
                /**
                 * Entra aca si es una app nueva
                 */
                $this->modulo="Jadmin";
                $this->controlador = 'init';
				$this->metodo="index";
				$init = substr($this->appRoot, 1);
				Session::set('URL_ACTUAL', $init.'jadmin/init');

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

                    }
                    $this->checkMetodo($param,true);
                }else{
                    $this->controlador=$this->modulo;
                    $this->checkMetodo($param,TRUE);
                }
            }else{
                $this->controlador=$this->modulo;
                $this->metodo='index';
            }
        }
        Session::set('URL_ACTUAL', $URL);
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
                if($insertArg){
                    $this->args[]=strtolower($metodo);
                }
            }

        }else{

            $this->metodo="index";
            if($insertArg){
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

            $_GET = array_merge($this->args,$gets);
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

            	$acceso = $acl->validarAcceso($this->controlador,$this->validarNombre($this->metodo, 2),strtolower($this->modulo));

			}else{
				$acceso=TRUE;
			}
            if($acceso===TRUE){
            	global $dataVista;

                $dataVista= new DataVista($this->modulo,$this->controlador,$this->metodo);
            	$this->vista->data = $dataVista;

                $nombreArchivo = $this->controlador . "Controller.class.php";
                /*
                 * Se verifica si es llamado el controlador principal del framework.
                 * Si es llamado el modulo interno "jadmin" el valor de rutaVista = 2, excepcion=3 default=1
                 */

                if(strtolower($this->controlador)=='jadmin' or strtolower($this->modulo)=='jadmin'){

                   $this->vista->rutaPagina=2;
                   $rutaArchivo=framework_dir.'Jadmin/Controllers/'.$nombreArchivo;

                }else
                {
                    $rutaArchivo = $this->obtenerControladorModulo($nombreArchivo);
                }

                $metodo = $this->metodo;
                /**
                 * Se valida la existencia del archivo,
                 * @deprecated Este lógica será cambiada proximamente debido a que el "autoload debe encargarse de validar existencia".
                 */
                if(file_exists($rutaArchivo) and is_readable($rutaArchivo)){

                    if(!empty($this->modulo)){
                        require_once $rutaArchivo;
                    }
                    $controlador = $this->controlador."Controller";

                    if(!is_callable(array($controlador,$metodo))){
                        /*En caso de que no se consiga metodo en la url
                         * Se llama un metodo por default llamado index y se agrega el
                         * valor metodo como un parametro get clave
                         *
                         */
                        array_unshift($this->args,$this->metodo);

                        if(count($this->args)>0){

                            $this->procesarArgumentos(2);
                        }
                        $this->metodo = 'index';
                    }
                }else{
                    /**
                     * En caso de que no exista el controlador se verifica que el parametro
                     * pasado para controlador sea un metodo del controlador por defecto.
                     *
                     * Si existe un modulo se buscara el controlador principal del modulo, caso contrario el
                     * controlador index y se vuelve
                     * a llamar al mismo metodo validación.
                     */

                     if(!empty($this->modulo)){
                         /**
                          * Cada Modulo tiene un controlador con el mismo nombre del módulo, por tanto
                          * es buscado dicho controlador como controlador por defecto.
                          */
                         $controlador = $this->validarNombre($this->modulo,1)."Controller";
                         $nameControl = $this->validarNombre($this->modulo,1);
                         if(!class_exists($controlador)){
                             $controlador = "IndexController";
                             $nameControl = "Index";
                         }
                     }else{
                         $controlador = "IndexController";
                         $nameControl = "Index";
                     }

                    if(method_exists($controlador, $this->validarNombre($this->controlador,2))){
                        $this->metodo = $this->controlador;

                    }
                    $this->controlador=$nameControl;
                    //$this->vista->validarDefiniciones($this->controlador,$this->metodo,$this->modulo);

                }//fin validacion de existencia del controlador.
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
        $metodo = $this->metodo;
        $retorno= array();
        #se instancia el controlador solicitado
        $nombreControlador = $controlador;
		$this->vista->data->idioma=$this->idiomaActual;
		$GLOBALS['dv']=$this->vista->data;

        $this->controladorObject = new $controlador();

        $this->controladorObject->modulo=$this->modulo;
        $controlador=& $this->controladorObject;
        if(method_exists($controlador, $metodo))
            //$controlador->$metodo($params);
            call_user_func_array([$controlador,$metodo], $args);
        else{
            throw new Exception("Error Processing Request", 404);
            Debug::string("No existe el metodo $metodo del controlador $nombreControlador",true);
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
                $nombre = str_replace(" ","",Cadenas::upperCamelCase(str_replace("-", " ",$str)));
            }else{
                $nombre = str_replace(" ","",Cadenas::lowerCamelCase(str_replace("-", " ",$str)));
            }
            return $nombre;
        }

    }
 } // END

