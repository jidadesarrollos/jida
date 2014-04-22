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
     * 
     * 
     */
    private $controlador;
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
    private $args;
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
                $url = explode('/', str_replace('.php', '', $url));
                $url = array_filter($url);
            }
			/**
			 * variable global con todos los parametros pasados via url
			 * 
			 */
            $GLOBALS['arrayParametros'] = $url;
            $this->controlador = $this->validarNombre(array_shift($url),1);
            
            if(in_array($this->controlador,$this->modulosExistentes)){
                $this->modulo = $this->controlador;
                $this->controlador = $this->validarNombre(array_shift($url),1);
                
            }
             
            $this->metodo = $this->validarNombre(array_shift($url),2);
            $this->args = $url;
            
            if(count($this->args)>0){
                $this->procesarArgumentos();
            }
            
            if(!$this->controlador){
                /*
                 * Si no se pasa un controlador en la url, se buscará un controlador con el nombre del modulo
                 * Esto pasa solo si el primer parametro de la URL existe adentro del arreglo de modulos existentes
                 * */
                $this->controlador = $this->modulo;
            }
            if(!$this->metodo){
                $this->metodo = 'index';
            }
            
            $this->vista = new Pagina($this->controlador,$this->metodo,$this->modulo);
            
            $this->validacion();
        
        }catch(Exception $e){
            
            Excepcion::controlExcepcion($e);
        }
            
    }//fin constructor
    
    
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
        Arrays::mostrarArray($this);
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
			
            $acceso = $acl->validarAcceso($this->controlador,$this->metodo,strtolower($this->modulo));
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
                         $this->controlador="Excepcion";
                         $controlador = $this->controlador."Controller";
                         $this->metodo = 'error404';
                        
                    }
                    $this->controlador=$nameControl;
                    $this->vista->validarDefiniciones($this->controlador,$this->metodo,$this->modulo);
                    // if(is_callable($controlador,$this->validarNombre($this->controlador,2))){
                        // echo $this->controlador."<hr>";                     
                        // $this->metodo = $this->controlador;
                        // 
//                         
                        // //$this->validacion();
//                         
                    // }else{
                        // if(entorno_app=='dev')
                            // echo "no se consigue la ruta $rutaArchivo";exit;
                    // }
                }//fin validacion de existencia del controlador.
           }else{
                 
                 $this->vista->rutaPagina=3;
                 $this->controlador="Excepcion";
                 $controlador = $this->controlador."Controller";
                 $this->metodo = 'error403';
                 
             }        
            if(isset($controlador)){
                $this->ejecucion($controlador);
            }
            
            
             }catch(Exception $e){
                 
                #$data = array('title'=>"Error 403");
                #$this->mostrarContenido($data,'403');
                Excepcion::controlExcepcion($e);
            }  
       
    
    }//final funcion validacion
    

    
    /**
     * Obtiene el controlador requerido de un modulo especifico
     */
    private function obtenerControladorModulo($nombreArchivo){
        try{
            
            $rutaModulo="";
            if(!empty($this->modulo)){
                $rutaModulo =app_dir . "Modulos/" .$this->modulo."/Controller/".$nombreArchivo;
                 
            }else{
                
                $rutaModulo = app_dir.'Controller/'.$nombreArchivo;
            }
            
            return $rutaModulo;
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }

        
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
            
        $args = $this->args;
        $metodo = $this->metodo;
        $retorno= array();
        #se instancia el controlador solicitado
        
        $controlador = new $controlador;
        #llamada al metodo solicitado via url
        $controlador->$metodo();
        #retorno del metodo ejecutado
        $retorno=$controlador->data;
        
        
        /**
         * Se define el titulo para tag titile de la página
         */
        $retorno['title'] = (!empty($controlador->tituloPagina))?$controlador->tituloPagina:titulo_sistema;
        $retorno['metaDescripcion']=$controlador->metaDescripcion;
        $this->vista->obtenerDirectorioPlantillas();
        
        $this->vista->checkHeader($controlador->header);
        $this->vista->checkFooter($controlador->footer);

        
        $this->mostrarContenido($retorno,$controlador->vista);
        
    }//fin funcion ejecucion
    
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
    
    private function manejarError($archivo){
        echo "No existe el archivo controlador requerido : $archivo";
    }
    
    /**
     * Ajusta el nombre de los Controladores y Metodos
     * 
     * Realiza una modificación del string para crear nombres
     * de clases controladoras y metodos validas
     * @param int $tipoCamelCase Indica si el string a modificar debe ir en upper o lower CamelCase
     * @param string $str Cadena a formatear
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
