<?php
/**
 * Codigo de error 8
 */

namespace Jida\Manager\Vista;

use Jida\Manager\Excepcion;
use Jida\Manager\Vista\Layout\Procesador;
use Jida\Medios\Debug;

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

            if (!Tema::$directorio) {
                $tema = Tema::obtener();
                self::$_urlTema = $tema::$url;
                self::$directorio = $tema::$directorio;
                self::$_configuracion = $tema::$configuracion;
                return true;
            }

            self::$_urlTema = Tema::$url;
            self::$directorio = Tema::$directorio;
            self::$_configuracion = Tema::$configuracion;

        }
        catch (\Excepcion $e) {
            Debug::imprimir(["excepcion", $e], true);
        }
        catch (\Error $e) {
            Debug::imprimir(["Error", $e], true);
        }

        return $this;
    }

    function _definirPlantilla($tpl) {
        $this->_plantilla = $tpl;
    }

    /**
     * @param $directorio
     */
    static function definirDirectorio($directorio) {
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

        try {

            if (!self::$directorio) {
                $msj = 'No se ha definido el directorio del layout';
                throw new \Exception($msj, self::$_ce . '0008');
            }
            if (!$vista) {
                $msj = 'El parametro $vista es requerido para el metodo render';
                throw new \Exception($msj, self::$_ce . '0001');
            }
            if (!$this->_plantilla) {
                //todo: manejar  layout por defecto
                throw new \Exception("No se ha definido layout para el metodo", self::$_ce . '0006');
            }

            $marco = self::$directorio . DS . $this->_plantilla;

            echo $this->_obtenerContenido(
                $marco,
                ['contenido' => $vista]
            );

        }
        catch (\Exception $e) {
            Debug::imprimir([
                "Excepcion en Layout::render",
                $e->getCode(),
                $e->getMessage(),
                $e->getTrace()
            ],
                true);
        }

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
            Debug::imprimir([
                "Excepcion en Layout::render",
                $e->getCode(),
                $e->getMessage(),
                $e->getTrace()
            ],
                true);
        }

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

        $configuracion = self::$_configuracion;

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

        try {

            if (!self::$instancia) {
                throw new \Exception("El objeto layout no ha sido instanciado", self::$_ce . 1);
            }

            return self::$instancia;

        }
        catch (\Exception $e) {
            Debug::imprimir([$e->getCode(), $e->getMessage(), $e->getTrace()], true);
        }

    }

    function __call($metodo, $params) {

        if (method_exists($this, $metodo)) {
            call_user_func_array([$this, $metodo], $params);
        }

    }
}