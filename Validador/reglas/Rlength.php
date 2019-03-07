<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Regla que un texto sea de un tamaño determinado
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rlength extends Regla {

    public $errorMsj    = " {:attr} no tiene la cantidad de caracteres establecidos ";
    protected $multiple = false;

    public function validar($value, array $parametros):bool {
        if (isset($parametros[0]) && isset($parametros[1])) {
            return strlen($value) >= $parametros[0] && strlen($value) <= $parametros[1];
        }
        if (isset($parametros[0])) {
            return strlen($value) <= $parametros[0];
        }
        return false;
    }

}
