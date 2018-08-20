<?php

namespace Jida\Manager;

use Jida\Helpers\Debug;
use Exception as Excepcion;

class Validador {

    private $_ce = 100011;

    static private $_entorno;

    function __construct () {

        $this->_manejoErrores();
        Entorno::configurar();

    }

    function _capturaErrores () {

        $error = error_get_last();
        if ($error) {
//            Debug::imprimir("Error capturado", $error);
//            exit("Error capturado" . $error['type']);

            return;
        }

    }

    private function _manejoErrores () {
        // TODO: corregir manejo de errores
        set_error_handler([
                              $this,
                              '_capturaErrores'
                          ]);
    }

    /**
     * Verifica las estructuras y componentes habilitadas
     *
     * Valida la estructura general de configuración para la aplicación
     * en ejecución
     * @method inicio
     * @return boolean true
     *
     */
    public function inicio () {

        global $elementos;
        $elementos = [
            'areas'     => [],
            'elementos' => []
        ];

        $this->_configurarEntorno()
            ->_validarConfiguracion();

        return true;

    }

    private function _configurarEntorno () {

        if (function_exists('ini_set')) {
            /**
             * Inclusión de directorios de aplicación, framework y libs dentro del path
             */
            ini_set('include_path', DIR_APP . PS . DIR_FRAMEWORK . PS . get_include_path());

        }
        else {
            throw new Excepcion("Debe activar la funcion ini_set para continuar..");

        }

        return $this;

    }

    private function _validarConfiguracion () {

        if (class_exists('\App\Config\Configuracion')) {
            $configuracion = new \App\Config\Configuracion();
            $configuracion->inicio();
        }

    }

}