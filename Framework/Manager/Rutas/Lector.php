<?php

namespace Jida\Manager\Rutas;

use Jida\Configuracion\Config;

use Jida\Manager\Estructura;
use Jida\Medios\Sesion;

class Lector {

    // private $_ce = 10003;
    private $_get;
    private $_manager;
    /**
     * @var array $_args Argumentos GET.
     */
    private $_args;
    /**
     * URL
     */
    public $arrayUrl;
    public $configuracion;
    /**
     * @var Arranque $_arranque Objecto Arranque
     */
    private $_arranque;
    private $_idioma;

    /**
     * Lector constructor.
     *
     * @param $manager
     */

    public function __construct ($manager) {

        $this->_get = $_GET;
        $this->_manager = $manager;
        $this->configuracion = Config::obtener();

    }

    public function validar () {

        $this
            ->_verificarEstructura()
            ->_procesar()
            ->ejecutar();

    }

    /*
     * Procesa la informacion de arranque de la aplicacion
     *
     * @method _procesar
     * @return object Jida\Manager\Rutas\Arranque
     */
    private function _procesar () {

        if (!$this->_arranque) {
            $this->_arranque = new Arranque();
        }

        return $this->_arranque;

    }

    private function _verificarEstructura () {

        if (isset($this->_get['url'])) {
            unset($this->_get['url']);
        }

        if (count($this->_get)) {
            $this->_args = $this->_get;
        }

        $urlBase = Estructura::$urlBase;

        $urlActual = $urlBase . Sesion::obt('URL_ACTUAL');
        Sesion::editar('URL_ACTUAL', $urlActual);

        if (count(Estructura::$partes) > 0) {
            $this->_validarIdioma();
        }

        return $this;

    }

    private function _validarIdioma () {

        $actual = $this->arrayUrl[0];
        $idiomas = Config::obtener()->idiomas;

        if (array_key_exists($actual, $idiomas) or in_array($actual, $idiomas)) {

            $this->_idioma = $this->arrayUrl[0];
            array_shift($this->arrayUrl);

        }

    }

}