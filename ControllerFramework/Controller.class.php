<?PHP 
/**
 * Clase Modelo [PADRE] de Controladores
 * 
 *
 * @package Framework
 * @category Controlador
 * @author  Julio Rodriguez <jirc48@gmail.com>
 * 
 */
class Controller {
    
    var $urlCanonical=url_sitio;
    /**
     *  Define el layout a usar por el controlador
     *  @var $layout
     */
    var $layout=FALSE;
	/**
	  * Define el titulo de la pagina a colocar en la etiqueta <title> del head del sitio
	  * 
	  * @var string $tituloPagina
	  * @access public
	  */
	var $tituloPagina="";
    /**
     * Define el contenido de la meta-etiqueta description para uso de los buscadores
     * @var $metaDescripcion;
     */
    var $metaDescripcion;
    protected $helpers = array();
     /**
      * Define el Modelo a usar en el controlador;
	  * 
	  * @var $modelo
	  * @access protected
      */
    protected $modelo="";
     /**
      * Permite especificar una vista para el metodo
      * 
      * Si la propiedad se encuentra vacia el framework busca una view con el mismo nombre
      * del metodo, si se requiere que el metodo use la misma vista que otro metodo o no se desea
      * crear una vista nueva, se puede especificar en esta propiedad cual es la vista a usar
      * @var string $vista
      */
    var $vista="";
     /**
      * Arreglo que contiene la información que desee pasarse a la vista
      * 
      * Si desea pasarse información a la vista, la misma debe ser guardada en eñ arreglo como
      * una nueva posición asociativa con el nombre escogido por el programador, luego esta podrá
      * ser accedida desde la vista, por medio del arreglo global $dataArray;
	  * @var $data
      * @deprecated
      */
    var $data=array();
    /**
     * Archivos Javascript Requeridos
     * @var array $requireJS
     */
    var $requireJS=array();
    /**
     * Archivos CSS Requeridos en la vista
     * @var array $requireCSS
     * @access public
     */
    var $requireCSS=array();
    /**
     *
     * Define la URL principal de acceso para el controlador (En caso de ser usada)
     * Puede ser instanciada en el controlador con la URL principal
     * @var $url
     * @access protected
     */
    protected $url; 
    /**
     * Data POST de Formulario
     * @var array $post
     */
    private $post;
    /**
     * Data Get pasada por url
     * @var array $get;
     */
    private $get;
    /**
     * Objeto DataVista
     * @var object $dv;
     */
     
    private $_clase;
    /**
     * Nombre del controlador
     */
    private $_nombreController;
    private $_modulo;
    /**
     * @var url $__url URL Actual Registra la URL ingresada en el navegador
     * @access protected
     */
    var $__url;
    /**
     *
     * @var object $dv Instancia de clase DataVista
     * @see DataVista object
     */
    var $dv;
    var $usuario;
    /**
     * @var object $usuario Objeto User instanciado al iniciar sesion. Si la sesion no esta iniciada retorna vacio 
     */
    function __construct(){
        
        $this->instanciarHelpers();
        $this->post=& $_POST;
        $this->get =& $_GET;
        
        $this->_clase=get_class($this);
        $this->_nombreController = str_replace("Controller", "", $this->_clase);
        $this->_modulo = $GLOBALS["_MODULO_ACTUAL"];
        $this->dv = new DataVista();
        $this->url = $this->urlController();
        $this->usuario = Session::get('Usuario');
        if($this->solicitudAjax()){
            $this->layout="ajax.tpl.php";
        }
        $this->getModelo();
        $this->dv->usuario = Session::get('Usuario');
        if(count($this->helpers)>0){
            for($i=0;$i<count($this->helpers);++$i){
                $object = $this->helpers[$i];
                
                if(is_object($object)){
                    $this->$$object = new $object();
                }
            }
        }
        
    }
    
    private function instanciarHelpers(){
        if(count($this->helpers)>0){
            foreach ($this->helpers as $key => $propiedad) {
                $this->$propiedad = new $propiedad();
            }
        }
    } 
    /**
     * Filtra contenido de Texto
     * 
     * Convierte el contenido de una variable en codigo aceptado HTML
     * @param string $valor Valor capturado a validar
     * @return string $valor Valor sanado.
     */
    protected function getString($valor){
        
        if(!empty($valor)){
            $valor  = htmlspecialchars($valor,ENT_QUOTES);
        }
        return $valor;
        
    } 
    /**
     * Valida y filtra el contenido de una variable como Entero
     * 
     * @param $string $valor
     * @return int $valor;
     */
    protected function getEntero($valor){
       if(!empty($valor)){
           $valor = filter_var($valor,FILTER_VALIDATE_INT);
           return $valor;
       }
       return 0;
    }
    /**
     * Valida y filta el contenido de una variable como Float
     * @method getDecimal
     * @param $string $valor
     * @return flaot $valor;
     */
    protected function getDecimal($valor){
       if(!empty($valor) and is_float($valor)){
           return $valor;
       }
       return 0; 
    }
    /**
     * Ejecuta un formulario de manera generica
     * 
     * El formulario debe ser pasado por medio de un parametro get "form". Si el formulario
     * debe ejecutarse en modo de edición se debe pasar un parametro get "id"
     * 
     * @method process
     */
    protected function process(){
       if(isset($_GET['form'])){
           $nombreForm = String::upperCamelCase($_GET['form']);
           $tipoForm=1;
           $pk="";
           if(isset($_GET['id'])){
               $tipoForm=2;$pk=$_GET['id'];
           }
           $formulario = new Formulario($nombreForm,$tipoForm,$pk);
       }else{
           throw new Exception("No se ha definido el formulario a ejecutar", 100);
       }
    }
	
	/**
	 * Valida si se ha realizado una solicitud ajax (se debe usar el plugin javascript jd.ajax)
	 * 
	 * Verifica la existencia del post s-ajax
	 * @method solicitudAjax
	 * @return boolean 
	 */
	protected function solicitudAjax(){
		if(isset($_POST['s-ajax']))
			return true;
		else
			return false;
	}
    /**
     * Setter para propiedad url
     * @method setUrl
     */
    protected function _setUrl($url){
        $this->url = $url;
    }
    
    protected function obtPost($param){
        
        if(isset($this->post[$param])){
            
            return $this->post[$param];
        }else{
            return FALSE;
        }   
    }
     /**
     * Retorna el valor get solicitado, false si el valor no es conseguido
     * @method get
     * @param string $param Dato a solicitar 
     * 
     */
    protected function get($param){
        if(isset($this->get[$param]))
            return $this->get[$param];
        else
            return false;
    }
    /**
     * Retorna el valor post solicitado, false si el valor no es conseguido
     * @method post
     * @param string $param Dato a solicitar 
     * 
     */
    protected function post($param){
        if(isset($this->post[$param]))
            return $this->post[$param];
        else
            return false;
    }
    
    /**
     * Devuelve la URL correspondiente al metodo que hace la llamada
     * 
     * @method urlActual
     */
    protected function urlActual($valor=1){
        $quienLlama = debug_backtrace(null,$valor+1)[$valor]['function'];
        return $this->url.$this->convertirNombreAUrl($quienLlama)."/";
    }
    
    /**
     * Convierte el nombre pasado en la estructura estandard de urls
     * 
     * La estructura consiste en todo en minusculas y separado por guiones
     * @method convertirNombreAUrl
     * @param string $nombre Nombre a convertir (metodo o controlador);
     * @return string $url
     */
    protected function convertirNombreAUrl($nombre){
        $coincidencias = preg_split('#([A-Z][^A-Z]*)#', $nombre, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        return strtolower(implode("-",$coincidencias));
    }
    /**
     * Retorna la url del controlador actual
     * @method urlController
     */
    protected function urlController(){
        if(isset($GLOBALS['_MODULO_ACTUAL'] )){
            $controller = str_replace("Controller", "", $this->_clase);
            if(strtolower($this->_modulo)==strtolower($controller)){
                $this->url = "/".strtolower($this->_modulo)."/";
            }else{
                if(empty($this->_modulo)){
                    $this->url = "/".$this->convertirNombreAUrl($controller)."/";
                }else{
                    $this->url = "/".strtolower($this->_modulo)."/".$this->convertirNombreAUrl($controller)."/";    
                }
                    
            }
               
        }
        
        return $this->url;
    }
    protected function urlModulo(){
          return "/".strtolower($this->_modulo)."/";
    }
    /**
     * Devuelve la estructura de la url solicitada
     * @method getUrl
     * @param string $metodo Nombre del metodo del cual se quiere obtener la url, si no es pasado se devolvera la url actual
     * @param string $controlador Nombre del controlador [aun no funcional] 
     * @return string $url
     */
    protected function getUrl($metodo="",$data=array(),$controlador=""){
        if(!empty($metodo)){
            
            if(method_exists($this->_clase,$metodo)){
                $params= "";
                if(count($data)>0){
                    foreach ($data as $key => $value) 
                        $params.="$key/$value/";
                }
                
                return $this->urlController().$this->convertirNombreAUrl($metodo)."/".$params;
            }else{
                throw new Exception("El metodo pasado para estructurar la url no existe", 301);
            }
            
        }else{
            return $this->urlActual(2);
        }
        
    }
    
    /**
     * Verifica si el controlador tiene un modelo correspondiente
     * 
     * Para que el modelo del controlador sea conseguido debe tener el nombre del Controlador
     * en singular
     * @method getModelo;
     * 
     */
    private function getModelo(){
        if(!empty($this->modelo)){
            if(class_exists($this->modelo)){
                $this->modelo = new $this->modelo;
            }else{
                throw new Exception("El objeto $this->modelo especificado como modelo no existe", 1);
                
            }
        }else{
            $words = preg_split('#([A-Z][^A-Z]*)#', $this->_nombreController, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            $arrayModel=array();
            foreach ($words as $key => $word) {
                if(substr($word, strlen($word)-2)==PLURAL_CONSONANTE){
                    $arrayModel[]=substr($word, 0,strlen($word)-2);
                }elseif(substr($word, strlen($word)-1)==PLURAL_ATONO){
                    $arrayModel[]=substr($word, 0,strlen($word)-1);
                }
            }
            
            $model = (count($arrayModel)>0)?implode($arrayModel):$this->_nombreController;
            if(class_exists($model)){
                $this->modelo = new $model;
            }
        }
            
    }
    /**
     * funcion estandard para eliminar registros, funcional solo con modelos que
     * extiendan del objeto DataModel.
     * @see DataModel
     * @method eliminar
     * @param mixed $id 
     */
    protected function eliminarDatos($id){
        
            if($this->getEntero($id)==0){
                $id = $this->obtenerListaGet($id);
                if(!$id)
                    throw new Exception("El valor pasado para eliminar el objeto no es valido", 602);
            }
            return ($this->modelo->eliminar($id));    
           
    }
    /**
     * Genera una excepción 404.
     */
    protected function _404(){
        throw new Exception("No se consigue el enlace solicitado", 404);
        
    }
    
    
    protected function obtenerListaGet($lista){
        $arr = explode(",",$lista);
        $band = true;
        foreach ($arr as $key => $value) {
            if($this->getEntero($value)==0){
                $band=false;
            }
        }
        if($band==FALSE)
            return $band;
        else return $arr;
    }
    /**
     * Devuelve contenido para una solicitud via ajax
     * 
     * Imprime la respuesta de la solicitud realizada sin esperar llegar a la vista
     * @param mixed $respuesta Respuesta de la solicitud ajax
     * @param int tipo 1 json, 2 html. 
     */
    protected function respuestaAjax($respuesta,$tipo=2){
        if($tipo==2){
            echo $respuesta;
        }else{
            print(json_encode($respuesta));    
        }
        exit;
    } 
    
    protected function respuestaJson($respuesta){
        print(json_encode($respuesta));
        exit;
    }
    /**
     * Realizar una redireccion
     * @method redireccionar
     */
    protected function redireccionar($url){
        header('location:'.$url.'');exit;
    }
    

} // END

?>