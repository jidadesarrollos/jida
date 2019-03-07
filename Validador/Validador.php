<?php

namespace Jida\Validador;

/**
 * Validador de datos  
 *
 * @author Enyerber Franco <enyerverfranco@gmail.com>
 * @package Framework
 * @category Validador
 *
 */
class Validador implements \ArrayAccess {

    protected $value;
    protected $opciones;
    protected $isArray            = false;
    protected $errores            = [];
    private $mensajes             = [];
    protected $reglas             = [];
    public static $reglasGlobales = [];

    public function __construct($value, $opciones) {

        $this->opciones = [];
        foreach ($opciones as $key => $v) {
            
            $this->opciones[$key] = $this->procesarOpciones($v);
            
        }
        $this->value = $value;
        foreach ($this->opciones as $key => $opcion) {
            
            $this->value[$key] = $this->validar($key, isset($this->value[$key]) ? $this->value[$key] : NULL, $opcion);
            
        }
        
    }

    /**
     * Crear un objeto para la validacion 
     * @param array $value array con valores para validar
     * @param array $opciones reglas de validacion
     * @return \self
     */
    public static function crear(array $value, array $opciones) {
        
        return new self($value, $opciones);
        
    }

    public static function uno(string $value, string $opciones) {
        
        $valid = new self(['uno' => $value], ['uno' => $opciones]);
        
        return $valid->valido();
        
    }

    /**
     * Registra una nueva regla de validacion 
     * @param string $nombre nombre de la regla 
     * @param Regla $regla regla de validacion 
     */
    public static function registrarRegla(string $nombre, Regla $regla) {
        
        self::$reglasGlobales[$nombre] = $regla;
        
    }

    private function parseError($atributo, $opcion, $parametros, $value, $mensaje) {
        
        foreach ($parametros as $i => $v) {
            
            $mensaje = str_replace("{:param[$i]}", $v, $mensaje);
            
        }
        $mensaje = str_replace("{:attr}", $atributo, $mensaje);
        $mensaje = str_replace("{:regla}", $opcion, $mensaje);
        return str_replace("{:valor}", $value, $mensaje);
        
    }

    private function validar($atributo, $valor, $opciones) {

        if ($valor == NULL && isset($opciones['required']) && $opciones['required'][0] == 'false') {
            
            return NULL;
            
        }
        foreach ($opciones as $opcion => $parametros) {

            $class = "\\Jida\\validador\\Reglas\\R" . $opcion;
            if (isset(self::$reglasGlobales[$opcion])) {
                
                $regla         = clone self::$reglasGlobales;
                $regla->reglas = $opciones;
                if (!$regla->validar($valor, $parametros)) {
                    
                    if (!is_array($this->errores[$atributo])){
                        
                         $this->errores[$atributo] = [];
                         
                    }
                       
                    if (isset($this->mensajes[$atributo]) && isset($this->mensajes[$atributo][$opcion])) {
                        
                        $mensaje = $this->mensajes[$atributo][$opcion];
                        
                    }
                    else {
                        
                        $mensaje = $regla->errorMsj;
                        
                    }
                    
                    $this->errores[$atributo][] = $this->parseError($atributo, $opcion, $parametros, $valor, $mensaje);
                    
                }
                else {
                    
                    $valor = $regla->processValue($valor, $parametros);
                    
                }
                
            }
            elseif (class_exists($class)) {
                
                $regla         = new $class();
                $regla->reglas = $opciones;
                if (!$regla->validar($valor, $parametros)) {
                    
                    if (!is_array($this->errores[$atributo])){
                        
                        $this->errores[$atributo] = [];
                        
                    }
                        
                    if (isset($this->mensajes[$atributo]) && isset($this->mensajes[$atributo][$opcion])) {
                        
                        $mensaje = $this->mensajes[$atributo][$opcion];
                        
                    }
                    else {
                        
                        $mensaje = $regla->errorMsj;
                        
                    }
                    
                    $this->errores[$atributo][] = $this->parseError($atributo, $opcion, $parametros, $valor, $mensaje);
                }
                else {
                    
                    $valor = $regla->processValue($valor, $parametros);
                    
                }
                
            }
            else {
                
                throw new \Exception("no existe la regla " . $opcion);
                
            }
            
        }
        
        return $valor;
        
    }

    /**
     * indica si los valores son validos 
     * @return boolean
     */
    public function valido() {
        
        return count($this->errores) == 0;
        
    }

    /**
     * la negacion de @see valido
     * @return boolean 
     */
    public function invalido() {
        
        return !$this->valido();
        
    }

    protected function procesarOpciones($string) {
        
        $str     = preg_split('/[\|]/', $string);
        $options = [];
        
        foreach ($str as $v) {
            
            $exp1 = explode(':', $v);
            $name = $exp1[0];
            unset($exp1[0]);
            if (strtolower($name) == 'pattern') {
                
                $options[$name] = implode(':', $exp1);
                
                continue;
                
            }
            $exp            = explode(',', implode(':', $exp1));
            /* if (count($exp) == 1) {
              $options[$name] = trim($exp[0]) == '' ? true : $exp[0];
              }
              else { */
            $options[$name] = $exp;
            //}
        }
        
        return $options;
        
    }

    /**
     * retorna los errores error de la clave pasada 
     * @param string $key (opcional) si no esta presente se retornan todos los errores 
     * @return array
     */
    public function error($key = null) {
        if (!$key)
            return $this->errores;
        return $this->errores[$key];
    }

    /**
     * retorna el primer error de la clave 
     * @param string $key
     * @return string
     */
    public function primerError($key) {
        return isset($this->errores[$key]) ? $this->errores[$key][0] : null;
    }

    /**
     * retorna el ultimo error de la clave 
     * @param string $key
     * @return string
     */
    public function ultimoError($key) {
        return isset($this->errores[$key]) ? $this->errores[$key][count($this->errores[$key]) - 1] : null;
    }

    /**
     *  @access private
     * @param type $offset
     * @param type $value
     */
    public function offsetSet($offset, $value) {
        $this->value[$offset] = $value;
    }

    /**
     *  @access private
     * @param type $offset
     * @return type
     */
    public function offsetExists($offset) {
        return isset($this->value[$offset]);
    }

    /**
     *  @access private
     * @param type $offset
     */
    public function offsetUnset($offset) {
        //	$this->Set($offset,NULL,time()-1000);
        unset($this->value[$offset]);
    }

    /**
     * @access private
     * @param type $offset
     * @return type
     */
    public function offsetGet($offset) {
        if (!isset($this->value[$offset])) {
            trigger_error("indice " . $offset . "no definido ", E_USER_NOTICE);
            $offset = NULL;
            return $offset;
        }
        return $this->value[$offset];
    }

}
