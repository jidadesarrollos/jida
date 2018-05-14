<?php
/**
 *  Procesador de Url parseada
 *
 *
 */

namespace Jida\Manager\Rutas;

use Jida\Helpers as Helpers;

class Procesador {

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

            $padre->modulo = $posModulo;
            $padre::$ruta = 'app';
            if ($padre->jadmin) {

                $this->_namespace = $this->_namespaces['modulo'] . $padre->modulo . '\\Jadmin\\Controllers\\';
            }
            else {
                $this->_namespace = $this->_namespaces['modulo'] . $padre->modulo . '\\Controllers\\';
            }

        }
        else if ($padre->jadmin) {

            $padre::$ruta = 'jida';
            if ($this->_moduloJadmin($posModulo)) {

                $padre->modulo = $posModulo;
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

    public function _controlador ($default = false) {

        $band = true;
        $tomado = false;
        $claseSufijo = $clase = false;

        if ($default) {

            $ctrlDefault = (empty($this->_padre->modulo) and $this->_padre->jadmin) ? 'Jadmin' : 'Index';
            $controlador = ($this->_padre->modulo) ? $this->_validarNombre($this->_padre->modulo,
                                                                           'upper') : $ctrlDefault;


            $default = $controlador;

        }
        else {
            $controlador = $this->_padre->proximoParametro();
            $tomado = true;
        }

        if (!empty($controlador)) {

            $clase = $this->_validarNombre($controlador, 'upper');
            $claseSufijo = $clase . 'Controller';

        }

        if (
            $clase and
            $band and (class_exists($this->_namespace . $clase))
            or class_exists($this->_namespace . $claseSufijo)
        ) {
            $controlador = (class_exists($this->_namespace . $clase)) ? $clase : $claseSufijo;
            $padre = $this->_padre;
            $padre::$controlador = $controlador;

            return true;

        }
        else if (!$default) {
            $tomado = false;
            $this->_padre->reingresarParametro($controlador);

            return $this->_controlador(true);

        }
        else if ($clase === $controlador) {

            throw new \Exception("No existe el controlador " . $this->_namespace . " $controlador solicitado",
                                 $this->_ce . '0000002');

        }

        if ($tomado) {
            $this->_padre->reingresarParametro($controlador);
        }


    }

    private function _validarMetodo ($controlador, $metodo) {

        $reflection = new \ReflectionClass($controlador);
        $padre = $this->_padre;

        if (method_exists($controlador, $metodo) and $reflection->getMethod($metodo)->isPublic()) {
            $padre::$metodo = $metodo;

            return true;
        }

        return false;

    }

    public function _metodo () {

        $posMetodo = $this->_padre->proximoParametro();
        $padre = $this->_padre;
        $controlador = $this->_namespace . $padre::$controlador;
        $default = true;

        if ($posMetodo) {

            $default = false;
            $metodo = $this->_validarNombre($posMetodo, 'lower');

            if (!$this->_validarMetodo($controlador, $metodo)) {

                $this->_padre->reingresarParametro($posMetodo);
                $default = true;

            }

        }

        if ($default) {

            $metodo = 'index';
            if (!$this->_validarMetodo($controlador, 'index')) {
                throw new \Exception('El controlador ' . $controlador . ' debe poseer un metodo index',
                                     $this->_ce . '0002');
            }

        }

        $padre::$metodo = $metodo;

        return true;

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

        if (!empty($str)) {
            if ($tipoCamelCase == 'upper') {
                $nombre = str_replace(" ", "", Helpers\Cadenas::upperCamelCase(str_replace("-", " ", $str)));
            }
            else {
                $nombre = str_replace(" ", "", Helpers\Cadenas::lowerCamelCase(str_replace("-", " ", $str)));
            }

            return $nombre;
        }

    }


}