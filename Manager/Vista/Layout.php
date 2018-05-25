<?php
/**
 * Codigo de error 3
 */

namespace Jida\Manager\Vista;

use function Composer\Autoload\includeFile;
use Jida\Configuracion\Config;
use Jida\Helpers as Helpers;
use Exception as Excepcion;
use Jida\Manager\Estructura;
use Jida\Render\Selector as Selector;

class Layout {

    private $_DIRECTORIOS = [
        'jida' => 'Framework/Layout/',
        'app'  => 'Aplicacion/Layout/'
    ];

    private $_ce = '10008';

    public static $padre;
    /**
     * @var _controlador Arranque solicitado por la url
     *
     */
    private $_controlador;

    private $_directorio;

    /**
     * @var _data Objeto Data Vista
     */
    private $_data;
    /**
     * @var $data  Objeto DataVista
     * @deprecated
     */
    public $data;

    static public $directorio;
    private static $instancia;

    public function __construct ($padre = "") {

        if ($padre) {
            self::$padre = $padre;
            self::$instancia = $this;
        }

    }

    public function leer () {

        $padre = self::$padre;
        $arranque = $padre::$Padre;

        $actual = explode(DS, __DIR__);
        $tema = (!!$arranque->jadmin) ? Config::obtener()->temaJadmin : Config::obtener()->tema;
        $posicion = array_search(Estructura::DIR_JIDA, $actual);

        $path = implode("/", array_chunk($actual, $posicion)[0]);

        //$path .= ($arranque::$ruta !== 'jida') ? "/" . Estructura::DIR_APP : "/" . Estructura::DIR_JIDA;
        $controlador = $arranque::$Controlador;
        $directorio = $this->_DIRECTORIOS['app'];


        if ($arranque->jadmin) {
            $directorio = ($tema === 'jadmin') ? $this->_DIRECTORIOS['jida'] : $this->_DIRECTORIOS['app'];
        }

        self::$directorio = $path . DS . $this->_directorio . $directorio;
        if (!!$tema) {
            self::$directorio .= $tema . DS;
        }

        self::$directorio .= $controlador->layout;

        return $this;

    }


    static function definir ($directorio) {

        self::$directorio = $directorio;
    }

    public function render ($vista) {

        if (!self::$directorio or !$vista) {
            throw  new Excepcion('El parametro $vista es requerido para el metodo render', $this->_ce . '0001');

            return;
        }

        $render = new Render();

        return $render->imprimir(self::$directorio, $vista);

    }

    /**
     * @deprecated
     * @see imprimirMeta
     */
    public function printHeadTags () {

        $msj = "El metodo printHeadTags se encuentra en desuso, por favor reemplazar por imprimir meta";
        throw new Excepcion($msj, $this->_ce . 3);

    }

    public function imprimirMeta () {

        if (is_object($this->_data)) {
            return Meta::imprimir($this->_data);
        }

        return;
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


    static function obtener () {

        if (!self::$instancia) {

            throw new Excepcion("El objeto layout no ha sido instanciado", self::$_ce . "1");
        }

        return self::$instancia;

    }
}