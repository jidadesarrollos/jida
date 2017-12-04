<?php
/**
 * Clase para SelectorInput
 *
 * @author Julio Rodriguez
 * @package
 * @version
 * @category
 */

namespace Jida\Render\Inputs;

use Jida\BD\BD as BD;
use Jida\Helpers as Helpers;
use Exception as Excepcion;
use Jida\REnder\Selector as Selector;

class Select extends InputBase implements SeleccionInterface {

    use Seleccion;

    function __construct($data = "", array $attr = []) {

        $attr = [
            'name' => $data->name,
            'id'   => $data->id,
        ];
        $this->establecerAtributos($data, $this);
        parent::__construct($data->type, $attr);

        if (property_exists($data, 'opciones')) {
            $this->_obtOpciones($data->opciones);
            $this->_procesarArregloOpciones();
        }


    }


    function render() {

        $options = "";
        foreach ($this->_selectoresOpcion as $key => $option) {
            $options .= $option->render();
        }
        $this->innerHTML($options);

        return parent::render(TRUE);
    }

    /**
     * Retorna el objeto Selector de una opción solicitada
     * @method opcion
     *
     * @param string opcion Valor o label de la opción requerida
     *
     * @return Object Selector
     * @see \Jida\Render\Selector
     */
    function opcion($opcion) {

        $salida = FALSE;
        if (array_key_exists($opcion, $this->_opciones)) {
            $salida = $this->_selectoresOpcion[$opcion];
        } else if (in_array($opcion, $this->_opciones)) {


        } else {
            throw new \Exception('La opción solicitada no existe', $this->_ce . '000001');
        }

        #Helpers\Debug::imprimir($opcion, $salida, true);
        return $salida;

    }

    private function _procesarArregloOpciones() {

        $opciones = $this->_opciones;
        foreach ($opciones as $key => $data) {

            $opcion = new Selector('option', ['value' => $key]);
            $opcion->innerHTML($data);
            if (!empty($key) and $key == $this->_valor) {
                $opcion->attr('selected', 'selected');
            }
            $this->_selectoresOpcion[$key] = $opcion;

        }
    }

    function valor($valor) {

        $this->_valorUpdate = $valor;
        $busqueda = array_search($valor, $this->_opciones);
        if ($busqueda) {
            $this->selector[$busqueda]->attr('selected', 'selected');
        }

    }

}