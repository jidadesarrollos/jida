<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Valida segun la exprecion regular pasada en el primer parametro de la regla 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rmatch extends Regla {

    public $errorMsj = "el atributo {:attr} no es valido";

    public function validar($value, array $parametros):bool {
        
        if (!is_string($value)) {
            
            return false;
            
        }

        return preg_match($parametros[0], $value);
        
    }

}
