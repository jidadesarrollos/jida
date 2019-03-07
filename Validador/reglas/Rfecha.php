<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;
use Jida\Validador\Type\DateTime;
/**
 * valida y sanitiza fechas 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rfecha extends Regla {

    public $errorMsj = "el atributo {:attr} una fecha valida";

    const FormatDefault = "Y/m/d";

    protected $format;

    public function validar($value, array $parametros):bool {
        
        if (!is_string($value)) {
            
            return false;
            
        }
        $this->format = self::FormatDefault;
        if (is_string($parametros[0]) && $parametros[0] != '') {
            
            $this->format = $parametros[0];
            
        }

        $d = \DateTime::createFromFormat($this->format, $value);
        return $d && $d->format($this->format) == $value;
        
    }

    public function processValue($value, array $parametros) {
        
        return new DateTime($value, null, $this->format);
        
    }

}
