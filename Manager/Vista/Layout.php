<?php
/**
 * Codigo de error 8
 */

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Manager\Textos;
use Jida\Manager\Vista\Layout\Gestor;
use Jida\Manager\Vista\Layout\Procesador;
use Jida\Manager\Vista\Render\Common;
use Jida\Manager\Vista\Render\Layout as RenderLayout;
use Jida\Medios\Debug;

class Layout {

    use Archivo, Common, RenderLayout, Procesador, Gestor;
    /**
     * @var object Objeto que llama o instancia a Layout
     */
    public static $padre;
    public static $directorio;
    private static $_ce = 10008;
    private static $_configuracion;
    private $_contenido;
    /**
     * @var string $_directorio Directorio fisico del tema y layout implementado
     */
    private static $_directorio;
    private static $instancia;
    /**
     * @var string $_urlTema Url de acceso al tema
     */
    private static $_urlTema;
    /**
     * @var object $data Objeto DataVista
     * @deprecated
     */
    public $data;
    private $config;
    /**
     * @var object $_data Objeto Data Vista
     */
    private $_data;
    private $_js = [];
    private $_jsAjax = [];
    private $_css = [];

    private $urlTema;

    private $_plantilla;
    /**
     * @var boolean $_custom True cuando el layout es definido desde un controlador.
     */
    private $_custom;
    private $_plantillaError;
    /**
     * @var object $textos Objeto Textos
     */
    public $textos;

    /**
     * Layout constructor.
     *
     * @param mixed $padre
     */
    public function __construct() {

        $this->_data = Data::obtener();
        $this->_leer();
        $this->urlBase = Estructura::$urlBase;
        $this->urlModulo = Estructura::$urlModulo;
        $this->url = Estructura::$url;
        $this->config = Config::obtener();
        $this->textos = Textos::obtener();

    }

    /**
     * lee el layout y define su directorio
     *
     * Verifica la configuracion de la aplicacion y define el directorio en el cual se encuentra
     * para disponibilizarlo en la propiedad estatica $directorio
     *
     * @return mixed
     */
    private function _leer() {

        if (!Tema::$directorio) {
            $tema = Tema::obtener();
            $this->urlTema = $tema::$url;
            self::$_urlTema = $tema::$url;
            self::$directorio = $tema::$directorio;
            self::$_configuracion = $tema::$configuracion;
            return true;
        }

        $this->urlTema = Tema::$url;
        self::$_urlTema = Tema::$url;
        self::$directorio = Tema::$directorio;
        self::$_configuracion = Tema::$configuracion;

    }

    /**
     * @param $directorio
     */
    static function definirDirectorio($directorio) {
        self::$directorio = $directorio;
    }

    /**
     * @return Layout
     * @throws Excepcion
     */
    static function obtener() {

        if (!self::$instancia) self::$instancia = new self();

        return self::$instancia;

    }

    function _definirPlantilla($tpl) {
        $this->_custom = true;
        $this->_plantilla = $tpl;
    }

    /**
     * Retorna el layout requerido
     */
    private function _get() {

        $layout = Tema::propiedad('layout');

        if ($this->_custom) return $this->_plantilla;

        if (is_object($layout)) {

            $this->_plantilla = "{$layout->default}.tpl.php";
            if ($layout->error) $this->_plantillaError = "{$layout->error}.tpl.php";

        }

    }

    private function _errorTpl() {

        $tema = Tema::obtener();
        $conf = $tema::$configuracion;

        $tpl = $this->_plantilla;

        if (is_object($conf->layout) && property_exists($conf->layout, 'error')) {
            $tpl = $conf->layout->error . ".tpl.php";
        }

        return $tpl;
    }

    /**
     * Renderiza una vista
     * @method render
     *
     * @param $vista
     * @return void | string
     * @throws Excepcion
     * @throws \Exception
     */
    public function render($vista, $error = false) {

        $this->_contenido = $vista;

        if (!self::$directorio) {
            $msj = 'No se ha definido el directorio del layout';
            Excepcion::procesar($msj, self::$_ce . '0008');
        }

        if (is_null($vista)) {
            $msj = 'El parametro $vista es requerido para el metodo render';
            Excepcion::procesar($msj, self::$_ce . '0001');
        }

        $this->_get();

        $plantilla = (!$error) ? $this->_plantilla : $this->_errorTpl();
        $marco = self::$directorio . DS . $plantilla;
        $contenido = $this->_obtenerContenido($marco);

        echo $contenido;

    }

    private function contenido() {
        return $this->_contenido;
    }

    function __call($metodo, $params) {

        if (method_exists($this, $metodo)) {
            call_user_func_array([$this, $metodo], $params);
        }

    }

    function config($propiedad) {

        $config = Config::obtener();
        if (property_exists($config, $propiedad)) return $config->{$propiedad};

    }

    function logo() {
        $config = Config::obtener();

        if (!!$config->logo) return Estructura::$urlBase . $config->logo;

        return Estructura::$urlBase . "/htdocs/img/logo.png";
    }

}