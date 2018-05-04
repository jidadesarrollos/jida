<?php

namespace Jida\Manager\Rutas;

use Jida\Helpers as Helpers;

class Inicio {

    private $_ce = '10002';
    private $_get;
    private $_manager;
    private $_urlOriginal;


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
    public $arrayUrl;
    public $configuracion;

    private $_controlador;

    /**
     * Inicio constructor.
     *
     * @param $manager
     */

    public function __construct ($manager) {

        $this->_get = $_GET;
        $this->_manager = $manager;
        $this->configuracion = $manager->configuracion();

    }

    private function _procesar () {

        if (!$this->_controlador) {
            $this->_controlador = new Arranque($this);
        }

        return $this->_controlador;

    }

    public function validar () {

        if ($this->_get['url']) {
            $this->_urlOriginal = utf8_encode($this->_get['url']);
        }
        $this->_verificarEstructura();


        $this->_procesar()->ejecutar();

        /*if ($controlador) {
            $this->_preparar($controlador);
            #$this->_preparar($controlador);
        }
        else {
            exit("NO");
        }*/


    }

    private function _verificarEstructura () {

        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
        $url = explode('/',
                       str_replace(
                           [
                               '.php',
                               '.html',
                               '.htm'
                           ],
                           '',
                           $url));


        $this->arrayUrl = array_filter($url,
            function ($var) {

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

        if (count($this->arrayUrl) > 0) {
            $this->_validarIdioma();
        }

        return $this;

    }

    private function _validarIdioma () {

        $actual = $this->arrayUrl[0];
        $idiomas = $this->configuracion->idiomas;

        if (array_key_exists($actual, $idiomas) or in_array($actual, $idiomas)) {

            $this->_idioma = $this->arrayUrl[0];
            array_shift($this->arrayUrl);

        }

    }


}