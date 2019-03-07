<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Valida que el valor sea numerico 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rnumerico extends Regla {

    public $errorMsj = "el atributo {:attr} debe ser contener numeros ";

    public function validar($value, array $parametros):bool {
        if (isset($parametros[0])) {
            switch ($parametros[0]) {
                case 'int':
                    return is_integer($value);
                case 'float':
                    return is_float($value);
            }
        }
        else {
            return is_numeric($value);
        }
    }

}
