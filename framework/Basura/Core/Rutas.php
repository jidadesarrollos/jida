<?php
/**
 * Manejador de Rutas del Framework
 *
 * Codigo de error 1
 * @since 0.6
 *
 */

namespace Jida\Core;

use Jida\Configuracion\Config;
use Jida\Medios as Medios;
use Jida\Manager\Estructura;

class Rutas {

    private $_ce = '30001';
    private $_conf;

    private $_solicitud;

    private $_rutaAbsoluta;
    private $_ruta;

    function __construct ($ruta, $tipo = "") {

        $this->_conf = Config::obtener();

        $this->_ruta = $ruta;
        $this->_solicitud = array_filter(explode("/", $ruta));

        switch ($tipo) {
            case 'formulario':
                $this->_analizarFormulario();
                break;
            case 'menu':
                $this->_analizarMenu();
                break;
            default:
                $this->_analizar();
                break;
        }

    }

    private function _analizarFormulario () {

        $jida = !!(in_array('jida', $this->_solicitud));
        $path = Estructura::path();

        if ((is_array($this->_solicitud) or is_object($this->_solicitud)) and count($this->_solicitud) > 1) {

            $modulo = array_shift($this->_solicitud);
            if ($jida) {
                $form = array_shift($this->_solicitud);
                $this->_rutaAbsoluta = $path . DS . Estructura::DIR_APP . DS . 'Formularios' . DS . $form;

            }
            else if ($this->_validarModulo($modulo)) {
                $form = array_shift($this->_solicitud);
                $this->_rutaAbsoluta = $this->_rutaModulo . DS . 'Formularios' . DS . $form;
            }

        }
        else {
            $form = array_shift($this->_solicitud);

            $directorio = $path . DS . Estructura::DIR_APP . DS . 'Formularios' . DS . $form;
            if (Medios\Directorios::validar($directorio)) {
                $this->_rutaAbsoluta = $directorio;

                return;
            }

            $directorio = $path . DS . Estructura::DIR_JIDA . DS . 'Formularios' . DS . $form;
            if (Medios\Directorios::validar($directorio)) {
                $this->_rutaAbsoluta = $directorio;

                return;
            }
            else {
                $msj = 'No existe la ruta solicitada para el formulario : ' . $directorio;
                throw new \Exception($msj, $this->_ce . 1);
            }

            $path = Estructura::path() . DS . Estructura::DIR_APP;
            $this->_rutaAbsoluta = $path . DS . 'Formularios' . DS . $form;

        }
    }

    private function _analizarMenu () {

        $jida = !!(in_array('jida', $this->_solicitud));
        if ((is_array($this->_solicitud) or is_object($this->_solicitud)) and count($this->_solicitud) > 1) {

            $modulo = array_shift($this->_solicitud);
            if ($jida) {
                $menu = array_shift($this->_solicitud);
                $this->_rutaAbsoluta = DIR_FRAMEWORK . DS . 'Menus' . DS . $menu;
            }
            else if ($this->_validarModulo($modulo)) {
                $menu = array_shift($this->_solicitud);
                $this->_rutaAbsoluta = $this->_rutaModulo . DS . 'Menus' . DS . $menu;
            }

        }
        else {
            $menu = array_shift($this->_solicitud);
            $this->_rutaAbsoluta = DIR_APP . 'Menus' . DS . $menu;
        }

    }

    private function _limpiar ($path, $ds = DS) {

        $array = array_filter(explode($ds, $path));

        return implode($ds, $array);
    }

    private function _analizar ($solicitud) {

        if ($this->_validarModulo($solicitud)) {
            $this->_rutaAbsoluta = DIR_APP . 'Modulos' . DS . ucwords($solicitud);
        }

    }

    function absoluta () {

        return $this->_limpiar($this->_rutaAbsoluta);
    }

    function _validarModulo ($modulo, $jida = "") {

        if (array_key_exists(strtolower($modulo), $this->_conf->modulos)) {
            $this->_rutaModulo = Estructura::path() . DS . Estructura::DIR_APP . DS . 'Modulos' . DS . ucwords($modulo);

            return true;
        }

        return false;
    }

    static function obtener ($ruta, $tipo = "") {

        return new Rutas($ruta, $tipo);
    }

}