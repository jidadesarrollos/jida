<?php
/**
 * Manejador de Rutas del Framework
 *
 * @since 0.6
 *
 */

namespace Jida\Core;


use Jida\Helpers as Helpers;

class Rutas {

    private $_conf;

    private $_solicitud;

    private $_rutaAbsoluta;
    private $_ruta;

    function __construct($ruta, $tipo = "") {

        $this->_conf = $GLOBALS['JIDA_CONF'];
        $this->_ruta = $ruta;
        $this->_solicitud = array_filter(explode("/", $ruta));

        switch ($tipo) {
            case 'formulario':
                $this->_analizarFormulario();
                break;
            default:
                $this->_analizar();
                break;
        }

    }

    private function _analizarFormulario() {

        $jida = !!(in_array('jida', $this->_solicitud));

        if (count($this->_solicitud) > 1) {

            $modulo = array_shift($this->_solicitud);
            if ($jida) {
                $form = array_shift($this->_solicitud);

                $this->_rutaAbsoluta = DIR_FRAMEWORK . DS . 'Formularios' . DS . $form;
                #$this->_rutaAbsoluta = '/Framework' . DS . 'Formularios' . DS . $form;
                #Helpers\Debug::imprimir($this->_rutaAbsoluta, $this->_rutaModulo, "NO", true);
            } else if ($this->_validarModulo($modulo)) {
                $form = array_shift($this->_solicitud);
                $this->_rutaAbsoluta = $this->_rutaModulo . DS . 'Formularios' . DS . $form;
            }


        } else {
            $form = array_shift($this->_solicitud);
            $this->_rutaAbsoluta = DIR_APP . 'Formularios' . DS . $form;
            #$this->_rutaAbsoluta = "/Aplicacion" . DS . 'Formularios' . DS . $form;
        }
    }

    private function _limpiar($path, $ds = DS) {

        $array = array_filter(explode($ds, $path));

        return implode($ds, $array);
    }

    private function _analizar($solicitud) {

        if ($this->_validarModulo($solicitud)) {
            $this->_rutaAbsoluta = DIR_APP . 'Modulos' . DS . ucwords($solicitud);
        }

    }

    function absoluta() {

        return $this->_limpiar($this->_rutaAbsoluta);
    }


    function _validarModulo($modulo, $jida = "") {


        if (array_key_exists(strtolower($modulo), $this->_conf->modulos)) {
            $this->_rutaModulo = DIR_APP . 'Modulos' . DS . ucwords($modulo);

            return TRUE;
        }

        return FALSE;
    }

    static function obtener($ruta, $tipo = "") {

        return new Rutas($ruta, $tipo);
    }

}