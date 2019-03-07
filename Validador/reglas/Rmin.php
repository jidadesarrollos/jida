<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Valida que un numero sea >= a el indicado en el primer parametro  
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rmax extends Regla {

    public $errorMsj = " {:attr} debe ser menor a {:param[0]} ";

    public function validar($value, array $parametros):bool {
        $num = new Rnumerico();
        if (!$num->validar($value, []))
            return false;
        return $value >= $parametros[0];
    }

}
