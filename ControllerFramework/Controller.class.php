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
	/**
	  * Define el titulo de la pagina a colocar en la etiqueta <title> del head del sitio
	  * 
	  * @var string $tituloPagina
	  * @access public
	  */
	var $tituloPagina="";
	/**
	 * Define la ruta del archivo header  a usar en la vista del controlador, 
     * en caso de no encontrarse definido se usa el header por defecto
     * @var $header
     * @access public
	 */
	var $header="";
	
    /**
     * Define la ruta del archivo Footer de la vista del controlador, 
     * en caso de no encontrarse definido se usa el header por defecto
     * 
     * @var $footer
     * @access public
     */
     var $footer="";
     
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
     *
     * Define la URL principal de acceso para el controlador (En caso de ser usada)
     * 
     * Puede ser instanciada en el controlador con la URL principal
     * @var $url
     * @access protected
     *  
     *  
     */
    protected $url; 
    /**
     * Ejecuta un funcionamiento por defecto en caso de no encontrarse el
     * controlador requerido.
     * @method noController
     * 
     */
    protected function noController(){
        
    }
    /**
     * Ejecuta funcionamiento por defecto en caso de no encontrarse
     * definido el metodo solicitado
     * @method noMetodo
     * 
     */
    protected function noMetodo(){
        echo "<h2>Lo sentimos debe definir un metodo</h2>";
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
     * Valida y flintra el contenido de una variable como Entero
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

} // END

?>