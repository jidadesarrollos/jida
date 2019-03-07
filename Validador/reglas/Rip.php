<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Regla validar ip
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rip extends Regla {

    public $errorMsj    = " {:attr} una ip valida";
    protected $multiple = false;

    public function validar($value, array $parametros):bool {
        
        if (isset($parametros[0])) {
            
            switch ($parametros[0]) {
                
                case 'ipv4':
                    return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
                    
                case 'ipv4':
                    return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
            }
            
        }

        return filter_var($value, FILTER_VALIDATE_IP);
        
    }

}
