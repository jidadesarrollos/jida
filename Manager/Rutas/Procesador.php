<?php
/**
 *  Procesador de Url parseada
 *
 *
 */

namespace Jida\Manager\Rutas;

use Jida\Helpers as Helpers;
use Jida\Manager\Rutas\Procesador\Controlador;
use Jida\Manager\Rutas\Procesador\Metodo;

class Procesador {

    use Controlador, Metodo;

    protected $_padre;
    protected $_moduloValidado;
    protected $_default = 'Index';
    private $_ce = 10005;
    private $_namespaces = [
        'app'        => 'App\\Controllers\\',
        'modulo'     => 'App\\Modulos\\',
        'jida'       => '\\Jida\\Jadmin\\Controllers\\',
        'jidaModulo' => '\\Jida\\Jadmin\\Modulos\\'

    ];
    private $_namespace;

    function __construct (Arranque $padre) {

        $this->_padre = $padre;
    }

    public function procesar () {

        $this->_moduloValidado = false;
        $this->_modulo();
        $this->_controlador();
        $this->_metodo();
        $this->_argumentos();
        $padre = $this->_padre;
        $padre::$namespace = $this->_namespace;
    }

    protected function _modulo () {

        $padre = $this->_padre;

        $parametro = $padre->proximoParametro();
        $posModulo = $this->_validarNombre($parametro, 'upper');

        if (in_array($posModulo, $padre->modulos) or array_key_exists($posModulo, $padre->modulos)) {

            $padre::$modulo = $posModulo;
            $padre::$ruta = 'app';
            if ($padre->jadmin) {

                $this->_namespace = $this->_namespaces['modulo'] . $padre::$modulo . '\\Jadmin\\Controllers\\';
            }
            else {
                $this->_namespace = $this->_namespaces['modulo'] . $padre::$modulo . '\\Controllers\\';
            }

        }
        else if ($padre->jadmin) {

            $padre::$ruta = 'jida';
            if ($this->_moduloJadmin($posModulo)) {

                $padre::$modulo = $posModulo;
                $this->_namespace = $this->_namespaces['jidaModulo'] . $posModulo . '\\Controllers\\';

            }
            else {
                $padre->reingresarParametro($posModulo);
                $this->_namespace = $this->_namespaces['jida'];
            }

        }
        else {
            $this->_namespace = $this->_namespaces['app'];
            $padre->reingresarParametro($posModulo);
        }

    }

    private function _moduloJadmin ($posModulo) {

        $modulo = $this->_validarNombre($posModulo, 'upper');

        return in_array($modulo, Jadmin::$modulos);

    }

    private function _argumentos () {

        $parametros = $this->_padre->arrayUrl();

        if (is_array($parametros) and $parametros) {

            $parametros = array_filter($parametros,
                function ($valor) {

                    return !empty($valor) or $valor === 0;

                });
            $this->_padre->parametros = $parametros;

        }

    }

    /**
     * Ajusta el nombre de los Controladores y Metodos
     *
     * Realiza una modificación del string para crear nombres
     * de clases controladoras y metodos validas
     *
     * @method validarNombre
     * @param string $str Cadena a formatear
     * @param int $tipoCamelCase lower, upper
     *
     * @return string $nombre Cadena Formateada resultante
     */
    protected function _validarNombre ($str, $tipoCamelCase) {

        if (empty($str)) {
            return false;
        }
        if ($tipoCamelCase == 'upper') {
            $nombre = str_replace(" ", "", Helpers\Cadenas::upperCamelCase(str_replace("-", " ", $str)));
        }
        else {
            $nombre = str_replace(" ", "", Helpers\Cadenas::lowerCamelCase(str_replace("-", " ", $str)));
        }

        return $nombre;

    }

}