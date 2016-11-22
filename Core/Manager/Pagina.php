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

namespace Jida\Core\Manager;
use Jida\Helpers as Helpers;
use Jida\Render\Selector as Selector;
use Jida\Core\Manager\JExcepcion as JExcepcion;
use Jida\Helpers\Directorios as Directorios;
use Exception as Excepcion;
class Pagina{

    use \Jida\Core\ObjetoManager;

    /**
     * Información pasada al layout y vista a renderizar
     * @param mixed $data
     */
    var $data;
	/**
	 * Define el layout por defecto de la aplicación
	 *
	 * Por defecto siempre buscara el valor "default.tpl.php" Este valor puede ser modificado
	 * por medio de la constante LAYOUT_DEFAULT
	 * @since 1.4
	 * @see LAYOUT_DEFAULT
	 * @var $layoutDefault
	 */
	var $layoutDefault='default.tpl.php';
	/**
	 * Define el tema utilizado en la aplicación
	 * @var url temaApp
	 * @since 1.4
	 */
	var $temaApp;
	var $idioma;


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
	 * Directorio fisico de la vista a incluir
	 * @var $directorioVista
	 */
    private $directorioVista;
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

	private $directorioPlantillas='Framework/plantillas/';
	/**
	 * URL Actual
	 */
    private $url;
	private $_ruta;
	/**
	 * Define si la vista pertenece a un controlador de un modulo del Jadmin
	 * @var $_esJadmin;
	 */
	private $_esJadmin=false;
    function __construct($controlador,$metodo="",$modulo="",$ruta="app",$jadmin=false){

       $this->validarDefiniciones($controlador,$metodo,$modulo);
	   $this->validarEstructuraApp();
	   $this->_esJadmin = $jadmin;
	   $this->_ruta=$ruta;

    }

	/**
	 * Verifica la estructura general de la aplicación
	 * @method validarEstructuraApp
	 * @since 1.4
	 */
	function validarEstructuraApp(){
		//la data cargada aquí, deberá poder ser obtenida de base de datos o un archivo.
		$data=[];
		if(array_key_exists('configuracion', $GLOBALS))
			$data=$GLOBALS['configuracion'];
		if(array_key_exists('tema', $data))
		{
			$this->temaApp = $data['tema'];
		}
		if(defined('LAYOUT_DEFAULT')) $this->layoutDefault=LAYOUT_DEFAULT;
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


        if(!empty($controlador))    $this->controlador = $controlador;
        if(!empty($metodo)) $this->nombreVista=$metodo;
        if(!empty($modulo)) $this->modulo = $modulo;


        if(defined('DIR_FRAMEWORK')){
            $this->rutaFramework=DIR_FRAMEWORK."Jadmin/Vistas/";
        }else{
            throw new Excepcion("No se encuentra definida la ruta de las vistas del admin jida. verifique las configuraciones", 1);
        }
        #Ruta para vistas de la aplicacion
        if(!empty($modulo)){
            $this->rutaApp=DIR_APP ."Modulos/".ucwords($modulo)."/Vistas/";
        }
        else{
            $this->rutaApp=DIR_APP ."Vistas" . "/" ;
        }



        $this->url = (Helpers\Sesion::get('URL_ACTUAL')[0]!="/")?"/".Helpers\Sesion::get('URL_ACTUAL'):Helpers\Sesion::get('URL_ACTUAL');


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

        if(!$this->_esJadmin){

            $this->urlPlantilla=DIR_PLANTILLAS_APP;
            $this->directorioLayout=DIR_LAYOUT_APP;
            if(!empty($this->temaApp))
            {
            	if(!Directorios::validar(DIR_LAYOUT_APP.$this->temaApp))
					throw new Excepcion("No se consigue el tema definido", 1);


				$this->directorioLayout.=$this->temaApp."/";
			//echo $this->directorioLayout.$this->obtNombreTpl($this->layout);Exit;
				if(empty($this->layout) or !Directorios::validar($this->directorioLayout.$this->obtNombreTpl($this->layout))){

					if(Directorios::validar($this->directorioLayout.$this->obtNombreTpl($this->controlador))){
						$this->layout=$this->obtNombreTpl($this->controlador);
					}else
					if(!empty($this->modulo) and Directorios::validar($this->directorioLayout.$this->obtNombreTpl($this->modulo))){

						$this->layout=$this->obtNombreTpl($this->modulo);
					}elseif(Directorios::validar($this->directorioLayout.$this->obtNombreTpl($this->temaApp))){
						$this->layout=$this->obtNombreTpl($this->temaApp);
					}else{

						$this->layout=$this->obtNombreTpl($this->layoutDefault);
					}

				}
            }
        }else{

			if(array_key_exists('configuracion', $GLOBALS) and array_key_exists('temaJadmin', $GLOBALS['configuracion']))
			{
				echo "el tema es ". $GLOBALS['configuracion']['temaJadmin'];
			}else{

				$this->directorioLayout = DIR_LAYOUT_JIDA;
				$this->urlPlantilla=DIR_PLANTILLAS_FRAMEWORK;
			}

        }

    }
   /**
    * Retorna un valor con estructura de nombre de plantilla
    * @method obtNombreTpl
    * @param string tpl
    * @return string tpl recibe "nombreplantilla" y retorna "nombreplantilla.tpl.php";
    */
	private function obtNombreTpl($tpl){
		if(strpos($tpl, '.tpl.php')===false) return Helpers\Cadenas::lowerCamelCase($tpl.'.tpl.php');

		return Helpers\Cadenas::lowerCamelCase($tpl);

	}
	/**
	 * Verifica el string del layout y estructura el nombre de forma correcta
	 *
	 * Si el layout no tiene la extension ".tpl.php" se la agrega
	 * @method procesarLayout
	 * @since 1.4
	 */
	private function procesarLayout(){
		if(strpos($this->layout,'.tpl.php')===FALSE)
		{
			$this->layout.='.tpl.php';
		}
	}

	/**
	 * Define el directorio donde debe ser buscada la vista
	 * @method obtenerDirectorioVista
	 */
	private function obtenerDirectorioVista(){

		if($this->_ruta=='framework'){
			$this->directorioVista = DIR_FRAMEWORK."Jadmin/";

			if(!empty($this->modulo)){
				$this->directorioVista.='Modulos/' . $this->modulo . "/Vistas/";
			}else{
				$this->directorioVista.='Vistas/';
			}
		}else{
			$this->directorioVista = DIR_APP;
			$vistaFolder  = ($this->_esJadmin)?"/Jadmin/Vistas/":'/Vistas/';

			if(!empty($this->modulo)){
				$this->directorioVista.= 'Modulos/' . $this->modulo . $vistaFolder;
			}else{
				$this->directorioVista.=$vistaFolder;
			}
		}
		$controller = Helpers\Cadenas::lowerCamelCase(str_replace('Controller', '', $this->controlador));
		$this->directorioVista  .= $controller."/";
		// Helpers\Debug::imprimir($this->_ruta,$this->directorioVista,true);
		return $this->directorioVista;
	}
    /**
     * Muestra la vista del metodo solicitado
     * @method renderizar
     * @access public
     * @param string nombreVista Nombre del archivo vista a mostrar, por defecto
     * se busca un archivo con el mismo nombre del metodo del controlador requerido.
     *
     */

    function renderizar($nombreVista="",$excepcion=FALSE){
		$this->procesarVariables();
        if(!empty($nombreVista)) $this->nombreVista = $nombreVista;

        $DataTpl = $this->data->getTemplate();
        /**
         * Se verifica si desea usarse una plantilla
         */
        if(!empty($DataTpl)){
            $rutaVista = $this->procesarVistaAbsoluta();
        }else{
            // Se accede a un archivo vista
            $rutaVista = $this->obtenerDirectorioVista();

            //Arma la estructura para una vista cualquiera
            if($excepcion)
				$rutaVista = $this->rutaExcepciones . $nombreVista .'.php';
			else
            $rutaVista = $rutaVista . Helpers\Cadenas::lowerCamelCase($this->nombreVista).".php";

        }

        if(!is_readable($rutaVista))
        	throw new Excepcion("No se consigue el archivo $rutaVista", 1);

        $this->template=$rutaVista;
		#Debug::mostrarArray($rutaVista);
        if((!empty($this->layout) and $this->layout!==FALSE) or $excepcion===TRUE)
            $this->renderizarLayout($excepcion);
        else
            throw new Excepcion("No se encuentra definida la plantilla", 120);

    }//final funcion

    /**
	 * Incluye una plantilla
	 * @inconclusa
	 */
    private function procesarVistaAbsoluta(){
        if($this->data->getPath()=="jida"){
            $this->urlPlantilla = DIR_PLANTILLAS_FRAMEWORK;

        }
        return $this->urlPlantilla.Helpers\Cadenas::lowerCamelCase($this->data->getTemplate()).".php";
    }

    /**
     * Renderiza una vista en un layout definido
     * @method renderizarLayout
     * @access private
     */
    private function renderizarLayout($excepcion=FALSE){
	// echo include_once $this->template;
	// Helpers\Debug::imprimir($this->layout,$this->template,true);
        /* Permitimos almacenamiento en bufer */
        // if(ob_get_contents())
        	// ob_clean();
        ob_start();

        $this->layout = $this->directorioLayout.$this->layout;

        if(empty($this->layout) and !$excepcion){

                throw new Excepcion("No se encuentra definido el layout para $this->template, controlador $this->controlador", 110);
        }else
        if(!file_exists($this->layout) and !$excepcion){

            throw new Excepcion('No existe el layout '.$this->layout, 1);

            //Debug::string('No existe el layout '.$this->layout,true);
        }else{
        	$contenido="";
			if(isset($this->template)){
				include_once $this->template;
           		$contenido = ob_get_clean();
		   	}
           	include_once $this->layout;
           	$layout = ob_get_clean();
           	echo $layout;
        }
        //if (ob_get_length()) ob_end_clean();



    }


    private function requiresJs(){

    }

    /**
	 * Procesa la excepción generada
	 * @method procesarExcepcion
	 */
    function procesarExcepcion(JExcepcion $e,$ctrlExcepcion)
    {
    	$this->layout = LAYOUT_DEFAULT;

		if(class_exists($ctrlExcepcion)){

    		$ctrl  =new $ctrlExcepcion($e);
			if(method_exists($ctrlExcepcion, 'layout'))
				$this->layout = $ctrl->obtLayout();
    	}
    	$codigo = $e->codigo();

		$this->excepcion = $e;
		$path= $this->directorioPlantillas.'error/';

		$tpl = 'error';
		$this->directorioLayout='Framework/Layout/';
		if(Directorios::validar(DIR_APP.'plantillas/error/')){
			$path =DIR_APP . 'plantillas/error/';

			if(Directorios::validar($path.$codigo.".php")){

				$tpl = $codigo;
			}elseif(Directorios::validar($path.'error.php')){

			     $tpl = 'error';
            }else{

                $path = DIR_FRAMEWORK . 'plantillas/error/';

            }

		}else{

			$this->directorioLayout='Framework/Layout/';

		}

		$this->rutaExcepciones = $path;
		$this->renderizar($tpl,TRUE);




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

    private function imprimirArrayJs($keyArrayPadre,$archivos,$pos,&$cont,$tipo="script"){

        $js="";

        if(is_array($archivos) and ($keyArrayPadre==ENTORNO_APP or $keyArrayPadre==$pos)){

            $inclusiones = Arrays::obtenerKey($pos, $archivos);

            foreach ($inclusiones as $key => $value) {
                if(!is_string($key) and !empty($post)){
                    $js.=Selector::crear('script',['src'=>$value],null,$cont);
                }else{
                    $this->imprimirArrayJs($key, $value, $pos, $cont);
                }
            }
        }else{
            switch ($keyArrayPadre) {
                case 'codigo':
                    $js.=$this->imprimirCodigoJs($archivos,$cont);
                    break;

                default:
                    if($keyArrayPadre==$pos) $js.=Selector::crear('script',['src'=>$value],null,$cont);
                    break;
            }
        }

        return $js;



    }
    /**
     * Imprime los bloques JAVASCRIPT pasados del controlador
     *
     * Permite imprimir las llamadas a archivos javascript o de segmentos de códigos creados desde el
     * controlador
     * @method printJS
     * @param string $pos Head o footer
     *
     */
    function printJS($pos=''){
        $js="";
        $this->checkData();
        $cont=0;
        $code= array();


		if(is_array($this->data->js)){
			$data=[];
            if(!empty($pos)){
                if(array_key_exists($pos, $this->data->js)) $data = $this->data->js[$pos];
                if(array_key_exists(ENTORNO_APP, $this->data->js) and array_key_exists($pos, $this->data->js[ENTORNO_APP]))
                    $data = array_merge($data,$this->data->js[ENTORNO_APP][$pos]);

                foreach ($data as $id => $archivo) {
                    $js.=Selector::crear('script',['src'=>$archivo],null,$cont);
                    if($cont==0)$cont=2;
                }

            }else{
                if(array_key_exists('footer', $this->data->js)){
                  $this->data->js = array_merge($this->data->js,$this->data->js['footer']);
                  unset($this->data->js['footer']);
                }
                if(array_key_exists('head', $this->data->js)){
                     $this->data->js = array_merge($this->data->js,$this->data->js['head']);
                    unset($this->data->js['head']);
                }

                foreach ($this->data->js as $key => $archivo) {
                    if(is_string($key)){
                        if($key==ENTORNO_APP){
                            foreach ($archivo as $id => $archivoEntorno) {
                                #Debug::mostrarArray($archivoEntorno,0);

                                if(is_string($archivoEntorno))
                                {
                                    $js.=Selector::crear('script',['src'=>$archivoEntorno],null,$cont);
                                    if($cont==0)$cont=2;
                                }elseif(is_string($id)){
                                    foreach ($archivoEntorno as $key => $archivoSeccion) {
                                        $js.=Selector::crear('script',['src'=>$archivoSeccion],null,$cont);
                                        if($cont==0)$cont=2;
                                    }
                                }

                            }
                        }

                    }else{

                       $js.=Selector::crear('script',['src'=>$archivo],null,$cont);
                    }
                    if($cont==0)$cont=2;
                }
            }
         }
			// foreach ($this->data->js as $key => $archivo) {
//
	            // if(is_string($key)){
//
	                // $js.=$this->imprimirArrayJs($key,$archivo,$pos,$cont);
	            // }else{
	                // if(empty($pos))
	                   // $js.=Selector::crear('script',['src'=>$archivo],null,$cont);
	            // }
//
	            // if(is_string($key)){
	                // if($key==ENTORNO_APP){
//
	                    // foreach ($archivo as $key => $value){
	                        // $js.=Selector::crear('script',['src'=>$value],null,$cont);
	                        // if($cont==0) $cont=2;
	                    // }
	                // }elseif($key=='codigo'){
//
	                  // $js.=$this->imprimirCodigoJs($archivo,$cont);
	                // }
	            // }
	            // else $js.=Selector::crear('script',['src'=>$archivo],null,$cont);
	          //  if($cont==0) $cont=2;
	      //  }


		//}

        return $js;
    }

    private function imprimirCodigoJs($codigo,$cont){
        $js="";

        if(is_array($codigo)){

        }else{
            $js=Selector::crear('script',null,$codigo,$cont);
        }
        return $js;
    }
	/**
	 * Imprime los archivos js incluidos en e
	 *
	 * @method printJSAjax
	 */
    function printJSAjax(){
        $js="";
        $this->checkData();
        $cont=0;
        $code= array();

        if(is_array($this->data->jsAjax)){
            if(array_key_exists('code',$this->data->jsAjax)){
                $code = $this->data->jsAjax['code'];
                unset($this->data->jsAjax['code']);
            }
            foreach ($this->data->jsAjax as $key => $archivo) {

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

	/**
	 * Imprime el css correspondiente a un modulo especifico
	 * @method printCssModulo
	 * @param string $modulo
	 * @since 1.4
	 *
	 */
	function printCssModulo($modulo){

	}

	/**
	 * Imprime las lirerias del lado cliente
	 *
	 *
	 * @since 1.4
	 * @param string $lang Tipo de libreria a imprimir [js o css]
	 * @param string $modulo Si es pasado, la funcion buscara imprimir solo los valores del key correspondiente.
	 * @return string $libsHTML renderización HTML de los tags de inclusión de las librerias.
	 */
	function imprimirLibrerias($lang,$modulo=""){
		$dataInclude=[];

		if(!property_exists($this->data, $lang)) return false;
		$data = $this->data->{$lang};

		//Se eliminan las librerias incluidas en un entorno distinto al actual
		//o que pertenezcan a un $modulo no solicitado
		foreach ($data as $key => $value) {
			if(is_array($value) and $key!=ENTORNO_APP and $key!=$modulo)
				unset($data[$key]);
		}//fin forech

		if(array_key_exists(ENTORNO_APP, $data)){
			$dataInclude = $data[ENTORNO_APP];
			//Se eliminan
			foreach ($dataInclude as $key => $value) {
				if(is_array($value) and $key!=$modulo) unset($dataInclude[$key]);
			}
			unset($data[ENTORNO_APP]);

		}

		$librerias = array_merge($dataInclude,$data);
		if(!empty($modulo)){
			if(array_key_exists($modulo,$librerias))
			{
				$libreriasModulo = $librerias[$modulo];
				unset($librerias[$modulo]);
				$librerias = $libreriasModulo;

			}

		}

		$libsHTML = "";
		$cont=0;


		foreach($librerias as $id => $libreria){
			if(is_array($libreria) and $lang=='css'){
					//se pasa como lenguaje la variable $id ya que es un una etiqueta link la que se creara
					//a partir del arreglo $libreria
					$libsHTML.=$this->__obtHTMLLibreria('link',$libreria,$cont);

			}elseif(!is_array($libreria))
				$libsHTML.=$this->__obtHTMLLibreria($lang, $libreria,$cont);

			if($cont==0) $cont=2;

		}//fin foreach=======================================
		return $libsHTML;
	}
	private function __obtHTMLLibreria($lang,$libreria,$cont=2){
		switch ($lang) {
			case 'js':
				if(is_array($libreria)) Debug::mostrarArray($libreria,0);
				$html = Selector::crear('script',['src'=>$libreria],null,$cont);
				break;
			case 'link':

				$html = Selector::crear('link',$libreria,null,$cont);
				break;
			default:
				//css
				$html= Selector::crear('link',['href'=>$libreria,'rel'=>'stylesheet', 'type'=>'text/css'],null,2);
				break;
		}
		return $html;
	}
	/**
	 * Imprime las librerias css
	 *
	 */
    function printCSS(){
        $css = "";

        $this->checkData();
        $cont=0;
		if(is_array($this->data->css)){
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
		}else{

		}

        return $css;
    }
    private function checkData(){
        if(!$this->data instanceof DataVista){
            $this->data = new DataVista();
            Debug::string("No se ha instanciado correctamente el objeto Data en el controlador $this->controlador", true);
        }
    }

    private function printHTML($html){
        return htmlspecialchars_decode($html);
    }
    /**
     * Imprime la información meta HTML configurada para la página actual
     *
     * Si no se ha configurado nada, se intentaran imprimir los valores por defectos
     * que pueden estar configurados con las constantes APP_DESCRIPCION, APP_IMAGEN y APP_AUTOR
     *
     * @method printHeadTags
     *
     */
    function printHeadTags(){
        $meta="";$itemprop="";
        $initTab=0;
        //Titulo de La pagina
        if(count($this->data->meta)>0){
            $metaAdicional="";

            foreach ($this->data->meta as $key => $dataMeta) {

                $metaAdicional.=Selector::crear('meta',$dataMeta,null,2);
            }
            //$itemprop.=$metaAdicional;
            $meta.=$metaAdicional;
        }
		if($this->data->google_verification!=FALSE){
			$meta.=Selector::crear('meta',["name"=>"google-site-verification", "content"=>$this->data->google_verification]);
		}
		if($this->data->responsive){

			$meta.=Selector::crear('meta',["name"=>"viewport",'content'=>"width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"]);
		}
        if(!empty($this->data->title)){
            $meta.=Selector::crear('TITLE',null,$this->data->title,0);
            $initTab=2;
            $meta.=Selector::crear('meta',['name'=>'title','content'=>$this->data->title],null,$initTab);

        }
        if(!empty($this->data->meta_descripcion)){
            $meta.=Selector::crear('meta',['name'=>'description','content'=>$this->data->meta_descripcion],null,$initTab);
            $itemprop.=Selector::crear('meta',['itemprop'=>'description','content'=>$this->data->meta_descripcion],null,2);
        }
        if(!empty($this->data->meta_autor)){
            $meta.=Selector::crear('meta',['name'=>'author','content'=>$this->data->meta_autor],null,2);
            $itemprop.=Selector::crear('meta',['itemprop'=>'author','content'=>$this->data->meta_autor],null,2);
        }
        if(!empty($this->data->meta_image)){
            $meta.=Selector::crear('meta',['name'=>'image','content'=>$this->data->meta_image],null,2);
            $itemprop.=Selector::crear('meta',['itemprop'=>'image','content'=>$this->data->meta_image],null,2);
        }

        if(count($this->data->meta)>0){
            $metaAdicional="\t\t<!---Tags Meta-----!>\n";

            foreach ($this->data->meta as $key => $dataMeta) {

                $metaAdicional.=Selector::crear('meta',$dataMeta,null,2);
            }
            //$itemprop.=$metaAdicional;
        }
        if(!$this->data->robots){
            $itemprop.=Selector::crear('meta',['name'=>'robots','content'=>'noindex'],null,2);
        }
        //URL CANNONICA
        if(!empty($this->data->url_canonical)){
            $itemprop.=Selector::crear('link',['rel'=>'canonical','href'=>$this->data->url_canonical],null,2);
        }

        return $meta.$itemprop."\n";
    }
	/**
	 * Renderiza una URL
	 *
	 * En estos momentos el metodo solo verifica si se estan manejando multiples
	 * lenguajes y antepone el lenguaje actual a la url
	 * @version beta
	 *
	 */
	function renderURL($url,$lang=""){

		if(defined('USO_IDIOMAS') and USO_IDIOMAS){
			if(empty($lang) and !empty($this->idioma)) $lang = $this->idioma;
		}
		if(!empty($lang)) $lang='/'.$lang;

		return $lang.$url;
	}
	/**
	 * Retorna el layout a utilizar en la vista
	 *
	 * @param path $path Si es pasado el objeto buscará el layout en
	 * el directorio indicado
	 * @return path $path
	 * @deprecated No se encuentra en uso
	 */
	function pathLayout($path=""){
		if(!empty($path))	$this->urlPlantilla = $path;
		return $this->urlPlantilla;

	}

    /**
     * Permite incluir "segmento" de código en una vista
     *
     * @internal Los segmentos de código pueden ser declaradas como archivos
     * independientes. Son especialmente útiles cuando se requiere
     * reutilizar código del lado de las vistas.
     * El segmento será buscado por defecto en la carpeta "segmentos" en la raiz de Aplicacion
     * @method segmento
     * @param string $segmento Nombre del segmento, sin la extensión. (Debe ser pasado como primer parametro)
     * @param array $variables Matriz de variables a pasar al segmento
     */
    function segmento($segmento,$params=[]){

        if(!is_array($params)) $params = array($params);

        foreach ($params as $key => $p)
            $this->data->$key = $p;

        $directorio = DIR_APP.'Segmentos/';
        if(file_exists($directorio . $segmento . '.php')){
            echo  $this->incluir('Aplicacion/Segmentos/'.$segmento);
            // echo  $this->obtenerContenidos('Aplicacion/Segmentos/'.$segmento.'.php');
            // return true;
        }else{
            throw new Exception("No existe el segmento $segmento en la carpeta ".$directorio, 100);
        }
        return false;

    }

	private function obtenerContenidos($archivo){

			ob_start();
			include_once $archivo;

			$contenido = ob_get_clean();
			if (ob_get_length()) ob_end_clean();
			return $contenido;
	}
	/**
	 * Función para incluir archivos
	 * @param mixed $files Nombre de Archivo o arreglo de archivos a incluir
	 *
	 */
	function incluir($archivo)
	{
		if(is_array($archivo)){
			foreach ($archivo as $key => $ar) {
				include_once $ar.'.php';
			}
		}elseif(is_string($archivo)){
			include_once $archivo.'.php';
		}
	}

    /**
     * Función para incluir templates
     * @param mixed $archivo Nombre del archivo a incluir
     *
     */
    function incluirLayout($archivo){
        $tema = $GLOBALS['configuracion']['tema'];
        $directorio = 'Aplicacion/Layout/'.$tema.'/';
        $extension = '.php';

        if(is_array($archivo)){
            foreach ($archivo as $key => $ar) {
                if(file_exists($directorio . $ar . $extension)) include_once $directorio . $ar . $extension;
                else throw new Exception('No existe la plantilla'. $ar . $extension, 100);
            }
        }elseif(is_string($archivo)){
            if(file_exists($directorio . $archivo . $extension)) include_once $directorio.$archivo.$extension;
            else throw new Excepcion('No existe la plantilla '. $archivo . $extension, 100);

        }
    }

	/**
	 * Procesa todas las variables del dataVista para que sean accedidas desde la vista
	 *
	 * @internal Recorre las propiedades del objeto DataVista instanciado y las asigna
	 * en ejecucion al objeto.
	 * @method procesarVariables
	 */
	function procesarVariables(){
		$propiedades = get_object_vars($this->data);
		foreach ($propiedades as $k => $value) {
			//Helpers\Debug::imprimir("procesamos la propiedad ".$k);
			$this->$k = $this->data->$k;
		}
	}
	/**
	 * Permite acceder a un nexo
	 *
	 */
	function nexo(){

	}

}
