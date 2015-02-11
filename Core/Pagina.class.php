<?PHP
include_once framework_dir.'Clases/DomNodeRecursiveIterator.class.php'; 
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
     * Información pasada al layout y vista a renderizar
     * @param mixed $data
     */
    var $data;
    
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
     * Define directorio de layout a usar
     * @var $directorioLayout
     * @access private
     */
    private $directorioLayout;
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
        /**
     * Define el nombre del controlador requerido
     * @var $controlador
     */
    private $controlador;
    /**
     * Archivo vista a renderizar
     * @var $template
     * @access private
     */
     
    private $template;
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
    
    function renderizar($data,$nombreVista="",$urlAbsoluta=""){
        if(!empty($nombreVista)){
            $this->nombreVista = $nombreVista;
        }
        $DataTpl = $this->data->getTemplate();
        if(!empty($DataTpl)){
            $rutaVista = $this->procesarVistaAbsoluta();
            
        }else{
            $rutaVista = $this->obtenerRutaVista();
            if($this->rutaPagina==3){
                $rutaVista = $rutaVista. String::lowerCamelCase($this->nombreVista).".php";
            }else{
                if($this->controlador=='Excepcion'){
                    $rutaVista=$this->rutaExcepciones.String::lowerCamelCase($this->nombreVista).".php";
                }else{   
                    $rutaVista = $rutaVista.String::lowerCamelCase($this->controlador )."/". String::lowerCamelCase($this->nombreVista).".php";          
                }   
            }
        }
        if(!is_readable($rutaVista)){
            
            throw new Exception("Pagina no conseguida", 404);
        }
        $this->template=$rutaVista;
        
        if(!empty($this->layout) or $this->layout!==FALSE){
            $this->renderizarLayout($data);
        }else{
            
            throw new Exception("No se encuentra definida la plantilla", 120);
        }
    }//final funcion
    
    
    private function procesarVistaAbsoluta(){
        if($this->data->getPath()=="jida"){
            $this->urlPlantilla = DIR_PLANTILLAS_FRAMEWORK;
             
        }  
        return $this->urlPlantilla.String::lowerCamelCase($this->data->getTemplate()).".php";
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

           include_once $this->template;
           #$this->obtenerBloquesJS();
           $contenido = ob_get_clean();
           include_once $this->directorioLayout.$this->layout;
           $layout = ob_get_clean();
           echo $layout;
        else:
            
            throw new Exception("No se encuentra definido el layout para $this->template, controlador $this->controlador", 110);
            
        endif;
        
        if (ob_get_length()) ob_end_clean();
        
        
    
    }

     
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
        
    function establecerAtributos($arr) {
        $clase=__CLASS__;
        
        $metodos = get_class_vars($clase);
        foreach($metodos as $k => $valor) {
            if (isset($arr[$k])) {
                $this->$k = $arr[$k];
            }
        }
        
    }
    
    function printJS(){
        $js="";
        $this->checkData();
        $cont=0;
        $code= array();
        if(array_key_exists('code',$this->data->js)){
            $code = $this->data->js['code'];
            unset($this->data->js['code']);
        }
        foreach ($this->data->js as $key => $archivo) {
            
            if(is_string($key)){
                if($key==ENTORNO_APP){
                    foreach ($archivo as $key => $value){
                        $js.=Selector::crear('script',['src'=>$value],null,$cont);
                        if($cont==0) $cont=2;
                    }           
                }
            }
            else $js.=Selector::crear('script',['src'=>$archivo],null,$cont);
            if($cont==0) $cont=2;
        }
        if(count($code)>0){
            foreach ($code as $key => $value){
                if(array_key_exists('archivo',$value)){
                    $contenido = file_get_contents($this->obtenerRutaVista().$value['archivo'].".js");
                    $js.=Selector::crear('script',null,$contenido,$cont);    
                }else{
                    $js.=Selector::crear('script',null,$value['codigo'],$cont);
                }
                
            }
    
        }
        return $js;
    }
    function printCSS(){
        $css = "";
        
        $this->checkData();
        $cont=0;
        foreach ($this->data->css as $key => $files) {
            
            if(is_string($key)){
                if($key==ENTORNO_APP){
                    foreach ($files as $key => $value) {
                        if(is_array($value)) 
                            $css.=Selector::crear('link',$value,null,$cont);
                        else 
                            $css.=Selector::crear('link',['href'=>$value,'rel'=>'stylesheet', 'type'=>'text/css'],null,2);
                        if($cont==0) $cont=2;
                    }    
                }   
            }else{
                if(is_array($files)){
                    $css.=Selector::crear('link',$files,null,$cont);
                }else{
                    $css.=Selector::crear('link',['href'=>$files,'rel'=>'stylesheet','type'=>'text/css'],null,2);
                }
                if($cont==0) $cont=2;       
            }
        }
        return $css;
    }
    private function checkData(){
        if(!$this->data instanceof DataVista){
            $this->data = new DataVista();
            Debug::string("No se ha instanciado correctamente el objeto Data en el controlador $this->controlador", true);            
        }
    }
    /**
     * Verifica el DOM HTML de la vista y valida si existe código JS incrustado
     * @method obtenerBloquesJS
     */
    private function obtenerBloquesJS(){
        $dom = new DOMDocument();
        $dom->loadHTMLFile($this->template);
        
        $dtaScript = new DOMNodeRecursiveIterator($dom->getElementsByTagName('script'));
        $dom->removeChild($dtaScript);
        $dom->save();
        for($i=0;$i<count($dtaScript);++$i){
            $this->data->js['code'][] =$dtaScript[$i]->nodeValue;    
       }
        

    }
    
    
}
