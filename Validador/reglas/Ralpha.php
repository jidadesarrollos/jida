<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Regla para validacion de caracteres alfabeticos 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Ralpha extends Regla {

    public $errorMsj = " {:attr} debe ser contener caracteres alfabeticos ";

    public function validar($value, array $parametros):bool {
        return ctype_alpha($value);
    }

}
