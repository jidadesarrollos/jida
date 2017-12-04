<?php

namespace Jida\Render\Inputs;

use Jida\BD\BD as BD;
use Jida\Helpers as Helpers;
use Exception as Excepcion;
use Jida\Render\CloneSelector as CloneSelector;
use Jida\Render\Selector as Selector;

trait Seleccion {


    /**
     * Contiene los objetos SelectorInput de cada opcion de un control
     * de seleccion múltiple
     *
     * @param array $_selectoresOpcion
     */
    protected $_selectoresOpcion = [];

    protected $_opciones = [];

    /**
     * Verifica las opciones disponibles para el selector
     *
     * Verifica las opciones pasadas para la funcionalidad del selector y las estructura
     * para que sean usadas, las opciones a armar pueden ser pasadas de tres formas:
     *  - En un orden clave=valor
     *  - Con una consulta a base de datos
     *  - Pasando un key externo para hacer referencia a que serán seteadas de forma dinamica.
     *
     * @param $opciones
     */
    protected function _obtOpciones() {

        $revisiones = explode(";", $this->opciones);
        $opciones = [];

        foreach ($revisiones as $key => $opcion) {

            if (stripos($opcion, 'select ') !== FALSE) {

                $data = BD::query($opcion);
                foreach ($data as $key => $opcion) {

                    if (!is_array($opcion)) {
                        $data = explode("=", $opcion);
                        $opciones[$data[0]] = trim($data[1]);
                    } else {
                        $keys = array_keys($opcion);
                        $opciones[$opcion[$keys[0]]] = $opcion[$keys[1]];
                    }

                }

            } elseif (stripos($opcion, 'externo') !== FALSE) {
                continue;

            } else {

                $data = explode("=", $opcion);
                $opciones[$data[0]] = trim($data[1]);
            }
        }
        #Helpers\Debug::imprimir($revisiones, $opciones, true);
        $this->_opciones = array_filter($opciones);

    }

    /**
     * Agrega opciones al Selector de selecion
     * @method agregarOpciones
     *
     * @param      $opciones
     * @param bool $adicion Si es pasada en true y el selector tenia opciones las opciones
     *                      pasadas son agregadas, si es false se reemplazarán.
     *
     * @return $this
     * @throws \Exception
     */
    function agregarOpciones($opciones, $adicion = FALSE) {

        if (!is_array($opciones)) {
            throw new Excepcion('Las opciones no se han pasado correctamente', $this->_ce . '000008');
        }

        if ($adicion) {
            $this->_opciones = array_merge($this->_opciones, $opciones);
        } else {
            $this->_opciones = $opciones;
        }
        $this->_procesarArregloOpciones();

        return $this;

    }

    /**
     * Crea los objetos selector para cada opcion de un selector multiple
     * @method crearOpcionesSelectorMultiple
     */
    /**
     * private function _crearOpcionesSelectorMultiple($opciones) {
     *
     *
     * for ($i = 0; $i < count($opciones); ++$i) {
     *
     * $class = new \stdClass();
     * $class->value = array_shift($opciones[$i]);
     * $class->labelOpcion = array_shift($opciones[$i]);
     *
     * $class->name = ($this->type == 'checkbox') ? $this->name . "[]" : $this->name;
     * $class->type = $this->type;
     * $class->_tipo = $this->type;
     * $class->_identif = 'objectSelectorInputInterno';
     * $class->id = $this->id . "_" . ($i + 1);
     *
     * $selector = new Input($class, $this->type);
     * if ($class->value == $this->_valorUpdate) {
     * $selector->attr('checked', 'checked');
     * }
     * array_push($this->_selectoresOpcion, $selector);
     * }
     * }
     *
     * /**
     * Genera los objeto selector para las opciones de un select
     * @method crearOpcionesSelect
     */


}