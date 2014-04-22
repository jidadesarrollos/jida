<?PHP 
/**
 * Clase Vista
 * 
 * Clase manejadora de vistas requeridas
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 1.0 - 28/12/2013
 * @package Framework
 * @category Router
 * 
 */

class Pagina{
    
    /**
     * Define el nombre del controlador requerido
     * @var $controlador
     */
    private $controlador;
    
    /**
     * Nombre de la vista requerida
     * 
     * Por defecto el nombre de la vista es el mismo nombre
     * que el metodo solicitado
     * @var $nombreVista
     * @access private;
     */
    private $nombreVista;
    
    /**
     * Nombre del Modulo o componente al que pertenece el controlador
     */
    private $modulo;
    /**
     * Archivo de encabezado de la vista
     * @var $header;
     */
    var $header=header_default;
    
    
    /**
     * Archivo de cierre de la vista
     */
    var $footer=footer_default;
    
    /**
     * Define la ubicación de las plantillas HEADER y FOOTER
     * a utilizar en la pagina
     * @var string $urlPlantilla
     */
    var $urlPlantilla="";
    /**
     * Indica si la ruta de la página a mostrar pertenece a la aplicación
     * en desarrollo o, si es una plantilla prederminada del framework
     * @var $rutaPagina
     * @access public
     */
    var $rutaPagina=1;
    /**
     * Define la ruta de ubicación de las vistas de la aplicación en desarrollo
     * Por defecto es 1
     * <ul>
     * <li>1 : Ruta Aplicación</li>
     * <li>2: Ruta Framework</li>
     * @var $rutaApp
     */
    /**
     * Define la ruta de los modulos del framework;
     */
    private $rutaFramework="";
    /**
     * Define la ruta de las plantillas del Framework
     */
    private $rutaPlantillasFramework = "";
    
    private $rutaExcepciones="";
    function __construct($controlador,$metodo="",$modulo=""){
        try{
           $this->validarDefiniciones($controlador,$metodo,$modulo);
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
        
    }
    /**
     * Verifica todos los datos pasados a la clase para la carga
     * de la pagina
     * @method validarDefiniciones
     * @access public
     * @var string $controlador Nombre del controlador a validar
     * @var string $metodo Nombre del metodo a ejecutar
     * @var string $modulo Módulo en el cual se encuentra el controlador buscado. 
     */
    function validarDefiniciones($controlador,$metodo="",$modulo=""){
        
        if(defined('jida_admin_vistas_dir')){
            $this->rutaFramework=jida_admin_vistas_dir;
            
        }else{
            throw new Exception("No se encuentra definida la ruta de las vistas del admin jida. verifique las configuraciones", 1);
            
        }
        if(defined('plantillas_framework_dir')){
            $this->rutaPlantillasFramework = plantillas_framework_dir;
        }else{
            throw new Exception("No se encuentra definida la ruta de las plantillas del framework. verifique las configuraciones", 1);
        }
        
        if(defined('plantillas_excepciones_dir')){
            $this->rutaExcepciones = plantillas_excepciones_dir;
        }else{
            $this->rutaExcepciones = plantillas_framework_dir."error/";
        }
        #Ruta para vistas de la aplicacion
        if(!empty($modulo)){
            $this->rutaApp=app_dir ."Modulos/".ucwords($modulo)."/Vistas/";
        }
        else{
            $this->rutaApp=app_dir ."Vistas" . "/" ;
        }
        if(!empty($controlador))
            $this->controlador = $controlador;
        if(!empty($metodo)){
            $this->nombreVista=$metodo;
        }
    }
    
    function obtenerDirectorioPlantillas(){
         /*Verificación de ruta de plantillas*/
        if($this->rutaPagina==1){
            $this->urlPlantilla=directorio_plantillas;
        }elseif($this->rutaPagina==2){
            $this->urlPlantilla=plantillas_framework_dir;
        }
        
    }

  
    /**
     * Muestra la vista del metodo solicitado
     * @method renderizar
     * @access public
     * @param array $data Variable instanciada como global para uso requerido
     * de datos pasados desde el controlador
     * 
     * @param string nombreVista Nombre del archivo vista a mostrar, por defecto
     * se busca un archivo con el mismo nombre del metodo del controlador requerido.
     * 
     */
    
    function renderizar($data,$nombreVista=""){
       try{
           global $dataArray ;
            $dataArray = $data;
            if(!empty($nombreVista)){
                $this->nombreVista = $nombreVista;
            }
            
            $rutaVista = $this->obtenerRutaVista();
            if($this->rutaPagina==3){
                $rutaVista = $rutaVista. $this->nombreVista.".php";
            }else{
                $rutaVista = $rutaVista.String::lowerCamelCase($this->controlador )."/". $this->nombreVista.".php";
            }
            if(!is_readable($rutaVista)){
                $paginaError=($this->rutaPagina==3)?$this->nombreVista:"404";
                $rutaVista = $this->obtenerVistaError($rutaVista,$paginaError);
                
            }
            
            include_once $this->header;
            
            include_once $rutaVista;
            include_once $this->footer;
               
       }catch(Exception $e){
           Excepcion::controlExcepcion($e);
       }
    }//final funcion
    
    /**
     * Muestra una página de error
     * 
     * 
     */
     
     
    private function requiresJs(){
        
    }
    private function obtenerVistaError($rutaVista,$vistaError="404"){
        try{
            $directorioError="";
            $_SESSION['ruta'] = $rutaVista;
            
            $directorioError = $this->rutaExcepciones."$vistaError.php";
            
            return $directorioError;
            
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }    
        
    }
    /**
     * Verifica la ruta a utilizar para la vista
     * @return string $rutaPagina
     */
    private function obtenerRutaVista(){
        
        switch ($this->rutaPagina) {
            case 1:
                $rutaVista = $this->rutaApp;
                            
                break;
            
            case 2:
                $rutaVista = $this->rutaFramework;
                    
                break;
            
            default:
                $rutaVista = $this->rutaExcepciones;
                break;
        }
        
        return $rutaVista;
    }
    
    function checkHeader($data){
    
        if(!empty($data)){
            $this->header=$this->urlPlantilla.$data;
            
        }
    }
    
    function checkFooter($data){
        if(!empty($data)){
            $this->footer=$this->urlPlantilla.$data;
        }
        
    }
    
    function pagina403(){
        
    }
}



?>