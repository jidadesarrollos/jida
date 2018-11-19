<?php
/**
 * Clase Modelo para AccionVistaSelector
 * @author Julio Rodriguez
 * @package
 * @version
 * @category
 */

namespace Jida\Render;

use Jida\Helpers as Helpers;

class AccionVistaSelector extends Selector {
    /**
     * @var object Span Objeto Selector Span dentro del objeto Accion
     */
    var $span;
    private $dataAccion;
    private $nombreAccion;

    /**
     *
     */
    function __construct($inner, $data, $selector = "a") {

        parent::__construct($selector);

        $this->innerHTML($inner);
        $this->dataAccion = $data;

        if (array_key_exists('span', $data)) {
            $this->span = new Selector('span');
            $this->span->attr($data['span']);
            unset($this->dataAccion['span']);
        }

        $this->_armarValores();

    }

    function nombreAccion() {
        return $this->nombreAccion;
    }

    /**
     * @method _armarValores
     */
    private function _armarValores() {

        $this->nombreAccion = Helpers\Cadenas::lowerCamelCase($this->innerHTML());
        $this->attr('id', Helpers\Cadenas::lowerCamelCase("accion " . $this->innerHTML()));
        $this->attr($this->dataAccion);

    }

    function render() {

        if ($this->span instanceof Selector) {
            $valor = $this->innerHTML();
            if (empty($valor)) {
                $this->innerHTML($this->span->render() . $this->innerHTML());
            }

        }

        return parent::render();
        
    }
}
