<?php
/**
 *  Arranque Rutas\Arranque
 *
 *  Ejecuta el procesaodor para identificar la petición solicitada. Instancia al controlador y ejecuta el metodo
 * pedido. Posteriormente realiza el llamado al objeto pagina para realizar la renderización.
 *
 * $_ce 1
 */

namespace Jida\Manager\Rutas;

use Jida\Configuracion\Config;
use Jida\Core\Manager as Core;
use Jida\Manager\Estructura;
use Jida\Manager\Excepcion;
use Jida\Manager\Rutas\Procesador\Url;
use Jida\Manager\Vista\Data;
use Jida\Manager\Vista\Manager as ManagerVista;
use Jida\Medios\Debug;

class Arranque {

    private static $_ce = 10002;

    public $url;
    public $procesador;
    /**
     * @var {object} Objeto controlador solicitado
     *
     */
    static public $Controlador;
    static public $metodo = false;

    /**
     * @var bool
     */
    static public $controlador = false;
    static public $modulo;

    /**
     * @var string $ruta Define si la ruta de archivos debe ser buscada en el framework o en la aplicacion
     */
    static public $ruta;
    public $default;

    public $jadmin = false;

    public $parametros = [];
    private $_dataVista;
    /**
     * Objeto Renderizador de la vista
     *
     * @var $_managerVista Object Vista
     * @see \Jida\Manager\Vista\Manager
     */
    private $_managerVista;

    static $idioma;

    public function __construct($parametros = []) {

        $conf = Config::obtener();
        $this->_configuracion = $conf;

        if (count($parametros)) $this->_parametros($parametros);

        $this->modulos = $conf::$modulos;

        $this->url = new Url();
        $this->_get = $_GET;

        $this->_parser();

        $this->_managerVista = new ManagerVista($this);

    }

    private function _parser() {

        $parametro = $this->url->proximoParametro();

        //pipeline de procesamiento
        $this->_validarIdioma();
        if (strtolower($parametro) === 'jadmin') {
            $this->jadmin = true;
            Estructura::$jadmin = true;
        }
        else {
            $this->url->reingresarParametro($parametro);
        }

        $this->procesador = new Procesador($this);
        $this->procesador->procesar();

    }

    private function _parametros($parametros) {

        if (isset($parametros['handlers'])) {
            $this->procesarHandlers($parametros['handlers']);
        }

    }

    private function procesarHandlers($handlers) {
        Debug::imprimir([$handlers], true);
    }

    /**
     * Verifica si hay funcionalidades definidas a ejecutar previo o posterior al metodo solicitado
     *
     *
     * Realiza la ejecución de los metodos _jdPost o _jdPre si existen.
     *
     * @param object $controlador Arranque a ejecutar
     * @param string $method Metodo a ejecutar. _jdPost o _jdPre
     *
     * @since 0.6
     */
    private function _pipeLines($controlador, $metodo) {

        if (method_exists($controlador, $metodo)) {

            $respuesta = call_user_func_array(
                [
                    $controlador,
                    $metodo
                ],
                $this->parametros
            );

            if (!!$respuesta) {
                $this->parametros = $respuesta;
            }
        }

    }

    public static function obtenerControlador($controlador) {

        if (!self::$Controlador) {

            self::$controlador = str_replace("Controller", "", $controlador);

            Estructura::$controlador = $controlador;

            $objeto = Estructura::$namespace . $controlador;

            if (!class_exists($objeto)) {

                Excepcion::procesar("El controlador {$controlador} solicitado no existe", self::$_ce . 1);
            }

            self::$Controlador = new $objeto();

        }

        return self::$Controlador;

    }

    public function ejecutar() {

        try {

            $controlador = self::obtenerControlador(Estructura::$controlador);

            if ($this->_validar()) {

//                Debug::imprimir([Estructura::$rutaModulo,
//                    "modulo" . Estructura::$modulo,
//                    "controlador " . Estructura::$controlador,
//                    "metodo " . Estructura::$metodo,
//                ], true);

                $this->_pipeLines($controlador, '_jdPre');

                call_user_func_array(
                    [
                        $controlador,
                        Estructura::$metodo
                    ],
                    $this->parametros
                );

                $this->_pipeLines($controlador, '_jdPost');

                $this->_managerVista->renderizar();

            }
        }
        catch (\Exception $e) {
            Excepcion::controller($e);
        }
        catch (\Error $e) {
            Excepcion::controller($e);
            //Debug::imprimir(["obetnido error", $e->getMessage(), $e->getTrace()], true);

        }

    }

    private function _validar() {

        Estructura::definir($this);

        $ControlPadre = 'Jida\Core\Controlador\Control';
        $esData = (self::$Controlador instanceof $ControlPadre);

        if (!$esData) {
            $dataVista = new Core\DataVista(self::$modulo, self::$controlador, self::$metodo, $this->jadmin);
            $GLOBALS['dataVista'] = $dataVista;
            $this->_dataVista = $dataVista;
        }
        else {
            $data = Data::obtener();
            $GLOBALS['dataVista'] = $data;
            $this->_dataVista = $data;
        }

        return true;

    }

    private function _validarIdioma() {

        $parametro = $this->url->proximoParametro();

        $idiomas = $this->_configuracion->idiomas;

        if (array_key_exists($parametro, $idiomas) or in_array($parametro, $idiomas)) {
            $this->_idioma = $parametro;
            Estructura::$idioma = $parametro;
            return;
        }

        $this->url->reingresarParametro($parametro);

    }

}