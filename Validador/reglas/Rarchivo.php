<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;
use Jida\Validador\Type\Archivo;

/**
 * Regla validar y sanitizar un archivo 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rarchivo extends Regla {

    public $errorMsj    = "No se recibio el archivo o es invalidos";
    protected $multiple = false;

    public function validar($value, array $parametros): bool {

        if (!is_array($value) || count($value) == 0) {
            return false;
        }

        if (!isset($value['name']) || !isset($value['type']) || !isset($value['tmp_name']) || !isset($value['error']) || !isset($value['size']))
            return false;
        if ($parametros[0] == "multiple") {
            if (!is_array($value['name']) || !is_array($value['type']) || !is_array($value['tmp_name']) || !is_array($value['error']) || !is_array($value['size']))
                return false;
            foreach ($value['tmp_name'] as $tmp_name) {
                if (!is_uploaded_file($tmp_name) && isset($this->reglas['required']) && $this->reglas['required'] != false) {
                    return false;
                }
            }
        }
        else {
            if (!is_string($value['name']) || !is_string($value['type']) || !is_string($value['tmp_name']))
                return false;
            if (!is_uploaded_file($value['tmp_name']) && isset($this->reglas['required']) && $this->reglas['required'] != false) {
                return false;
            }
        }
        return true;
    }

    public function processValue($value, array $parametros) {

        if ($parametros[0] == "multiple") {
            $file = [];
            foreach ($value['tmp_name'] as $i => $tmp_name) {
                $file[] = new Archivo([
                    'name' => $value['name'][$i],
                    'type' => $value['type'][$i],
                    'tmp_name' => $value['tmp_name'][$i],
                    'error' => $value['error'][$i],
                    'size' => $value['size'][$i],
                ]);
            }
        }
        else {
            $file = new Archivo($value);
        }

        return $file;
    }

}
