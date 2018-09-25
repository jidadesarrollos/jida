<?php
/**
 * Codigo de error 4
 */

namespace Jida\Manager\Vista;

use Exception as Excepcion;
use Jida\Configuracion\Config;
use Jida\Manager\Estructura;

class Layout {

    use Archivo, Render, RenderLayout;
    private $_DIRECTORIOS = [
        'jida' => 'Framework/Layout/',
        'app'  => 'Aplicacion/Layout/'
    ];

    static private $_ce = '10008';

    /**
     * @var object Objeto que llama o instancia a Layout
     */
    public static $padre;

    /**
     * @var object $_data Objeto Data Vista
     */
    private $_data;
    /**
     * @var object $data Objeto DataVista
     * @deprecated
     */
    public $data;

    static public $directorio;
    private $_tema;

    private static $instancia;

    public function __construct ($padre = "") {

        if ($padre) {
            self::$padre = $padre;
            self::$instancia = $this;
        }

    }

    /**
     * @return $this
     */
    private function leer () {

        $padre = self::$padre;
        $arranque = $padre::$Padre;

        $tema = (!!$arranque->jadmin) ? Config::obtener()->temaJadmin : Config::obtener()->tema;

        $this->_tema = $tema;
        $path = Estructura::$directorio;
        /**
         * @var object $controlador
         * @see \Jida\Core\Controlador;
         */
        $controlador = $arranque::$Controlador;
        $directorio = $this->_DIRECTORIOS['app'];

        if ($arranque->jadmin) {
            $dirJida = Estructura::$directorioJida . "/Jadmin/Layout/";
            $directorio = ($tema === 'jadmin') ? $dirJida : $this->_DIRECTORIOS['app'];
        }

        self::$directorio = $path . DS . $directorio;
        if ($tema) {
            self::$directorio .= $tema . DS;
        }

        self::$directorio .= $controlador->layout();

        return $this;

    }

    static function definir ($directorio) {

        self::$directorio = $directorio;
    }

    /**
     * Renderiza una vista
     * @method render
     * @return void | string
     */
    public function render ($vista) {

        $this->leer();

        if (!self::$directorio or !$vista) {
            $msj = 'El parametro $vista es requerido para el metodo render';
            throw  new Excepcion($msj, self::$_ce . '0001');

        }

        $layout = $this->_obtenerContenido(self::$directorio, ['contenido' => $vista]);

        echo $layout;

    }

    /**
     * @deprecated
     * @see imprimirMeta
     */
    public function printHeadTags () {

        $msj = "El metodo printHeadTags se encuentra en desuso, por favor reemplazar por imprimir meta";
        throw new Excepcion($msj, self::$_ce . 3);

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
        //TODO: logica para imprimir librerias

    }

    static function obtener () {

        if (!self::$instancia) {

            throw new Excepcion("El objeto layout no ha sido instanciado", self::$_ce . "1");
        }

        return self::$instancia;

    }

}