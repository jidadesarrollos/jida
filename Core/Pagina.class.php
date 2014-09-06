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
     * Archivo vista a renderizar
     * @var $vista
     * @access private
     */
     
    private $vista;
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
    var $directorioPlantillas="";
    /**
     * Define directorio de layout a usar
     * @var $directorioLayout
     * @access private
     */
     
    private $directorioLayout;
    /**
     * Indica si la ruta de la página a mostrar pertenece a la aplicación
     * en desarrollo o, si es una plantilla prederminada del framework
     * @var $rutaPagina
     * @access public
     * @example 1 =>Aplicacion 2=>Framework 3=>Excepcion
     */
    var $rutaPagina=1;
    /**
     * Determina si el contenido de la vista sera mostrado en un layout o entre un pre y un post
     * @var $usoLayout 
     */
     
    var $usoLayout;
    /**
     * Layout a usar para renderizar la vista a mostrar
     * @var $layout 
     */
     
    var $layout;
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
     * @var $rutaFramework
     * @access private
     */
    private $rutaFramework="";
    /**
     * Define la ruta de las plantillas del Framework
     * @var $rutaPlantillasFramework
     * @access private
     */
    private $rutaPlantillasFramework = "";
    
    private $rutaExcepciones="";
    
    
    function __construct($controlador,$metodo="",$modulo=""){
        
       $this->validarDefiniciones($controlador,$metodo,$modulo);
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
        if(!defined('DIR_LAYOUT_JIDA')){
            define('DIR_LAYOUT_JIDA',framework_dir."Layout/");
        }
        if(!defined('DIR_LAYOUT_APP')){
            define('DIR_LAYOUT_APP',app_dir.'Layout/');
        }
        if(defined('framework_dir')){
            $this->rutaFramework=framework_dir."Jadmin/Vistas/";
            
        }else{
            throw new Exception("No se encuentra definida la ruta de las vistas del admin jida. verifique las configuraciones", 1);
            
        }
        
        if(defined('DIR_EXCEPCION_PLANTILLAS')){
            
            $this->rutaExcepciones = DIR_EXCEPCION_PLANTILLAS;
        }else{
            
            $this->rutaExcepciones = DIR_PLANTILLAS_FRAMEWORK."error/";
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

    /**
     * Define los directorios por defecto a manejar
     * 
     * Los tipos de directorio son : 
     * <ul> <li>1 Aplicación</li> <li>2Jida </li> <li>3 Excepciones</li></ul>
     * @method definirDirectorios
     */
    function definirDirectorios(){
         /*Verificación de ruta de plantillas*/
        if($this->rutaPagina==1  or $this->rutaPagina==3){
            
            $this->urlPlantilla=directorio_plantillas;
            $this->directorioLayout=DIR_LAYOUT_APP;
        }elseif($this->rutaPagina==2){
            $this->urlPlantilla=DIR_PLANTILLAS_FRAMEWORK;
            $this->directorioLayout=DIR_LAYOUT_JIDA;
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
        
        if(!empty($nombreVista)){
            $this->nombreVista = $nombreVista;
        }
        #String::test($this->nombreVista);
        $rutaVista = $this->obtenerRutaVista();
        #String::test($rutaVista);
        
        if($this->rutaPagina==3){
            
            $rutaVista = $rutaVista. String::lowerCamelCase($this->nombreVista).".php";
        }else{
            
            if($this->controlador=='Excepcion'){
                
                $rutaVista=$this->rutaExcepciones.String::lowerCamelCase($this->nombreVista).".php";
            }else{
                $rutaVista = $rutaVista.String::lowerCamelCase($this->controlador )."/". String::lowerCamelCase($this->nombreVista).".php";
                   
            }
            
        }
        
        if(!is_readable($rutaVista)){
            echo "$rutaVista";exit;
            throw new Exception("Pagina no conseguida", 404);
               
            #$paginaError=($this->rutaPagina==3)?$this->nombreVista:"404";
            #$rutaVista = $this->obtenerVistaError($rutaVista,$paginaError);
            
        }
        $this->vista=$rutaVista;
        
        if(!empty($this->layout) or $this->layout!==FALSE){
            $this->renderizarLayout($data);
        }else{
            
           $this->renderizarPlantilla($data); 
        }
    }//final funcion
    
    /**
     * Renderiza una vista haciendo uso de un archivo HEADER y un FOOTER
     * @method renderizarPlantilla
     * @access private
     */
    
    private function renderizarPlantilla($data){
        global $dataArray;
        $dataArray = $data;
        
        include_once $this->header;
        include_once $this->vista;
        include_once $this->footer;   
    }
    
    /**
     * Renderiza una vista en un layout definido
     * @method renderizarLayout
     * @access private
     */
    private function renderizarLayout($data){
        global $dataArray ;
        
        $dataArray = $data;
        
        /* Permitimos almacenamiento en bufer */
        ob_start();
        
        if(!empty($this->layout) and file_exists($this->directorioLayout.$this->layout)):
           
           include_once $this->vista;
           $contenido = ob_get_clean();
           
           include_once $this->directorioLayout.$this->layout;
           $layout = ob_get_clean();
           echo $layout;
        else:
            
            $b = String::test($this->directorioLayout);
        endif;
        
        if (ob_get_length()) ob_end_clean();
        
        
    
    }
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
    
    function establecerAtributos($arr) {
        $clase=__CLASS__;
        
        $metodos = get_class_vars($clase);
        foreach($metodos as $k => $valor) {
            
            if (isset($arr[$k])) {
                $this->$k = $arr[$k];
            }
        }
        
    }
}



?>