<?PHP
/**
 * Clase Modelo [PADRE] de Controladores
 *
 *
 * @package Framework
 * @category Controlador
 * @author  Julio Rodriguez jirc48@gmail.com
 *
 *
 */

namespace Jida\Core;
use Jida\Core\Manager\DataVista as DataVista;
use Jida\Helpers as Helpers;
use Jida\Helpers\Cadenas as Cadenas;
class Controller {
    
    use \Jida\Core\ObjetoManager;
    
	/**
	 * Define el tema de diseño a implementar en la aplicacion
	 *
	 * El tema será buscado en la carpeta Aplicacion/layout/nombreTema
	 * En caso de que no se encuentre ningun tema definido, los templates seran
	 * buscado en la carpeta layout como en las versiones anteriores.
	 *
	 * @since 1.4
	 * @var temaLayout
	 */
	var $temaLayout="";
	/**
	 * Define si la aplicación maneja multiples idiomas
	 *
	 * Si es colocada en true el controller validara la variable $idioma y la incluira en
	 * las urls
	 *
	 * @var boolean multiidioma
	 */
	var $multiidioma=FALSE;

    var $urlCanonical=URL_APP;
    /**
     *  Define el layout a usar por el controlador
     *  @var $layout
     */
    var $layout=FALSE;
	/**
	 * Define el idioma manejado al momento de la ejecucion del controlador
	 * @var string $idioma;
	 */
	var $idioma;
	/**
	  * Define el titulo de la pagina a colocar en la etiqueta title del head del sitio
	  *
	  * @var string $tituloPagina
	  * @access public
	 *  @deprecated
	  */
	var $tituloPagina="";
    /**
     * Define el contenido de la meta-etiqueta description para uso de los buscadores
     * @var $metaDescripcion;
	 * @deprecated
     */
    var $metaDescripcion;
	/**
	 * Define un metodo a ejecutar previo a la ejecucion de metodos accedidos por url
	 *
	 * @var string $preEjecucion
	 */
	var $preEjecucion="";
	/**
	 * Define un metodo a ejecutar posterior a la ejecucion de metodos accedidos por url
	 * @var string $postEjecucion
	 */
	var $postEjecucion="";
    protected $helpers = array();
     /**
      * Define el Modelo a usar en el controlador;
	  *
	  * @var $modelo
	  * @access protected
      */
    protected $modelo="";
    /**
     * Permite definir los modelos a usar en el controlador
     *
     * Los modelos agregados en el arreglo podrán ser accedidos como propiedad posteriormente
     * @var $modelos
     * @access Protected
     */
    protected $modelos =[];
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
      * ser accedida desde la vista, por medio del arreglo global $;
	  * @var $data
      * @deprecated
      * @see Pagina::data
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
	 * @var array $request Arreglo $_REQUEST
	 */
	private $request;
    /**
     * Objeto DataVista
     * @var object $dv;
     */


    private $_clase;
    /**
     * Nombre del controlador
     */
    private $_nombreController;
    protected $_modulo;
	protected $_controlador;
	protected $_metodo;

	var $metodo;
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

	/**
     * @var object $usuario Objeto User instanciado al iniciar sesion. Si la sesion no esta iniciada retorna vacio
     */
    var $usuario;

     /**
     * Define el funcionamiento que realiza el framework para manejar
	 * los parametros en las URL
     * @var $manejoParams
     */
    var $manejoParams=MANEJADOR_PARAMS;
	/**
	 * Registra el nombre del controlador para la url
	 * @var string $_controladorURL
	 *
	 */
	private $_controladorURL;
	private $_urlBase;
    function __construct(){
    	global $dataVista;
		$this->_urlBase = (defined('URL_BASE'))?URL_BASE:'';
		
		/**
		 * Si es capturada una excepción el objeto DAtaVista no es pasado a la segunda instancia
		 * del controlador con error, por tanto se crea un objeto DataVista vacio
		 */
		if(!$dataVista instanceof DataVista)		$dataVista = new DataVista();
		if(defined('APP_MULTIIDIOMA')) $this->multiidioma = APP_MULTIIDIOMA;
        $this->dv = $dataVista;
		$this->idioma=& $this->dv->idioma;

        $this->instanciarHelpers();
        $this->instanciarModelos();
        $this->validarVarGlobales();
		$this->_clase = get_class($this);
		$clase= explode("\\", $this->_clase);

        $this->_nombreController = str_replace("Controller", "", end($clase));


        $this->url = $this->urlController();
		if(Helpers\Sesion::get('Usuario')instanceof User)
        	$this->usuario = Helpers\Sesion::get('Usuario');
		else{
			$clase = MODELO_USUARIO;
			$this->usuario = new $clase;
		}

		$this->_modulo=$this->dv->modulo;
		$this->_metodo=$this->dv->metodo;
		$this->_controlador=$this->dv->controlador;

        if($this->solicitudAjax()){
            $this->layout="ajax.tpl.php";
        }

        $this->getModelo();
        $this->dv->usuario = Helpers\Sesion::get('Usuario');
        if(count($this->helpers)>0){
            for($i=0;$i<count($this->helpers);++$i){
                $object = $this->helpers[$i];

                if(is_object($object)){
                    $this->$$object = new $object();
                }
            }
        }

    }
	/**
	 * Procesa las variables de parametros globales
	 * @since 1.4
	 * @method validarVarGlobales
	 */
	function validarVarGlobales($bug=false){
		$this->post=& $_POST;
        $this->get =& $_GET;
        $this->request=& $_REQUEST;
		;
	}

    private function instanciarHelpers(){
        if(count($this->helpers)>0){
            foreach ($this->helpers as $key => $propiedad) {
            	$helper = '\\Jida\\Helpers\\'.$propiedad;
                $this->$propiedad = new $helper();
            }
        }
    }
    private function instanciarModelos(){
        if(count($this->modelos)>0){
            foreach ($this->modelos as $key => $propiedad)
            {
                if(class_exists($propiedad))
                    $this->$propiedad = new $propiedad();
            }
        }
    }
    /**
     * @see self::obtString
	 * @deprecated 0.4
     */
    protected function getString($valor){
    	return $this->obtString($valor);
	}
	/**
     * Filtra contenido de Texto
     *
     * Convierte el contenido de una variable en codigo aceptado HTML
     * @param string $valor Valor capturado a validar
     * @return string $valor Valor sanado.
	 * @since 1.5
     */
    protected function obtString($valor){

        if(!empty($valor)){
            $valor  = htmlspecialchars($valor,ENT_QUOTES);
        }
        return $valor;

    }
	/**
	 * @deprecated 0.5
	 * @see self::obtEntero
	 */
	protected function getEntero($valor){
		return $this->obtEntero($valor);
	}
    /**
     * Valida y filtra el contenido de una variable como Entero
     *
     * @param $string $valor
     * @return int $valor;
	 * @since 1.5
     */
    protected function obtEntero($valor){
       if(!empty($valor)){
           $valor = filter_var($valor,FILTER_VALIDATE_INT);
           return $valor;
       }
       return false;
    }
	/**
     * @see self:obtDecimal
	 * @deprecated
     */
    protected function getDecimal($valor){
    	return obtDecimal($valor);
	} 
    /**
     * Valida y filta el contenido de una variable como Float
     * @method getDecimal
     * @param $string $valor
     * @return flaot $valor;
     */
    protected function obtDecimal($valor){
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
           $nombreForm = Cadenas::upperCamelCase($_GET['form']);
           $tipoForm=1;
           $pk="";
           if(isset($_GET['id'])){
               $tipoForm=2;$pk=$_GET['id'];
           }
           $formulario = new Formulario($nombreForm,$tipoForm,$pk);
       }else{
           throw new \Exception("No se ha definido el formulario a ejecutar", 100);
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

		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) and !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
		and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		    $this->dv->solicitudAjax=TRUE;
			return true;
		}else{

			return false;
        }
        exit;
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
    protected function get($param=""){
    	if(!empty($param)){
    		if(isset($this->get[$param]))
	            return $this->get[$param];
	        else
	            return false;
    	}else return $_GET;

    }
    /**
     * Retorna el valor post solicitado, false si el valor no es conseguido
     * @method post
     * @param string $param Dato a solicitar
     * @return mixed 1. si solo es pasado $param se devolverá el valor $_POST correspondiente,
     * 2. Si es pasado $nuevoValor será retornado el objeto
     *
     */
    protected function post($param="",$nuevoValor=""){

        if(empty($param)){
            return $_POST;
        }elseif($nuevoValor!==""){

             $this->post[$param]=$nuevoValor;
             return $this;
        }else
        if(isset($this->post[$param]) or array_key_exists($param, $_POST)){
            return $this->post[$param];
        }

        return false;

    }
	/**
	 * Retorna el valor request solicitado
	 * @method request
	 * @param string $param Nombre del key a buscar o agregar
	 * @param string $nuevoValor [opcional] Valor a agregar a param
	 */
	protected function request($param="",$nuevoValor=""){
		if(empty($param)){
			return $_REQUEST;
		}elseif($nuevoValor!=""){
			$this->request[$param] = $nuevoValor;
		}elseif(isset($this->request[$param]) or array_key_exists($param, $this->request)){
			return $this->request[$param];
		}
		return false;
	}

    /**
     * Devuelve la URL correspondiente al metodo que hace la llamada
     *
     * @method urlActual
     */
    protected function urlActual($valor=1){
        $quienLlama = debug_backtrace(null,$valor+1)[$valor]['function'];
        return $this->_urlBase . $this->urlController() . $this->convertirNombreAUrl($quienLlama) ."/";
    }

    /**
     * Convierte el nombre pasado en la estructura estandard de urls
     *
     * @internal La estructura consiste en todo en minusculas y separado por guiones
     * @method convertirNombreAUrl
     * @param string $nombre Nombre a convertir (metodo o controlador);
     * @return string $url
     */
    static function convertirNombreAUrl($nombre){
        $coincidencias = preg_split('#([A-Z][^A-Z]*)#', $nombre, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        return strtolower(implode("-",$coincidencias));
    }
    /**
     * Retorna la url del controlador actual
     * @method urlController
     */
    protected function urlController($ctrl=""){

		$this->url="/";
        if(empty($ctrl)){
           	$controller =  $this->_nombreController;
        }else{

            if(class_exists(Cadenas::upperCamelCase($ctrl)."Controller")){
            	$controller = str_replace('Controller', '', $ctrl);
            }elseif( class_exists(Cadenas::upperCamelCase($ctrl)) ){

				$controller = (explode("\\", $ctrl));
				$controller = end($controller);
				$controller = str_replace('Controller', '', $controller);

            }else
                throw new \Exception("La url no puede ser armada correctamente, el objeto <strong>$ctrl</strong> no existe", 1);
        }




        if(!empty($controller)){

		    if(strtolower($this->_modulo)==strtolower($controller)){
            	if($this->dv->_esJadmin) $this->url.="jadmin/";
                $this->url .= strtolower($this->_modulo)."/";

		    }else{

                if(empty($this->_modulo)){
                	if(strtolower($controller)=='index'){

						$this->url =$this->obtURLApp();
						if($this->dv->_esJadmin) $this->url.="jadmin/";
            
					}else{

                  	  	$this->url = $this->obtURLApp();
                  	  	if($this->dv->_esJadmin) $this->url.="jadmin/";
                        
						$this->url .= $this->convertirNombreAUrl($controller)."/";
					}
                }else{

                    $this->url = $this->obtURLApp().strtolower($this->_modulo)."/".$this->convertirNombreAUrl($controller)."/";
                }

            }

        }

        return $this->url;
    }
    protected function urlModulo(){
          if(!empty($this->_modulo))  return $this->obtURLApp().strtolower($this->_modulo)."/";
          else return false;

    }
    /**
     * Devuelve la estructura de la url solicitada
     * @method getUrl
     * @param mixed $metodo Nombre del metodo del cual se quiere obtener la url, si no es pasado se devolvera la url actual
     * @param string $controlador Nombre del controlador [aun no funcional]
     * @return string $url
     */
    protected function getUrl($metodo="",$data=array()){
        if(!empty($metodo)){

            $url = explode(".", $metodo);

            if(count($url)==2){

				if(strpos($url[0],"/")){

					$urlExplode = explode('/', $url[0]);
					foreach ($urlExplode as $key => $value)
						$urlExplode[$key] = Cadenas::upperCamelCase($value);

					$ctrl = implode("\\", $urlExplode).'Controller';
				}else
					$ctrl = preg_replace("/[a-zA-Z]+Controller$/", Cadenas::upperCamelCase($url[0]).'Controller', $this->_clase);


                $urlController = $this->urlController($ctrl);
                $metodo=$url[1];

			}else{
                $ctrl = $this->_clase;
                $urlController = $this->urlController();
            }

            if(method_exists($ctrl,$metodo)){
                if($metodo=='index')$metodo="";
                $params= "";
                if(count($data)>0){
                    foreach ($data as $key => $value)
                        $params.="$key/$value/";
                }

                // Helpers\Debug::string($urlController.$this->convertirNombreAUrl($metodo)."/".$params,true);
                return $urlController.$this->convertirNombreAUrl($metodo)."/".$params;
            }else{

                throw new \Exception("El metodo < $metodo > pasado para estructurar la url no existe", 301);
            }

        }else{
            return $this->urlActual(2);
        }

    }

   /**
     * Devuelve la estructura de la url solicitada
     * @method obtUrl
     * @param mixed $metodo Nombre del metodo del cual se quiere obtener la url, si no es pasado se devolvera la url actual
     * @param string $data parametros pasados a la funcion
     * @return string $url
     */
    protected function obtUrl($metodo="",$data=[]){

// Helpers\Debug::imprimir('obtUrl',$metodo,$data);
		
        if(!empty($metodo)){

        	$url = explode(".", $metodo);

            if(count($url)==2){

			   if(strpos($url[0],"/")){
					$urlExplode = explode('/', $url[0]);
					foreach ($urlExplode as $key => $value)
						$urlExplode[$key] = Cadenas::upperCamelCase($value);

					$ctrl = implode("\\", $urlExplode).'Controller';
				}else
					$ctrl = preg_replace("/[a-zA-Z]+Controller$/", Cadenas::upperCamelCase($url[0]).'Controller', $this->_clase);
				
                $urlController = $this->urlController($ctrl);
                $metodo=$url[1];
				
				$urlController = (strpos(strtolower($this->urlController($ctrl)), 'jadmin'))?'':'/jadmin';
                $urlController .= $this->urlController($ctrl);
				
            }else{

                $urlController = $this->urlController();

                if(strpos(strtolower($this->_clase), 'jadmin')){
                    
                    if(URL_APP != '/'){
                        $parametros = explode(URL_APP, $urlController);
                        $parametros = $parametros[1];
                        
                        $urlController = URL_APP;
                        $urlController .= (strpos(strtolower($this->urlController()), 'jadmin'))?'':'jadmin/';
                        $urlController .= $parametros;
                        
                    }else{
                        $urlController = (strpos(strtolower($this->urlController()), 'jadmin'))?'':'/jadmin';
                        $urlController .= $this->urlController();
                    }
                }
                
                $ctrl = $this->_clase;
                
            }

            if(method_exists($ctrl,$metodo)){
                if($metodo=='index')$metodo="";
                $params= "";
                if(count($data)>0){
                    foreach ($data as $key => $value){
						if(is_array($value)) Helpers\Debug::mostrarArray(debug_backtrace());
                        $params.="$value/";
					}
                }
                
				$slash = ($metodo=='')?'':'/';
                $urlCompleta = (empty($params))? $urlController.$this->convertirNombreAUrl($metodo) : $urlController.$this->convertirNombreAUrl($metodo).$slash.$params;

                return $this->_urlBase . $urlCompleta;
                
            }else{

                throw new \Exception("El metodo < $metodo > pasado para estructurar la url no existe", 301);
            }

        }else{
            return $this->_urlBase  . $this->urlActual(2);
        }

    }

    /**
     * Retorna el nombre del modulo en el que se encuentra el objeto
     */
    function getModulo($obj){
        if(is_object($obj)){
            return $this->_modulo;
        }else{
            return $this->_404();
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
		if( !is_object($this->modelo)){
			if(!empty($this->modelo)){

	            if(class_exists($this->modelo)){
	                $this->modelo = new $this->modelo;
	            }else{

	                throw new \Exception("El objeto $this->modelo especificado como modelo no existe", 1);

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
                    throw new \Exception("El valor pasado para eliminar el objeto no es valido", 602);
            }
            return ($this->modelo->eliminar($id));

    }
    /**
     * Genera una excepción 404.
     */
    protected function _404(){
        throw new \Exception("No se consigue el enlace solicitado", 404);

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
        exit(json_encode($respuesta));
        exit;
    }
    /**
     * Realizar una redireccion
     * @method redireccionar
     */
    protected function redireccionar($url){
        header('location:'.$url.'');exit;
    }
	/**
	 * Retorna la url de la aplicacion actual
	 * @method obtURLApp
	 *
	 */
	protected function obtURLApp(){
		$idioma="";
		if($this->multiidioma)
			$idioma=(empty($this->idioma))?"":$this->idioma."/";

		if(strtolower($_SERVER['SERVER_NAME'])=='localhost'){
			return $GLOBALS['__URL_APP'].$idioma;
		}else{
			return URL_APP.$idioma;
		}
	}

		/**
	 * Retorna un arreglo a partir de un archivo JSON
	 *
	 * @method obtJSON
	 * @param directory $path Directorio del archivo
	 * @return array
	 * @since 1.4
	 */
	protected function obtJson($path){
		return json_decode(file_get_contents($path));

	}

	/**
	 * Define el layout a utilizar
	 * @method layout
	 * @since 1.4
	 */
	protected function layout($layout){
		if(!strpos($layout, ".tpl.php")) $layout .=".tpl.php";
		$this->layout = $layout;
	}
	/**
	 * Asigna los parametros pasados para que puedan ser accedidos desde la vista
	 * @method data
	 * @param mixed $data Arreglo de variables a pasar a la vista o nombre de variable a pasar
	 * @param int $valor [opcional] Si $data es un string $valor sera asignado como valor de la variable $data
	 * @since 1.4
	 *
	 */
	protected function data($data,$valor=""){
	    // Helpers\Debug::imprimir('Controleeer');
        // $this->establecerAtributos($data);
		if(is_array($data)){
			foreach ($data as $key => $value) {
				$this->dv->{$key} = $value;
			}
		}else $this->dv->{$data} = $valor;
	}
	







}