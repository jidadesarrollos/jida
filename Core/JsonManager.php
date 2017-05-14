<?php

namespace Jida\Core;
/**
 * Clase para de objetos modelos lectores de archivos JSOn
 * @experimental
 * @author Julio Rodriguez
 * @version 0.0.1
 * @since 0.5.1
 */
 
use Jida\Helpers as Helpers;
use Exception;
class JsonManager{
    use ObjetoManager;
    /**
     * Archivo o string a leer
     * @property mixed $_json
     */
    private $_json;
    /**
     * Json leido y convertido en objeto
     * @property object $_objetoJson
     */
    protected $_objetoJson;
    
    private $_ce='0031';
    protected $_clase;
    function __construct($json=""){
        $clase = __CLASS__;
        if(!empty($json)){
            $this->_obtenerJSON($json);
        }
        
    }

    protected function _obtenerJSON($json=""){
        
        $json = (empty($json))?$this->_json:$json;
        
        if(Helpers\Archivo::existe($json)){
            
            $contenido = file_get_contents($json);
            $data = json_decode($contenido);
        
            if(is_object($data)){
                
                $this->_objetoJson = $data;
                $this->establecerAtributos($this->_objetoJson,$this);
         
            }else{
                throw new Exception("El valor pasado no es un objeto json", $this->_ce . '1');
                
            }
            
            
        }
        
    }
   
    
}
