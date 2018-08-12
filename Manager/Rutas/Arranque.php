<?php
/**
 *  Arranque Rutas\Arranque
 *
 *  Ejecuta el procesaodor para identificar la petición solicitada. Instancia al controlador y ejecuta el metodo
 * pedido. Posteriormente realiza el llamado al objeto pagina para realizar la renderización.
 */

namespace Jida\Manager\Rutas;

use Jida\Configuracion\Config;
use Jida\Helpers as Helpers;
use Jida\Core\Manager as Core;
use Jida\Manager\Estructura;
use Jida\Manager\Vista\Manager as ManagerVista;

class Arranque {

    private static $_ce = 10002;
    private $_arrayUrl;
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
    static public $namespace;

    static public $modulo;
    /**
     * @var string $ruta Define si la ruta de archivos debe ser buscada en el framework o en la aplicacion
     */
    static public $ruta;

    public $default;
    public $jadmin = false;

    public $parametros = [];
    public $modulos;

    private $_dataVista;
    /**
     * Objeto Renderizador de la vista
     *
     * @var $_managerVista Object Vista
     * @see \Jida\Manager\Vista\Manager
     */
    private $_managerVista;

    public function __construct ($control) {

        $this->modulos = Config::obtener()->modulos;
        $this->_arrayUrl = Estructura::$partes;
        $this->_parser();

    }

    private function _parser () {

        $parametro = $this->proximoParametro();

        if (strtolower($parametro) === 'jadmin') {
            $this->jadmin = true;
        }
        else {
            $this->reingresarParametro($parametro);
        }

        $this->procesador = new Procesador($this);
        $this->procesador->procesar();

    }

    public function proximoParametro () {

        $proximo = array_shift($this->_arrayUrl);

        return $proximo;

    }

    public function reingresarParametro ($parametro) {

        array_unshift($this->_arrayUrl, $parametro);

    }

    public function arrayUrl () {

        return $this->_arrayUrl;

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
    private function _pipeLines ($controlador, $metodo) {

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

    Static function obtenerControlador ($controlador) {

        if (!self::$Controlador or $controlador != self::$controlador) {
            self::$controlador = str_replace("Controller", "", $controlador);

            $objeto = self::$namespace . $controlador;
            self::$Controlador = new $objeto();
        }

        return self::$Controlador;

    }

    public function ejecutar () {

        try {
            if ($this->_validar()) {

                $controlador = self::obtenerControlador(self::$controlador);

                $this->_pipeLines($controlador, '_jdPre');

                call_user_func_array(
                    [
                        $controlador,
                        self::$metodo
                    ],
                    $this->parametros
                );

                $this->_pipeLines($controlador, '_jdPost');

                $this->_managerVista = new ManagerVista($this, $controlador, $this->_dataVista);
                $this->_managerVista->renderizar();

            }
        }
        catch (\Exception $e) {
            Helpers\Debug::imprimir([
                                        "capturada excepcion en arranque",
                                        $e
                                    ],
                                    ['corte' => true]);
        }
        catch (\Error $e) {
            Helpers\Debug::imprimir([
                                        "capturado error en arranque",
                                        $e
                                    ],
                                    ['corte' => true]);
        }

    }

    private function _validar () {

        Estructura::definir($this);
        $dataVista = new Core\DataVista($this->modulo, self::$controlador, self::$metodo, $this->jadmin);
        $GLOBALS['dataVista'] = $dataVista;
        $this->_dataVista = $dataVista;

        return true;
    }

}