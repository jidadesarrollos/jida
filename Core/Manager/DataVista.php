<?php /**
 * Objeto Data para pasar información a Vistas y Layouts
 *
 * Objeto pasado por medio del jidaController del Controlador ejecutado
 * a la clase Pagina para que pueda ser accedido en conjunto con los valores
 * pasados desde la vista
 *
 * @author Julio Rodriguez
 * @package Framework
 * @subpackage core
 * @version 0.1
 * @category View
 */

namespace Jida\Core\Manager;
use Jida\Helpers as Helpers;
use Exception;
class DataVista {

	use \Jida\Core\ObjetoManager;

	/**
	 * @var array $css Arreglo Global de archivos javascript a usar por la vista
	 */
	var $js;
	/**
	 * @var array $css Arreglo Global con css a usar por la vista
	 */
	var $css;
	/**
	 * @var array $jsAjax Arreglo que registra los js a incluir en llamadas ajax
	 */
	var $jsAjax = [];
	/**
	 * Titulo de la Página HTML
	 *
	 * Representa la etiqueta TITLE en el HEAD
	 * @var string $title
	 */
	var $title;
	/**
	 * @var string $meta_descripcion Representa la etiqueta meta property description de la página
	 */
	var $meta_descripcion;
	var $meta_autor;
	var $meta_image;
	var $meta_url;
	var $meta = array();
	var $url_canonical;
	var $responsive = TRUE;
	var $robots = TRUE;
	var $solicitudAjax = FALSE;
	var $google_verification = FALSE;
	var $metodo;
	var $modulo;
	var $controlador;
	var $idioma;

	var $jsAgregado = [];
	var $cssAgregado = [];
	var $_esJadmin = false;
	var $_esApp = FALSE;
	var $_urlCssBase;
	var $_urlJsBase;
	/**
	 * Define una ruta absoluta para el template de la vista a usar, si no se encuentra
	 * definida sera usada como vista la vista correspondiente al metodo por defecto o la definida
	 * en la propiedad "vista del" controlador
	 */
	private $_plantilla = "";
	private $_path = "app";

	function __construct($modulo = "", $controlador = "", $metodo = "", $jadmin = false, $app = FALSE) {

		$this -> modulo = $modulo;
		$this -> controlador = $controlador;
		$this -> metodo = $metodo;
		$this -> _esJadmin = $jadmin;
		$this -> _esApp = $app;
		if (array_key_exists('_CSS', $GLOBALS))
			$this -> css = &$GLOBALS['_CSS'];
		if (array_key_exists('_JS', $GLOBALS))
			$this -> js = &$GLOBALS['_JS'];
		if (array_key_exists('_JS_AJAX', $GLOBALS))
			$this -> jsAjax = &$GLOBALS['_JS_AJAX'];
		$this -> setMetaBasico();
		$this -> _inicializar();
	}

	private function _inicializar() {

		if (defined('URL_CSS'))
			$this -> _urlCssBase = URL_CSS;
		if (defined('URL_JS'))
			$this -> _urlJsBase = URL_JS;

	}

	/**
	 * Agrega un javascript para ser renderizado en el layout
	 * @method incluirJS
	 * @param mixed $js Arreglo o string con ubicación del js
	 * @param boolean $ambito TRUE si se desea usar la constante URL_JS como path de ubicación
	 * @param boolean footer True Define si el js se imprimirá arriba o abajo
     *
	 */
	function incluirJS($js, $dir = TRUE, $contentJS = "", $footer = TRUE) {

        if ($dir === TRUE){
        	
        	$dir = URL_JS;
        }
            $dir = URL_JS;
        if (!is_array($js))
            $js = explode(",", $js);
        foreach ($js as $key => $archivo) {
        	$finalDir = (is_string($archivo) and strpos($archivo,'http')===FALSE)?$dir:"";
            if (!empty($ambito)) {
                if ($this -> _esJadmin) {
                    $this -> js[$ambito]['jadmin'][$key] = $finalDir . $archivo;
                } else {
                    $this -> js[$ambito][$key] = $finalDir . $archivo;
                }

            } else {
                if ($this -> _esJadmin) {
                    $this -> js['jadmin'][$key] = $finalDir . $archivo;
                } else
                    $this -> js[$key] = $finalDir . $archivo;
            }
			
            array_push($this -> jsAgregado, $finalDir . $archivo);
        }

        // Helpers\DEbug::imprimir($this->js,true);
        return $this;
    }

    /**
     * @param $js
     * @param bool $dir
     * @param string $contentJS
     * @param bool $footer
     * @return mixed
       @deprecated 0.6
     */
    function addJs($js,$dir=TRUE,$contentJS="",$footer=TRUE){
            
        return $this->addJs($js,$dir,$contentJS,$footer);
        
    }


	/**
	 * Permite agregar archivos JS pertenecientes a un modulo especifico
	 *
	 * Los archivos js seran buscados dentro de una carpeta htdocs/js del modulo sobre el cual
	 * se encuentre el sistema
	 * @method addJsModulo
	 * @param string js Nombre o ruta del archivo
	 * @param boolean $ruta
	 * @deprecated 0.6
	 */
	function addJsModulo($js, $modulo = "") {

		$modulo = (!empty($modulo)) ? $modulo : $this -> modulo;

		if ($modulo !== TRUE) {
			if ($this -> _esJadmin and !$this -> _esApp) {
				$ruta = '/Framework/Jadmin/Modulos/' . ucwords(strtolower($modulo)) . '/htdocs/js/';
			} else {
				$ruta = '/Aplicacion/Modulos/' . ucwords(strtolower($modulo)) . '/htdocs/js/';
			}
		}

		if (!is_array($js)) $js = explode(' ', $js);

		if (is_array($js)) {
			foreach ($js as $key => $archivo) {
				if (array_key_exists(strtolower($this -> modulo), $this -> js)) {
					$this -> js[strtolower($this -> modulo)][] = $ruta . $archivo;
				} else {
					if ($this -> _esJadmin and array_key_exists('jadmin', $this -> js)) {
						$this -> js['jadmin'][] = $ruta . $archivo;
					}

					$this -> js[] = $ruta . $archivo;
				}
			}
		}
		
		// Helpers\Debug::imprimir($this->js,$js,true);
	}

	/**
	 * Permite agregar archivos css pertenecientes a un modulo especifico
	 *
	 * Los archivos css seran buscados dentro de una carpeta htdocs/css del modulo sobre el cual
	 * se encuentre el sistema
	 * @method addcssModulo
	 */
	function addCssModulo($css, $ruta = true) {
		$modulo = $this -> modulo;
		(Helpers\Cadenas::guionCase($modulo) == 'jadmin') ? $modulo = "Framework" : $modulo = "Aplicacion/Modulos/" . $modulo;

		if ($ruta === TRUE) {
			if ($this -> _esJadmin and !$this -> _esApp) {
				$ruta = '/Framework/Jadmin/Modulos/' . ucwords(strtolower($modulo)) . '/htdocs/css/';
			} else {
				$ruta = '/Aplicacion/Modulos/' . ucwords(strtolower($modulo)) . '/htdocs/css/';
			}
		}
		if (!is_array($css)) { $js = explode(' ', $css);
		}

		if (is_array($css)) {

			foreach ($css as $key => $archivo) {

				if (array_key_exists(strtolower($this -> modulo), $this -> css)) {
					$this -> css[strtolower($this -> modulo)][] = $ruta . $archivo;
				} else {
					if ($this -> _esJadmin and array_key_exists('jadmin', $this -> css)) {
						$this -> css['jadmin'][] = $ruta . $archivo;
					}
					$this -> css[] = $ruta . $archivo;
				}
			}

		}

	}

	/**
	 * Agrega un javascript para ser renderizado en el layout
	 * @method addjs
	 * @param mixed $js Arreglo o string con ubicación del js
	 * @param boolean $ambito TRUE si se desea usar la constante URL_JS como path de ubicación
	 * @param string ambito Usado para agregar el js solo para prod o dev
	 */
	function addJsAjax($js, $dir = TRUE, $ambito = "") {

		if ($dir === TRUE)
			$dir = $this -> _urlJsBase;

		if (is_array($js)) {
			foreach ($js as $key => $archivo) {
				if (!empty($ambito))
					$this -> jsAjax[$ambito][] = $dir . $archivo;
				else
					$this -> jsAjax[] = $dir . $archivo;
			}
		} else {
			if (!empty($ambito))
				$this -> js[$ambito][] = $dir . $js;
			else
				$this -> jsAjax[] = $dir . $js;
		}
	}

	/**
	 * Agrega un css a la hoja de estilo global
	 * @method addCss
	 * @param mixed $css Arreglo o string con ubicación del css
	 * @param boolean $ambito TRUE si se desea usar la constante URL_CSS como ubicacion
	 * @param string $ambito Usado para agregar css solo para prod o dev
	 */
	function addCSS($css, $url = "") {

		$constante = (empty($url)) ? $this -> _urlCssBase : $url;
		if (is_array($css)) {
			$this -> cssAgregado = array_merge($this -> cssAgregado, $css);
			foreach ($css as $key => $value) {
				if (!empty($ambito))
					$this -> css[$ambito][] = $constante . $value;
				else
					$this -> css[] = $constante . $value;
			}
		} else {
			array_push($this -> cssAgregado, $css);
			if (!empty($ambito))
				$this -> css[$ambito][] = $constante . $css;
			else {
				$this -> css[] = $constante . $css;

			}
		}
		return $this;
	}

	/**
	 * Remueve un archivo CCS
	 *
	 * Elimina un archivo css de la lista de archivos registrada para inserción en el view.
	 *
	 *
	 * @method removerCss
	 * @param string $css Nombre del Archivo CSS a eliminar
	 */
	function removerCSS($archivo, $key = null) {
		$arrayCss = &$this -> css;
		if (!is_null($key) and array_key_exists($key, $this -> css))
			$arrayCss = &$this -> css[$key];
		if (in_array($archivo, $arrayCss)) {
			$key = array_search($archivo, $arrayCss);
			unset($arrayCss[$key]);
			return true;
		}

		return false;
	}

	function removerJs() {

	}

	/**
	 * Permite definir una vista para usar fuera del ambito del controlador
	 *
	 * Este metodo está disponible para vistas estandard que puedan tener un mismo comportamiento en diversos
	 * controladores
	 * @method setVista
	 * @param string $nombreVista Vista a utilizar
	 * @param string $path a utilizar opciones disponibles 'app' 'jida' cualquier valor distinto será tomado como app
	 * @return void
	 */
	function usarPlantilla($nombreVista, $path = "") {
		if ($path == 'jida')
			$this -> _path = "jida";
		$this -> _plantilla = $nombreVista;
	}

	/**
	 * @deprecated
	 */
	function setVistaAsTemplate($nombreVista, $path = "") {
		$this -> usarPlantilla($nombreVista, $path);

	}

	function obtPlantilla() {

		if (!isset($this -> _plantilla))
			$this -> _plantilla = "";
		return $this -> _plantilla;

	}

	function getPath() {
		return $this -> _path;
	}

	/**
	 * Agrega codigo Js al final de la vista, luego de incluir
	 * @method addCodeJs
	 * @param mixed $arg1 Variable con codigo o Nombre del archivo, si es un archivo debe encontrarse en la misma carpeta
	 * de las vistas
	 * @param boolean $file Determina si lo pasado es una variable o una url de archivo.
	 *
	 */
	function addCodeJs($arg1, $file = false) {
		if ($file == TRUE)
			$this -> js['code'][] = ['archivo' => $arg1];
		else {
			$this -> js['code'][] = ['codigo' => $arg1];
		}
	}

	/**
	 * Permite editar las multiples etiquetas metas de una pagina
	 * @method editarMeta
	 * @param array Arreglo de etiquetas meta, los keys deben coincidir con las meta definidas
	 */
	function editarMeta($array) {
		$this -> establecerAtributos($array, __CLASS__);
	}
	
	private function setMetaBasico() {
		$html = "";
		if (empty($this -> meta_descripcion)) {
			if (defined('META_DESCRIPCION'))
				$this -> meta_descripcion = META_DESCRIPCION;
		}
		if (empty($this -> autor)) {
			if (defined('APP_AUTOR'))
				$this -> meta_autor = APP_AUTOR;
		}
		if (empty($this -> image)) {
			if (defined('APP_IMAGEN'))
				$this -> image = APP_IMAGEN;
		}
	}

	function addMeta($meta) {
		$this -> meta[] = $meta;
	}

	/**
	 * Establece los atributos de una clase.
	 *
	 * Valida si los valores pasados en el arreglo corresponden a los atributos de la clase en uso
	 * y asigna el valor correspondiente
	 *
	 * @access protected
	 * @param array @arr Arreglo con valores
	 * @param instance @clase Instancia de la clase
	 */
	protected function establecerAtributos($arr, $clase = "") {

		if (empty($clase)) {
			$clase = $this -> _clase;
		}

		$this -> establecerAtributos($arr, $this -> _clase);

		// $metodos = get_class_vars($clase);
		// foreach($metodos as $k => $valor) {
		//
		// if (isset($arr[$k])) {
		// $this->$k = $arr[$k];
		// }
		// }

	}

	function addJsCodigo($codigo) {
		if (array_key_exists('codigo', $this -> js)) {
			$this -> js['codigo'] .= $codigo;
		} else {
			$this -> js['codigo'] = $codigo;
		}

	}

}
