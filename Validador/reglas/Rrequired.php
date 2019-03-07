<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * requerido 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rrequired extends Regla {

    public $errorMsj = "el atributo {:attr} es requerido ";

    public function validar($value, array $parametros):bool {
        return !is_null($value) && $parametros[0];
    }

}
