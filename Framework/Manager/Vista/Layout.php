<?php
/**
 * Codigo de error 7
 */

namespace Jida\Manager\Vista;

use Jida\Configuracion\Config;
use Jida\Manager\Excepcion;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;
use Jida\Manager\Estructura;
use Jida\Manager\Vista\Layout\Procesador;

class Layout {

    use Archivo, Render, RenderLayout, Procesador;
    /**
     * @var object Objeto que llama o instancia a Layout
     */
    public static $padre;

    /**
     * @var object $data Objeto DataVista
     * @deprecated
     */
    public $data;

    public static $directorio;
    /**
     * @var object $_data Objeto Data Vista
     */
    private $_data;

    private $_DIRECTORIOS = [
        'jida' => 'Framework/Layout/',
        'app'  => 'Aplicacion/Layout/'
    ];

    private static $_ce = '10008';

    private $_tema;
    private $_configuracion;
    /**
     * @var string $_directorio Directorio fisico del tema y layout implementado
     */
    private static $_directorio;
    private static $instancia;
    /**
     * @var string $_urlTema Url de acceso al tema
     */
    private static $_urlTema;

    private $_js = [];
    private $_css = [];

    private $_plantilla;

    /**
     * Layout constructor.
     *
     * @param mixed $padre
     */
    public function __construct($padre = null) {

        if ($padre) {
            self::$padre = $padre;
            self::$instancia = $this;
            $this->_data = $padre->data;
        }
        else {
            $this->_data = new \StdClass();
        }

        $this->_leer();
        $this->_configuracion();

    }

    /**
     * lee el layout y define su directorio
     *
     * Verifica la configuracion de la aplicacion y define el directorio en el cual se encuentra
     * para disponibilizarlo en la propiedad estatica $directorio
     *
     * @return $this
     */
    private function _leer() {
        try {
            $padre = self::$padre;
            $arranque = $padre::$Padre;

            $tema = (!!$arranque->jadmin) ? Config::obtener()->temaJadmin : Config::obtener()->tema;

            $this->_tema = $tema;
            $path = Estructura::$directorio;

            $directorio = $this->_DIRECTORIOS['app'];

            self::$_urlTema = Estructura::$urlBase . 'Aplicacion/Layout/';
            if ($arranque->jadmin) {
                $config = Config::obtener();
                self::$_urlTema = '/' . Estructura::$urlBase . $config::PATH_JIDA . '/Jadmin/Layout/' . $this->_tema . "/";
                $dirJida = Estructura::$directorioJida . "/Jadmin/Layout/";

                $directorio = ($tema === 'jadmin' || Directorios::validar($dirJida . $tema))
                    ? $dirJida
                    : $this->_DIRECTORIOS['app'];
            }

            self::$directorio = $path . DS . $directorio;

            if ($tema) self::$directorio .= $tema;

        }
        catch (\Excepcion $e) {
            Debug::imprimir(["excepcion", $e], true);
        }
        catch (\Error $e) {
            Debug::imprimir(["Error", $e], true);
        }

        return $this;
    }

    /**
     * Obtiene la configuración del tema implementado
     *
     */
    private function _configuracion() {

        $archivoConfiguracion = self::$directorio . DS . "tema.json";

        if (!file_exists($archivoConfiguracion)) {
            $msj = "No se consigue el archivo de configuracion del tema $this->_tema en " . self::$directorio;
            \Jida\Manager\Excepcion::procesar($msj, self::$_ce . 5);
        }

        $configuracion = json_decode(file_get_contents($archivoConfiguracion));
        $entorno = Config::ENTORNO_APP;

        if (property_exists($configuracion, $entorno)) {

            foreach ($configuracion->{$entorno} as $propiedad => $valor) {
                $configuracion->{$propiedad} = $valor;
            }
            unset($configuracion->{$entorno});

        }

        $this->_configuracion = $configuracion;

    }

    private function _definirPlantilla($tpl) {
        $this->_plantilla = $tpl;
    }

    static function definir($directorio) {

        self::$directorio = $directorio;
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

        if (!self::$directorio or !$vista) {
            $msj = 'El parametro $vista es requerido para el metodo render';
            Excepcion::procesar($msj, self::$_ce . '0001');

        }
        if (!$this->_plantilla) {
            //todo: manejar layout por defecto
            Excepcion::procesar("No se ha definido layout para el metodo", self::$_ce . 6);
        }
        self::$directorio .= DS . $this->_plantilla;

        echo $this->_obtenerContenido(
            self::$directorio,
            ['contenido' => $vista]
        );

    }

    /**
     * Imprime las lirerias del lado cliente
     *
     * @see Layout\Procesador
     * @since 1.4
     * @param $lenguajes
     * @param string $modulo Si es pasado, la funcion buscara imprimir solo los valores del key correspondiente.
     * @return string $libsHTML renderización HTML de los tags de inclusión de las librerias.
     * @throws Excepcion
     */
    function imprimirLibrerias($lenguajes, $modulo = "") {

        $configuracion = $this->_configuracion;
        $lenguajes = (is_string($lenguajes)) ? (array)$lenguajes : $lenguajes;
        $retorno = "";

        foreach ($lenguajes as $lenguaje) {
            switch ($lenguaje) {
                case 'head':
                    $retorno = $this->_imprimirHead($configuracion, $modulo);
                    break;
                case 'js':
                    $retorno = $this->_imprimirJS($configuracion->{$lenguaje}, $modulo);
                    break;
                case 'css':
                    $retorno = $this->_imprimirCSS($configuracion->{$lenguaje}, $modulo);
                    break;
            }
        }

        return $retorno;

    }

    /**
     * @return Layout
     * @throws Excepcion
     */
    static function obtener() {

        if (!self::$instancia) {
            \Jida\Manager\Excepcion::procesar("El objeto layout no ha sido instanciado", self::$_ce . "1");
        }

        return self::$instancia;

    }

    function __call($metodo, $params) {

        if (method_exists($this, $metodo)) {
            call_user_func_array([$this, $metodo], $params);
        }

    }
}