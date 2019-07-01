<?php
/**
 * Codigo de error 8
 */

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Manager\Vista\Layout\Gestor;
use Jida\Manager\Vista\Layout\Procesador;
use Jida\Medios;

class Layout {

    use Archivo, Render, RenderLayout, Procesador, Gestor;
    /**
     * @var object Objeto que llama o instancia a Layout
     */
    public static $padre;
    public static $directorio;
    private static $_ce = 10008;
    private static $_configuracion;
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

        if (!self::$instancia) {
            self::$instancia = new self();
        }

        return self::$instancia;

    }

    function _definirPlantilla($tpl) {
        $this->_plantilla = $tpl;

    }

    /**
     * Renderiza una vista
     * @method render
     *
     * @param $vista
     * @return void | string
     * @throws Excepcion
     */
    public function render($vista) {

        if (!self::$directorio) {
            $msj = 'No se ha definido el directorio del layout';
            throw new \Exception($msj, self::$_ce . '0008');
        }
        if (is_null($vista)) {
            $msj = 'El parametro $vista es requerido para el metodo render';
            throw new \Exception($msj, self::$_ce . '0001');
        }
        if (!$this->_plantilla) {

            $layout = Tema::propiedad('layout');
            $layout = "{$layout}.tpl.php";
            $this->_plantilla = $layout;

        }

        $marco = self::$directorio . DS . $this->_plantilla;

        $contenido = $this->_obtenerContenido(
            $marco,
            ['contenido' => $vista]
        );

        echo $contenido;

    }

    function renderizarExcepcion($plantilla) {

        try {
            $marco = self::$directorio . DS . $this->_plantilla;

            echo $this->_obtenerContenido(
                $marco,
                ['contenido' => $plantilla]
            );

        }
        catch (\Exception $e) {
            Medios\Debug::imprimir([
                "Excepcion en Layout::render",
                $e->getCode(),
                $e->getMessage(),
                $e->getTrace()
            ],
                true);
        }

    }

    function __call($metodo, $params) {

        if (method_exists($this, $metodo)) {
            call_user_func_array([$this, $metodo], $params);
        }

    }

    function config($propiedad) {

        $config = Config::obtener();
        if (property_exists($config, $propiedad)) {
            return $config->{$propiedad};
        }

    }

    function logo() {
        $config = Config::obtener();

        if (!!$config->logo) {
            return Estructura::$urlBase . $config->logo;
        }

        return Estructura::$urlBase . "/htdocs/img/logo.png";
    }

}