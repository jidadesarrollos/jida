<?php

namespace Jida\Inicio\Rutas;

use Jida\Helpers as Helpers;

class Control {

    private $_get;
    private $_manager;
    private $_urlOriginal;
    private $_arrayUrl;
    private $_configuracion;
    /**
     * @var string $_urlBase Url base de la app
     */
    private $_urlBase;
    /**
     * @var array $_args Argumentos GET.
     */
    private $_args;
    /**
     * URL
     */

    private $_esJadmin;

    /**
     * Control constructor.
     *
     * @param $manager
     */

    public function __construct($manager) {

        $this->_get = $_GET;
        $this->_manager = $manager;
        $this->_configuracion = $manager->configuracion();

    }

    public function validar() {

        if ($this->_get['url']) {
            $this->_urlOriginal = utf8_encode($this->_get['url']);
        }
        $this->_verificarEstructura();
        $parser = $this->_procesar();

        Helpers\Debug::imprimir($this->_get, $this->_arrayUrl, $this->_configuracion, true);

    }

    private function _verificarEstructura() {

        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
        $url = explode('/',
            str_replace(['.php',
                '.html',
                '.htm'
            ], '', $url));

        $this->_arrayUrl = array_filter($url, function ($var) {

            return !!$var;
        });

        unset($this->_get['url']);
        if (count($this->_get)) {
            $this->_args = $this->_get;
        }

        $this->_urlBase = str_replace(['index.php'], "", $_SERVER['PHP_SELF']);
        //Eliminar luego
        $GLOBALS['__URL_APP'] = $this->_urlBase;
        Helpers\Sesion::set('URL_ACTUAL', $this->_urlBase . Helpers\Sesion::obt('URL_ACTUAL'));

        if (count($this->_arrayUrl) > 0) {
            $this->_validarIdioma();
        }

        return $this;

    }

    private function _validarIdioma() {

        $actual = $this->_arrayUrl[0];
        $idiomas = $this->_configuracion->idiomas;

        if (array_key_exists($actual, $idiomas) or in_array($actual, $idiomas)) {

            $this->_idioma = $this->_arrayUrl[0];
            array_shift($this->_arrayUrl);

        }

    }

    private function _procesar() {

        return new Parser($this->_arrayUrl);

    }


}