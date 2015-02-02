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
    var $metaDescripcion=meta_descripcion;
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
    protected $post;
    /**
     * Data Get pasada por url
     * @var array $get;
     */
    protected $get;
    /**
     * Objeto DataVista
     * @var object $dv;
     */
    var $dv;
    function __construct(){
        $this->instanciarHelpers();
        $this->post=& $_POST;
        $this->get =& $_GET;
        $this->dv = new DataVista();
        
        if($this->solicitudAjax()){
            $this->layout="ajax.tpl.php";
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
     * Metodo por defecto
     * 
     * Es ejecutado en caso de que no se haya pasado un metodo
     * al controlador
     */
    function index(){
        
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

} // END

?>