<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Regla para validar una url 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rurl extends Regla {

    public $errorMsj = "el atributo {:attr} debe ser un url valido";

    public function validar($value, array $parametros):bool {
        
        if (!is_string($value)) {
            
            return false;
            
        }
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            
            return false;
            
        }
        if ($parametros[0] == 'activo' && !checkdnsrr($value)) {
            
            return false;
            
        }
        return true;
        
    }

}
