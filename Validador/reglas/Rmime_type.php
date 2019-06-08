<?php

namespace Jida\Validador\Reglas;

use Jida\Validador\Regla;
use Jida\Validador\type\Archivo;

/**
 * Regla validar y sanitizar un archivo 
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Rmime_type extends Regla {

    public $errorMsj = " el archivo es invalido";

    public function validar($value, array $parametros): bool {


        if (is_array($value)) {
            
            if (isset($value['type'])) {
                
                if (is_array($value['type'])) {
                    
                    foreach ($value['type'] as $type) {
                        
                        if (!in_array($type, $parametros)) {
                            
                            return false;
                            
                        }
                        
                    }
                    
                }
                else {
                    
                    if (!in_array($value['type'], $parametros)) {
                        
                        return false;
                        
                    }
                    
                }
                
            }
            else {

                foreach ($value as $item) {
                    
                    if ($item instanceof Archivo) {
                        
                        if (!in_array($item->getType(), $parametros)){
                            
                            return false;
                            
                        }
                            
                    }
                    else {
                        
                        return false;
                        
                    }
                    
                }
                
            }
            
        }
        elseif ($value instanceof Archivo) {
            
            if (!in_array($value->getType(), $parametros)){
                
                return false;
                
            }
                
        }

        return true;
        
    }

}
