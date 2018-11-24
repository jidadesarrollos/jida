<?PHP

/**
 * Clase Vista
 *
 * Clase manejadora de vistas requeridas
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @version 1.0 - 28/12/2013
 * @package Framework
 * @category Router
 *
 */

namespace Jida\Core\Manager;

use App\Config\Configuracion;
use Jida\Medios as Medios;
use Jida\Manager\Estructura;
use Jida\Render\Selector as Selector;
use Jida\Core\Manager\JExcepcion as JExcepcion;
use Jida\Medios\Directorios as Directorios;
use Exception as Excepcion;

class Pagina {

    use \Jida\Core\ObjetoManager;

    private $_ce = '7000';

    /**
     * Objeto DataVista
     * @param object $data
     * @see \Jida\Core\DataVista
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
    var $layoutDefault = 'default.tpl.php';

    /**
     * Define el tema utilizado en la aplicación
     * @var $_tema
     * @since 1.4
     */
    var $_tema;
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
    private $urlPlantilla;

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
    private $rutaFramework = "";

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
    var $_modulo;
    var $_controlador;
    var $_namespace;
    var $template;

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
    private $rutaExcepciones = "";
    private $directorioPlantillas = 'Framework/plantillas/';

    /**
     * URL Actual
     */
    private $url;
    private $_ruta;

    /**
     * Define si la vista pertenece a un controlador de un modulo del Jadmin
     * @var $_esJadmin ;
     */
    private $_esJadmin = false;
    private $_conf;

    function __construct ($controlador, $metodo = "", $modulo = "", $ruta = "app", $jadmin = false) {

        $this->validarDefiniciones($controlador, $metodo, $modulo);
        $this->validarEstructuraApp();
        $this->_esJadmin = $jadmin;
        $this->_ruta = $ruta;

        $configuracion = Configuracion::obtener();

        if (is_object($configuracion)) {

            $this->_conf = $configuracion;

            if (!property_exists($this->_conf, 'tema')) {
                throw new Excepcion('No se ha definido el tema para la aplicacion', '100' . $this->_ce);
            }

            $this->_tema = $this->_conf->tema;
        }

    }

    /**
     * Verifica la estructura general de la aplicación
     * @method validarEstructuraApp
     * @since 1.4
     */
    function validarEstructuraApp () {

        if (defined('LAYOUT_DEFAULT')) {
            $this->layoutDefault = LAYOUT_DEFAULT;
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
    function validarDefiniciones ($controlador, $metodo = "", $modulo = "") {

        if (!empty($controlador))
            $this->controlador = $controlador;
        if (!empty($metodo))
            $this->nombreVista = $metodo;
        if (!empty($modulo))
            $this->modulo = $modulo;

        if (defined('DIR_FRAMEWORK')) {
            $this->rutaFramework = DIR_FRAMEWORK . "/Jadmin/Vistas/";
        }
        else {
            throw new Excepcion("No se encuentra definida la ruta de las vistas del admin jida. verifique las configuraciones",
                                1);
        }
        #Ruta para vistas de la aplicacion
        if (!empty($modulo)) {
            $this->rutaApp = DIR_APP . "Modulos/" . ucwords($modulo) . "/Vistas/";
        }
        else {
            $this->rutaApp = DIR_APP . "Vistas" . "/";
        }

        $this->url = (Medios\Sesion::obt('URL_ACTUAL')[0] != "/") ? "/" . Medios\Sesion::obt('URL_ACTUAL') : Medios\Sesion::obt('URL_ACTUAL');
    }

    /**
     * Define los directorios por defecto a manejar
     *
     * Los tipos de directorio son :
     * <ul> <li>1 Aplicación</li> <li>2Jida </li> <li>3 Excepciones</li></ul>
     * @method definirDirectorios
     */
    function definirDirectorios () {

        /* Verificación de ruta de plantillas */

        if (!$this->_esJadmin) {

            $this->urlPlantilla = DIR_PLANTILLAS_APP;
            $this->directorioLayout = DIR_LAYOUT_APP;
            if (!empty($this->_tema)) {

                if (!Directorios::validar(DIR_LAYOUT_APP . $this->_tema)) {
                    throw new Excepcion("No se consigue el tema definido", 1);
                }

                $this->directorioLayout .= $this->_tema . "/";
                if (empty($this->layout) or !Directorios::validar($this->directorioLayout . $this->obtNombreTpl($this->layout))) {

                    if (Directorios::validar($this->directorioLayout . $this->obtNombreTpl($this->controlador))) {
                        $this->layout = $this->obtNombreTpl($this->controlador);
                    }
                    else
                        if (!empty($this->modulo) and Directorios::validar($this->directorioLayout . $this->obtNombreTpl($this->modulo))) {

                            $this->layout = $this->obtNombreTpl($this->modulo);
                        }
                        else if (Directorios::validar($this->directorioLayout . $this->obtNombreTpl($this->_tema))) {
                            $this->layout = $this->obtNombreTpl($this->_tema);
                        }
                        else {

                            $this->layout = $this->obtNombreTpl($this->layoutDefault);
                        }
                }
            }

        }
        else {

            if (array_key_exists('configuracion', $GLOBALS) and array_key_exists('temaJadmin',
                                                                                 $GLOBALS['configuracion'])) {
                echo "el tema es " . $GLOBALS['configuracion']['temaJadmin'];
            }
            else {

                $this->directorioLayout = DIR_LAYOUT_JIDA;
                $this->urlPlantilla = DIR_PLANTILLAS_FRAMEWORK;
            }
        }
    }

    /**
     * Retorna un valor con estructura de nombre de plantilla
     * @method obtNombreTpl
     * @param string tpl
     * @return string tpl recibe "nombreplantilla" y retorna "nombreplantilla.tpl.php";
     */
    private function obtNombreTpl ($tpl) {

        if (strpos($tpl, '.tpl.php') === false)
            return Medios\Cadenas::lowerCamelCase($tpl . '.tpl.php');

        return Medios\Cadenas::lowerCamelCase($tpl);
    }

    /**
     * Define el directorio donde debe ser buscada la vista
     * @method obtenerDirectorioVista
     */
    private function obtenerDirectorioVista () {

        if ($this->_ruta == 'framework') {
            $this->directorioVista = DIR_FRAMEWORK . "/Jadmin/";

            if (!empty($this->modulo)) {
                $this->directorioVista .= 'Modulos/' . $this->modulo . "/Vistas/";
            }
            else {
                $this->directorioVista .= 'Vistas/';
            }
        }
        else {
            $this->directorioVista = DIR_APP;
            $vistaFolder = ($this->_esJadmin) ? "/Jadmin/Vistas/" : '/Vistas/';

            if (!empty($this->modulo)) {
                $this->directorioVista .= 'Modulos/' . $this->modulo . $vistaFolder;
            }
            else {
                $this->directorioVista .= $vistaFolder;
            }
        }

        $controlador = Medios\Cadenas::lowerCamelCase(Medios\Cadenas::guionCaseToString($this->controlador));
        $controller = Medios\Cadenas::lowerCamelCase(str_replace('Controller', '', $controlador));
        $this->directorioVista .= $controller . "/";

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
    function renderizar ($nombreVista = "", $excepcion = false) {

        $this->procesarVariables();
        if (!empty($nombreVista)) {
            $this->nombreVista = $nombreVista;
        }

        /**
         * Se verifica si desea usarse una plantilla
         */
        if (!empty($DataTpl)) {
            $rutaVista = $this->procesarVistaAbsoluta();
        }
        else {

            // Se accede a un archivo vista
            $rutaVista = $this->obtenerDirectorioVista();
            //Arma la estructura para una vista cualquiera

            if ($excepcion) {
                $rutaVista = $this->rutaExcepciones . $nombreVista . '.php';
            }
            else {
                $rutaVista = $rutaVista . Medios\Cadenas::lowerCamelCase($this->nombreVista) . ".php";
            }
        }

        if (!is_readable($rutaVista)) {
            throw new Excepcion("No se consigue el archivo $rutaVista", $this->_ce . 1);
        }

        $this->template = $rutaVista;

        if ((!empty($this->layout) and $this->layout !== false) or $excepcion === true) {
            $this->renderizarLayout($excepcion);
        }
        else {
            throw new Excepcion("No se encuentra definido el layout", $this->_ce . '10');
        }
    }

    //final funcion

    /**
     * Incluye una plantilla
     * @inconclusa
     */
    private function procesarVistaAbsoluta () {

        if ($this->getPath() == "jida") {
            $this->urlPlantilla = DIR_PLANTILLAS_FRAMEWORK;
        }
        else {
            $_plantilla = $this->urlPlantilla . Medios\Cadenas::lowerCamelCase($this->obtPlantilla()) . ".php";
            if (!file_exists($_plantilla)) {

                $this->urlPlantilla = DIR_PLANTILLAS_FRAMEWORK;
                $_plantilla = $this->urlPlantilla . Medios\Cadenas::lowerCamelCase($this->obtPlantilla()) . ".php";

                if (!file_exists($_plantilla)) {
                    throw new Excepcion("La plantilla solicitada no existe : " . $_plantilla, $this->_ce . '10');
                }
            }
        }

        return $_plantilla;
    }

    /**
     * Renderiza una vista en un layout definido
     * @method renderizarLayout
     * @access private
     */
    private function renderizarLayout ($excepcion = false) {

        ob_start();

        $this->layout = $this->directorioLayout . $this->layout;

        if (empty($this->layout) and !$excepcion) {

            throw new Excepcion("No se encuentra definido el layout para $this->template, controlador $this->controlador",
                                110);
        }
        else
            if (!file_exists($this->layout) and !$excepcion) {

                throw new Excepcion('No existe el layout ' . $this->layout, 1);

                //Debug::string('No existe el layout '.$this->layout,true);
            }
            else {
                $contenido = "";
                if (isset($this->template)) {
                    include_once $this->template;
                    $contenido = ob_get_clean();
                }
                include_once $this->layout;
                $layout = ob_get_clean();

                echo $layout;
            }
        //if (ob_get_length()) ob_end_clean();
    }

    private function requiresJs () {

    }

    /**
     * Procesa la excepción generada
     * @method procesarExcepcion
     */
    function procesarExcepcion (JExcepcion $e, $ctrlExcepcion) {

        $this->layout = LAYOUT_DEFAULT;

        if (class_exists($ctrlExcepcion)) {

            $ctrl = new $ctrlExcepcion($e);
            if (method_exists($ctrlExcepcion, 'layout'))
                $this->layout = $ctrl->obtLayout();
        }
        $codigo = $e->codigo();

        $this->excepcion = $e;
        $path = $this->directorioPlantillas . 'error/';

        $tpl = 'error';
        $this->directorioLayout = 'Framework/Layout/';
        if (Directorios::validar(DIR_APP . 'plantillas/error/')) {
            $path = DIR_APP . 'plantillas/error/';

            if (Directorios::validar($path . $codigo . ".php")) {

                $tpl = $codigo;
            }
            else if (Directorios::validar($path . 'error.php')) {

                $tpl = 'error';
            }
            else {

                $path = DIR_FRAMEWORK . '/plantillas/error/';
            }
        }
        else {

            $this->directorioLayout = 'Framework/Layout/';
        }

        $this->rutaExcepciones = $path;
        $this->renderizar($tpl, true);
    }

    function establecerAtributos ($arr) {

        $clase = __CLASS__;

        $metodos = get_class_vars($clase);
        foreach ($metodos as $k => $valor) {
            if (isset($arr[$k])) {
                $this->$k = $arr[$k];
            }
        }
    }

    private function imprimirArrayJs ($keyArrayPadre, $archivos, $pos, &$cont, $tipo = "script") {

        $js = "";

        if (is_array($archivos) and ($keyArrayPadre == ENTORNO_APP or $keyArrayPadre == $pos)) {

            $inclusiones = Arrays::obtenerKey($pos, $archivos);

            foreach ($inclusiones as $key => $value) {
                if (!is_string($key) and !empty($post)) {
                    $js .= Selector::crear('script', ['src' => $value], null, $cont);
                }
                else {
                    $this->imprimirArrayJs($key, $value, $pos, $cont);
                }
            }
        }
        else {
            switch ($keyArrayPadre) {
                case 'codigo':
                    $js .= $this->imprimirCodigoJs($archivos, $cont);
                    break;

                default:
                    if ($keyArrayPadre == $pos)
                        $js .= Selector::crear('script', ['src' => $value], null, $cont);
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
    function printJS ($pos = '') {

        $js = "";
        $this->checkData();
        $cont = 0;
        $code = [];

        $path = Estructura::$urlRuta;
        if (is_array($this->js)) {
            $data = [];
            if (!empty($pos)) {
                if (array_key_exists($pos, $this->js))
                    $data = $this->js[$pos];
                if (array_key_exists(ENTORNO_APP, $this->js) and array_key_exists($pos, $this->js[ENTORNO_APP]))
                    $data = array_merge($data, $this->js[ENTORNO_APP][$pos]);

                foreach ($data as $id => $archivo) {
                    $js .= Selector::crear('script', ['src' => $path . $archivo], null, $cont);
                    if ($cont == 0)
                        $cont = 2;
                }
            }
            else {
                if (array_key_exists('footer', $this->js)) {
                    $this->js = array_merge($this->js, $this->js['footer']);
                    unset($this->js['footer']);
                }
                if (array_key_exists('head', $this->js)) {
                    $this->js = array_merge($this->js, $this->js['head']);
                    unset($this->js['head']);
                }

                foreach ($this->js as $key => $archivo) {
                    if (is_string($key)) {
                        if ($key == ENTORNO_APP) {
                            foreach ($archivo as $id => $archivoEntorno) {
                                #Debug::mostrarArray($archivoEntorno,0);

                                if (is_string($archivoEntorno)) {
                                    $js .= Selector::crear('script', ['src' => $path . $archivoEntorno], null, $cont);
                                    if ($cont == 0)
                                        $cont = 2;
                                }
                                else if (is_string($id)) {
                                    foreach ($archivoEntorno as $key => $archivoSeccion) {
                                        $js .= Selector::crear('script',
                                                               ['src' => $path . $archivoSeccion],
                                                               null,
                                                               $cont);
                                        if ($cont == 0)
                                            $cont = 2;
                                    }
                                }
                            }
                        }
                    }
                    else {

                        $js .= Selector::crear('script', ['src' => $path . $archivo], null, $cont);
                    }
                    if ($cont == 0)
                        $cont = 2;
                }
            }
        }
        // foreach ($this->js as $key => $archivo) {
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

    private function imprimirCodigoJs ($codigo, $cont) {

        $js = "";

        if (is_array($codigo)) {

        }
        else {
            $js = Selector::crear('script', null, $codigo, $cont);
        }

        return $js;
    }

    /**
     * Imprime los archivos js incluidos en e
     *
     * @method printJSAjax
     */
    function printJSAjax () {

        $js = "";
        $this->checkData();
        $cont = 0;
        $code = [];

        if (is_array($this->jsAjax)) {
            if (array_key_exists('code', $this->jsAjax)) {
                $code = $this->jsAjax['code'];
                unset($this->jsAjax['code']);
            }
            foreach ($this->jsAjax as $key => $archivo) {

                if (is_string($key)) {
                    if ($key == ENTORNO_APP) {
                        foreach ($archivo as $key => $value) {
                            $js .= Selector::crear('script', ['src' => $value], null, $cont);
                            if ($cont == 0)
                                $cont = 2;
                        }
                    }
                }
                else
                    $js .= Selector::crear('script', ['src' => $archivo], null, $cont);
                if ($cont == 0)
                    $cont = 2;
            }
        }

        if (count($code) > 0) {
            foreach ($code as $key => $value) {
                if (array_key_exists('archivo', $value)) {
                    $contenido = file_get_contents($this->obtenerRutaVista() . $value['archivo'] . ".js");
                    $js .= Selector::crear('script', null, $contenido, $cont);
                }
                else {
                    $js .= Selector::crear('script', null, $value['codigo'], $cont);
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
    function printCssModulo ($modulo) {

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
    function imprimirLibrerias ($lang, $modulo = "") {

        $dataInclude = [];
        $path = (defined('URL_BASE')) ? URL_BASE : "";
        if (!property_exists($this->data, $lang))
            return false;
        $data = $this->{$lang};

        //Se eliminan las librerias incluidas en un entorno distinto al actual
        //o que pertenezcan a un $modulo no solicitado
        foreach ($data as $key => $value) {
            if (is_array($value) and $key != ENTORNO_APP and $key != $modulo)
                unset($data[$key]);
        }//fin forech

        if (array_key_exists(ENTORNO_APP, $data)) {
            $dataInclude = $data[ENTORNO_APP];
            //Se eliminan
            foreach ($dataInclude as $key => $value) {
                if (is_array($value) and $key != $modulo)
                    unset($dataInclude[$key]);
            }
            unset($data[ENTORNO_APP]);
        }

        $librerias = array_merge($dataInclude, $data);
        if (!empty($modulo)) {
            if (array_key_exists($modulo, $librerias)) {
                $libreriasModulo = $librerias[$modulo];
                unset($librerias[$modulo]);
                $librerias = $libreriasModulo;
            }
        }

        $libsHTML = "";
        $cont = 0;

        foreach ($librerias as $id => $libreria) {
            if (is_array($libreria) and $lang == 'css') {
                //se pasa como lenguaje la variable $id ya que es un una etiqueta link la que se creara
                //a partir del arreglo $libreria
                $libsHTML .= $this->__obtHTMLLibreria('link', $libreria, $cont);
            }
            else if (!is_array($libreria))
                $libsHTML .= $this->__obtHTMLLibreria($lang, $libreria, $cont);

            if ($cont == 0)
                $cont = 2;
        }//fin foreach=======================================
        return $libsHTML;
    }

    private function __obtHTMLLibreria ($lang, $libreria, $cont = 2) {

        $path = (defined('URL_BASE') and (is_string($libreria) and strpos($libreria,
                                                                          'http') === false)) ? URL_BASE : "";

        switch ($lang) {
            case 'js':
                if (is_array($libreria))
                    Debug::mostrarArray($libreria, 0);
                $html = Selector::crear('script', ['src' => $path . $libreria], null, $cont);
                break;
            case 'link':

                $libreria['href'] = $path . $libreria['href'];
                $html = Selector::crear('link', $libreria, null, $cont);
                break;
            default:
                //css
                $html = Selector::crear('link',
                                        [
                                            'href' => $path . $libreria,
                                            'rel'  => 'stylesheet',
                                            'type' => 'text/css'
                                        ],
                                        null,
                                        2);
                break;
        }

        return $html;
    }

    /**
     * Imprime las librerias css
     *
     */
    function printCSS () {

        $css = "";
        $path = (defined('URL_BASE')) ? URL_BASE : "";
        $this->checkData();
        $cont = 0;
        if (is_array($this->css)) {
            foreach ($this->css as $key => $files) {

                if (is_string($key)) {

                    if ($key == ENTORNO_APP) {
                        foreach ($files as $key => $value) {
                            if (is_array($value))
                                $css .= Selector::crear('link', $value, null, $cont);
                            else
                                $css .= Selector::crear('link',
                                                        [
                                                            'href' => $path . $value,
                                                            'rel'  => 'stylesheet',
                                                            'type' => 'text/css'
                                                        ],
                                                        null,
                                                        2);
                            if ($cont == 0)
                                $cont = 2;
                        }
                    }
                }
                else {
                    if (is_array($files)) {
                        if (array_key_exists('href', $files))
                            $files['href'] = $path . $files['href'];
                        $css .= Selector::crear('link', $files, null, $cont);
                    }
                    else {
                        $css .= Selector::crear('link',
                                                [
                                                    'href' => $path . $files,
                                                    'rel'  => 'stylesheet',
                                                    'type' => 'text/css'
                                                ],
                                                null,
                                                2);
                    }
                    if ($cont == 0)
                        $cont = 2;
                }
            }
        }
        else {

        }

        return $css;
    }

    private function checkData () {

        if (!$this->data instanceof DataVista) {
            $this->data = new DataVista();
            Debug::string("No se ha instanciado correctamente el objeto Data en el controlador $this->controlador",
                          true);
        }
    }

    private function printHTML ($html) {

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
    function printHeadTags () {

        $meta = "";
        $itemprop = "";
        $initTab = 0;
        //Titulo de La pagina
        if (count($this->meta) > 0) {
            $metaAdicional = "";

            foreach ($this->meta as $key => $dataMeta) {

                $metaAdicional .= Selector::crear('meta', $dataMeta, null, 2);
            }
            //$itemprop.=$metaAdicional;
            $meta .= $metaAdicional;
        }
        if ($this->google_verification != false) {
            $meta .= Selector::crear('meta',
                                     [
                                         "name"    => "google-site-verification",
                                         "content" => $this->google_verification
                                     ]);
        }
        if ($this->responsive) {

            $meta .= Selector::crear('meta',
                                     [
                                         "name"    => "viewport",
                                         'content' => "width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
                                     ]);
        }
        if (!empty($this->title)) {
            $meta .= Selector::crear('TITLE', null, $this->title, 0);
            $initTab = 2;
            $meta .= Selector::crear('meta',
                                     [
                                         'name'    => 'title',
                                         'content' => $this->title
                                     ],
                                     null,
                                     $initTab);
        }
        if (!empty($this->meta_descripcion)) {
            $meta .= Selector::crear('meta',
                                     [
                                         'name'    => 'description',
                                         'content' => $this->meta_descripcion
                                     ],
                                     null,
                                     $initTab);
            $itemprop .= Selector::crear('meta',
                                         [
                                             'itemprop' => 'description',
                                             'content'  => $this->meta_descripcion
                                         ],
                                         null,
                                         2);
        }
        if (!empty($this->meta_autor)) {
            $meta .= Selector::crear('meta',
                                     [
                                         'name'    => 'author',
                                         'content' => $this->meta_autor
                                     ],
                                     null,
                                     2);
            $itemprop .= Selector::crear('meta',
                                         [
                                             'itemprop' => 'author',
                                             'content'  => $this->meta_autor
                                         ],
                                         null,
                                         2);
        }
        if (!empty($this->meta_image)) {
            $meta .= Selector::crear('meta',
                                     [
                                         'name'    => 'image',
                                         'content' => $this->meta_image
                                     ],
                                     null,
                                     2);
            $itemprop .= Selector::crear('meta',
                                         [
                                             'itemprop' => 'image',
                                             'content'  => $this->meta_image
                                         ],
                                         null,
                                         2);
        }

        if (count($this->meta) > 0) {
            $metaAdicional = "\t\t<!---Tags Meta-----!>\n";

            foreach ($this->meta as $key => $dataMeta) {

                $metaAdicional .= Selector::crear('meta', $dataMeta, null, 2);
            }
            //$itemprop.=$metaAdicional;
        }
        if (!$this->robots) {
            $itemprop .= Selector::crear('meta',
                                         [
                                             'name'    => 'robots',
                                             'content' => 'noindex'
                                         ],
                                         null,
                                         2);
        }
        //URL CANNONICA
        if (!empty($this->url_canonical)) {
            $itemprop .= Selector::crear('link',
                                         [
                                             'rel'  => 'canonical',
                                             'href' => $this->url_canonical
                                         ],
                                         null,
                                         2);
        }

        return $meta . $itemprop . "\n";
    }

    /**
     * Renderiza una URL
     *
     * En estos momentos el metodo solo verifica si se estan manejando multiples
     * lenguajes y antepone el lenguaje actual a la url
     * @version beta
     *
     */
    function renderURL ($url, $lang = "") {

        if (defined('USO_IDIOMAS') and USO_IDIOMAS) {
            if (empty($lang) and !empty($this->idioma))
                $lang = $this->idioma;
        }
        if (!empty($lang))
            $lang = '/' . $lang;

        return $lang . $url;
    }

    /**
     * Retorna el layout a utilizar en la vista
     *
     * @param path $path Si es pasado el objeto buscará el layout en
     * el directorio indicado
     * @return path $path
     * @deprecated No se encuentra en uso
     */
    function pathLayout ($path = "") {

        if (!empty($path))
            $this->urlPlantilla = $path;

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
    function segmento ($segmento, $params = []) {

        if (!is_array($params))
            $params = [$params];

        foreach ($params as $key => $p)
            $this->$key = $p;

        $directorio = DIR_APP . 'Segmentos/';
        if (file_exists($directorio . $segmento . '.php')) {
            echo $this->incluir('Aplicacion/Segmentos/' . $segmento);
            // echo  $this->obtenerContenidos('Aplicacion/Segmentos/'.$segmento.'.php');
            // return true;
        }
        else {
            throw new \Excepcion("No existe el segmento $segmento en la carpeta " . $directorio, 100);
        }

        return false;
    }

    private function obtenerContenidos ($archivo) {

        ob_start();
        include_once $archivo;

        $contenido = ob_get_clean();
        if (ob_get_length())
            ob_end_clean();

        return $contenido;
    }

    /**
     * Función para incluir archivos
     * @param mixed $files Nombre de Archivo o arreglo de archivos a incluir
     *
     */
    function incluir ($archivo) {

        if (is_array($archivo)) {
            foreach ($archivo as $key => $ar) {
                include_once $ar . '.php';
            }
        }
        else if (is_string($archivo)) {
            include_once $archivo . '.php';
        }
    }

    /**
     * Función para incluir templates
     * @param mixed $archivo Nombre del archivo a incluir
     *
     */
    function incluirLayout ($archivo) {

        if (!$this->_tema) {
            $this->_tema = $this->_conf->tema;
        }

        $directorio = 'Aplicacion/Layout/' . $this->_tema . '/';
        $extension = '.php';

        if (is_array($archivo)) {
            foreach ($archivo as $key => $ar) {
                if (file_exists($directorio . $ar . $extension))
                    include_once $directorio . $ar . $extension;
                else
                    throw new Excepcion('No existe la plantilla' . $ar . $extension, 100);
            }
        }
        else if (is_string($archivo)) {
            if (file_exists($directorio . $archivo . $extension))
                include_once $directorio . $archivo . $extension;
            else
                throw new Excepcion('No existe la plantilla ' . $archivo . $extension, 100);
        }
    }

    /**
     * Procesa todas las variables del dataVista para que sean accedidas desde la vista
     *
     * @internal Recorre las propiedades del objeto DataVista instanciado y las asigna
     * en ejecucion al objeto.
     * @method procesarVariables
     */
    function procesarVariables () {

        $propiedades = get_object_vars($this->data);
        foreach ($propiedades as $k => $value) {
            $this->$k = $this->$k;
        }
    }

    /**
     * Permite acceder a un nexo
     *
     */
    function nexo ($nexo, $modulo = "") {

        $partes = explode(".", $nexo);
        if (count($partes) > 1) {

        }
        else {
            $modulo = (empty($modulo)) ? ucwords($this->_modulo) : ucwords($modulo);

            if ($this->_esJadmin) {

                $namespace = '\Jida\Jadmin\Modulos\\' . $modulo . '\Nexos\\';
            }
            else {
                $namespace = '\App\Modulos\\' . $modulo . '\Nexos\\';
            }
        }
        $nexoAbsoluto = $namespace . ucfirst($nexo);
        if (!class_exists($nexoAbsoluto))
            throw new Excepcion("No existe el nexo solicitado " . $nexoAbsoluto, $this->_ce . '90');

        $objNexo = new $nexoAbsoluto;

        return $objNexo;
    }

    /**
     * Retorna la url publica de los archivos htdocs de un tema
     * @method htdocs
     * @params string $folder Carpeta a obtener
     * @params string $item nombre del archivo
     * @params boolean $tema Determina si el archivo debe buscarse en el contenido
     * de un tema o en el contenido general.
     *
     */
    function htdocs ($folder, $item, $tema = true) {

        $path = Estructura::$urlRuta;
        $url = $path . URL_HTDOCS_TEMAS . $this->_tema . '/htdocs/' . $folder . '/' . $item;
        if ($tema)
            return $url;

        return $path . "htdocs/" . $folder . '/' . $item;
    }

    /**
     * Obtiene el texto correspondiente a una traduccion
     *
     * @internal Es un atajo para acceder al objeto traductor, el cual debe
     * haber sido instanciado en el controlador previamente. El traductor debe
     * haber sido pasado con el nombre de varible "traductor" al objeto DataVista
     * @method cadena
     * @param string $texto Texto a imprimir
     * @param string $ubicacion Ubicacion dentro del sistema de traducciones
     * @param string $seccion [opcional] seccion declarada en el sistema de traducciones
     *
     */
    function cadena ($texto, $ubicacion, $seccion = "") {

        if (!property_exists($this, 'traductor'))
            throw new Excepcion("El objeto vista no consigue al traductor, no se ha instanciado correctamente",
                                $this->_ce . '10');

        return $this->traductor->cadena($texto, $ubicacion);
    }

    /**
     * Permite incluir objetos media
     */
    function media ($folder, $item, $tema = true) {

        return $this->htdocs($folder, $item, $tema);
    }

    function enlace ($url = "") {

        $path = (defined('URL_BASE')) ? URL_BASE : '';
        if (!empty($this->idioma))
            $enlace = $path . '/' . $this->idioma . '/' . $url;
        else
            $enlace = $path . $url;

        return $enlace;
    }

    /**
     * Retorna la url actual para el idioma pasado
     *
     * Metodo provisional para manejo de urls desde las vistas
     * @method cambiarUrl
     * @param {string} idioma
     * @since 0.5
     */
    function cambiarUrl ($idioma) {

        $url = "/";
        if (!empty($this->modulo))
            $url .= $this->modulo . "/";
        if (!empty($this->controlador) and strtolower($this->controlador) != 'index')
            $url .= $this->controlador;
        if (!empty($this->metodo) and strtolower($this->metodo) != 'index')
            $url .= $this->metodo;

        $base = URL_BASE;
        $base = (empty($base)) ? "/" : "/" . URL_BASE . '/';
        $url = explode("/", Medios\Cadenas::guionCase($idioma, true) . '/' . Medios\Cadenas::guionCase($url, true));

        return $base . implode("/", array_filter($url));

    }

}
