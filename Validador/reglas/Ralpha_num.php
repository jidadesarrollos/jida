<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Regla para validacion de caracteres alfanumericos 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Ralpha_num extends Regla {

    public $errorMsj = "{:attr} debe ser contener caracteres alfanumericos ";

    public function validar($value, array $parametros):bool {
        
        return ctype_alnum($value);
        
    }

}
