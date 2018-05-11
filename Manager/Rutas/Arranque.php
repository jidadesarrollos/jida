<?php
/**
 *  Arranque Rutas\Arranque
 *
 *  Ejecuta el procesaodor para identificar la petición solicitada. Instancia al controlador y ejecuta el metodo
 * pedido. Posteriormente realiza el llamado al objeto pagina para realizar la renderización.
 */

namespace Jida\Manager\Rutas;


use Jida\Helpers as Helpers;
use Jida\Core\Manager as Core;
use Jida\Manager\Vista\Manager as ManagerVista;

class Arranque {

    private $_ce = 10002;
    private $_arrayUrl;
    public $procesador;
    /**
     * @var {object} Objeto controlador solicitado
     *
     */
    static public $Controlador;
    public static $metodo = false;
    /**
     * @var bool
     */
    static public $controlador = false;
    static public $namespace;
    static public $ruta;

    public $default;
    public $jadmin = false;

    public $modulo = false;
    public $parametros = [];
    public $modulos;


    private $_dataVista;
    private $_pagina;
    /**
     * Objeto Renderizador de la vista
     *
     * @var $_managerVista Object Vista
     * @see \Jida\Manager\Vista\Manager
     */
    private $_managerVista;

    public function __construct ($control) {

        $this->modulos = $control->configuracion->modulos;
        $this->_arrayUrl = $control->arrayUrl;
        $this->_parser();

    }

    private function _parser () {

        $parametro = $this->proximoParametro($this->_arrayUrl);

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
     * Verifica si hay funcionalidades definidas a ejecutar previo o posteriormente al metodo solicitado
     *
     *
     * Realiza la ejecución de los metodos _jdPost o _jdPre si existen.
     *
     * @param $controlador Arranque a ejecutar
     * @param $method Metodo a ejecutar. _jdPost o _jdPre
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
            self::$controlador = str_replace("Controller","", $controlador);

            $objeto = self::$namespace . $controlador;
            self::$Controlador = new $objeto();
        }

        return self::$Controlador;

    }

    public function ejecutar () {

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

            $this->_managerVista->renderizar($this);
            /*
                        $this->_pagina->data = $this->_dataVista;
                        $this->_pagina->_namespace = $this->namespace;
                        $this->_pagina->_controller = $this->controlador;
                        $this->_pagina->_modulo = $this->modulo;
                        $this->_pagina->layout = $controlador->layout;
                        $this->_pagina->definirDirectorios();
                        $this->_pagina->renderizar($controlador->vista);

              */
        }

    }

    private function _validar () {


        //$this->_pagina = new Core\Pagina($this->controlador, self::$metodo, $this->modulo, $this->ruta, $this->jadmin);


        $dataVista = new Core\DataVista($this->modulo, self::$controlador, self::$metodo, $this->jadmin);
        $GLOBALS['dataVista'] = $dataVista;
        $this->_dataVista = $dataVista;

        return true;
    }


}