<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;

/**
 * Regla convertir el texto en minusculas
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rstring extends Regla {

    public $errorMsj    = " {:attr} debe ser contener un string";
    protected $multiple = false;

    public function validar($value, array $parametros):bool {
        
        return is_string($value);
        
    }

    public function processValue($value, array $parametros) {
        
        foreach ($parametros as $param) {
            
            switch ($param) {
                case 'lower':
                    $value = strtolower($value);
                    break;
                
                case 'upper':
                    $value = strtoupper($value);
                    break;
                
                case 'md5':
                    $value = md5($value);
                    break;
                
                case 'trim':
                    $value = trim($value);
                    break;
                
                case 'htmlentities':
                    $value = htmlentities($value);
                    break;
                
                case 'htmlencode':
                    $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                    break;
                
                case 'urlencode':
                    $value = filter_var($value, FILTER_SANITIZE_ENCODED);
                    break;
            }
            
        }

        return $value;
        
    }

}
