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
    private $controlador;
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
     * @var object vista
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
     * Arreglo de modulos existentes
     * 
     * El arreglo se obtiene por medio de la funcion obtenerModulos, la cual debe
     * existir en el archivo de configuracion del framework
     * @var array $modulosExistentes
     */
     private $modulosExistentes=array();
    function __construct(){
        try{
            
            if(isset($GLOBALS['modulos']) and is_array($GLOBALS['modulos'])){
                $this->modulosExistentes=$GLOBALS['modulos'];
            }else{
                throw new Exception("No se encuentra definida la variable global modulos, verifique el archivo de configuracion", 1);
            }
            $_SESSION['urlAnterior'] = isset($_SESSION['urlActual'] )?$_SESSION['urlActual'] :"";
            $_SESSION['urlActual'] = $_GET['url'];
            if(isset($_GET['url'])){
                $url = filter_input(INPUT_GET, 'url',FILTER_SANITIZE_URL);    
                $url = explode('/', str_replace(array('.php','.html','.htm'), '', $url));
                $url = array_filter($url);
            }
            /**
             * variable global con todos los parametros pasados via url
             */
            $GLOBALS['arrayParametros'] = $url;
            $this->getSubdominio($url);
            $this->procesarURL($url);
            if(count($this->args)>0){
                $this->procesarArgumentos();
            }
            
            $this->vista = new Pagina($this->controlador,$this->metodo,$this->modulo);
            $this->validacion();
        
        }catch(Exception $e){
            
            Excepcion::controlExcepcion($e);
        }
            
    }//fin constructor
    /**
     * Procesa el contenido de la url
     * Valida los modulos, controladores y metodos a consultar
     * @method procesarURL
     */
    private function procesarURL($url){
        
        $param = $this->validarNombre(array_shift($url),1);
        
        if(!in_array($param,$this->modulosExistentes)){
            //Se valida si se ha solicitado un modulo por medio de un subdominio
            if(in_array($this->validarNombre($this->subdominio,1),$this->modulosExistentes)){
                $this->modulo=$this->validarNombre($this->subdominio,1);   
            }
            //Se verifica si existe el controlador
            if($this->checkController($param."Controller")){
                $this->controlador=$param;
                if(count($url)>0 ){
                    $param =$this->validarNombre(array_shift($url),1);
                    $this->checkMetodo($param);
                }else{
                    $this->metodo='index';
                }
            }else{
                /**
                 * Si entra aqui el controlador a ejecutar es el Index publico
                 * */
                $this->controlador='Index';
                $this->checkMetodo($param);
            }
            
        }else{
            $this->modulo=$param;
            if(count($url)>0){
                $param =$this->validarNombre(array_shift($url),1);

                //Se valida si existe un controlador en la url
                if($this->checkController($param."Controller")){
                    $this->controlador=$param;
                    if(count($url)>0){
                        $param =$this->validarNombre(array_shift($url),1);
                        
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
        
        $this->args = array_merge($this->args, $url);
    }
    /**
     * Verifica la existencia de un metodo solicitado
     * @method checkMetodo
     * @param string $metodo Nombre del metodo a consultar
     */
    private function checkMetodo($metodo='index',$insertArg=FALSE){
        
        if(method_exists($this->controlador."Controller", $this->validarNombre($metodo,2))){
            $this->metodo=$metodo;
            return true;
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
        
        try{
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
            
            $_GET = $gets;
            
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
        
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
            $acl = new ACL();
            
            $acceso = $acl->validarAcceso($this->controlador,$this->validarNombre($this->metodo, 2),strtolower($this->modulo));
            
            #exit;
            if($acceso===TRUE){
                
                $nombreArchivo = $this->controlador . "Controller.class.php";
                /*
                 * Se verifica si es llamado el controlador principal del framework.
                 * Si es llamado el modulo interno "jadmin" el valor de rutaVista = 2, excepcion=3 default=1
                 */
                if(strtolower($this->controlador)=='jadmin' or strtolower($this->modulo)=='jadmin'){
                    
                   $this->vista->rutaPagina=2;
                   $rutaArchivo=framework_dir.'Jadmin/Controllers/'.$nombreArchivo;
                   
                }else
                if(strtolower($this->controlador)=='excepcion' or strtolower($this->modulo)=='excepcion'){
                    $rutaArchivo=framework_dir."ControllerFramework/".$nombreArchivo;
                    $this->vista->rutaPagina=3;
                }
                else{
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
                    
                    $instancia = new $controlador;
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
                        
                    }else{
                        
                        $this->vista->rutaPagina=3;
                        if(!defined('CONTROLADOR_EXCEPCIONES'))
                            throw new Exception("No se encuentra definido el controlador de excepciones", 10);
                            
                        $this->controlador=CONTROLADOR_EXCEPCIONES;
                        
                        $controlador = $this->controlador."Controller";
                        $this->metodo = 'error404';
                        
                    }
                    $this->controlador=$nameControl;
                    $this->vista->validarDefiniciones($this->controlador,$this->metodo,$this->modulo);

                }//fin validacion de existencia del controlador.
           }else{
               #Arrays::mostrarArray(Session::get('acl','jadmin'));exit;
                 throw new Exception("No tiene permisos", 403);
                 
             }        
            if(isset($controlador)){
                $this->ejecucion($controlador);
            }
            
            
         }catch(Exception $e){
            
            $this->procesarExcepcion($e);
            ;
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
        $this->mostrarContenido($retorno,$controlador->vista);
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
        #retorno del metodo ejecutado
        $retorno=$controlador->data;        
        /**
         * Se define el titulo para tag titile de la página
         */
        $retorno['title'] = (!empty($controlador->tituloPagina))?$controlador->tituloPagina:titulo_sistema;
        $retorno['metaDescripcion']=$controlador->metaDescripcion;
        $this->mostrarContenido($retorno,$controlador->vista);
        
        
    }//fin funcion ejecucion
    /**
     * Verifica las propiedades de los directorios Layout,header y footer para la vista
     * @method checkDirectoriosView
     */
    private function checkDirectoriosView(){
        if(is_object($this->controladorObject)):
            $this->vista->layout = $this->controladorObject->layout;
        
            $this->vista->definirDirectorios();
            if(!$this->vista->layout){
                $this->vista->checkHeader($this->controladorObject->header);
                $this->vista->checkFooter($this->controladorObject->footer);    
            }
        endif;
        
    }
    /**
     * Realiza la ejecución del Controlador a instanciar
     * @method ejecutarController
     */
    private function ejecutarController($controlador,$params=null,$checkDirs=true){
        
        $args = $this->args;
        $metodo = $this->metodo;
        $retorno= array();
        #se instancia el controlador solicitado
        
        $this->controladorObject = new $controlador;
        $controlador=& $this->controladorObject;
        
        $controlador->$metodo($params);
        if($checkDirs)
            $this->checkDirectoriosView();
        #llamada al metodo solicitado via url
                
        return $controlador;
    }
    
    private function procesarExcepcion($excepcion){
        
        $ctrlError = $this->controlador."Controller";
        $this->controladorObject = new $ctrlError;
        $this->checkDirectoriosView();
        $this->controlador='ExcepcionController';
        $this->metodo='error';
        
        $this->vista->rutaPagina=($this->modulo=='Jadmin')?2:3;
        $this->vista->definirDirectorios();
        $this->vista->establecerAtributos(array('controlador'=>'Excepcion','modulo'=>$this->modulo));
        
        $ctrl = $this->ejecutarController($this->controlador,$excepcion,false);
        
        $retorno=$ctrl->data;
        
        $this->mostrarContenido($retorno,$ctrl->vista);
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
    private function mostrarContenido($retorno,$vista=""){
        
        $this->vista->renderizar($retorno,$vista);
        
    }
    /**
     * Ajusta el nombre de los Controladores y Metodos
     * 
     * Realiza una modificación del string para crear nombres
     * de clases controladoras y metodos validas
     * @param string $str Cadena a formatear
     * @param int $tipoCamelCase 1 Upper 2 Lower
     */
    private function validarNombre($str,$tipoCamelCase){
        if(!empty($str)){
            if($tipoCamelCase==1){
                $nombre = str_replace(" ","",String::upperCamelCase(str_replace("-", " ",$str)));    
            }else{
                $nombre = str_replace(" ","",String::lowerCamelCase(str_replace("-", " ",$str)));
            }
            return $nombre;    
        }
        
    }
 } // END
?>
