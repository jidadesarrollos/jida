<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;
use Jida\Validador\Type\Password;

/**
 * Regla para validacion de contraseñas
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rpassword extends Regla {

    public $errorMsj = "  ";

    public function validar($value, array $parametros): bool {
        
        return is_string($value);
        
    }

    public function processValue($value, array $parametros) {
        
        $opciones = [];
        if (!is_bool($parametros[0]) && $parametros[0] != 'null') {
            
            $opciones['salt'] = $parametros[0];
            if (isset($parametros[1]))
            {
                
                 $opciones['cost'] = $parametros[1];
                 
            }
               
        }
        
        return new Password($value, PASSWORD_BCRYPT, $opciones);
        
    }

}
