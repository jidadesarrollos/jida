<?php

/**
 * Clase Modelo para s_formularios
 *
 * @internal Clase creada para la transición de Formularios creados con la clase Formulario del Framework
 * en versiones anteriores a la version 0.5
 *
 *
 * @package Aplicacion
 * @category Modelo
 * @version 0.4

*/

namespace Jida\Modelos;
use Jida\Core\JsonManager as JsonManager;
use Jida\Helpers as Helpers;
use Jida\Core\GeneradorCodigo;
use Exception as Excepcion;

class Formulario extends JsonManager{
        
    use GeneradorCodigo\GeneradorArchivo;    
	
	var $nombre;
	var $query;
	var $clave_primaria;
	var $identificador;
	var $estructura;
    var $campos= [];
    
    private $_modelo = [
        'nombre', 
        'query', 
        'identificador', 
        'estructura',
        'clave_primaria', 
        'campos'
    ];
    
    private $_ambito='app';
    private $_json=[];
    private $_ubicacion;
    private $_modulo;
    private $_ce='10041';
    private $_campos=[];
    
    function __construct($form=""){
        
        $totalArgumentos = func_num_args();
        $argumentos = func_get_args();
        if(!empty($form)){
            parent::__construct($this->_obtUbicacionFormulario($argumentos));
            
            if($this->campos){
                $this->_procesarCampos();
            }
        }
            
        
        
        
        $ubicacion = ($this->_ambito=='app')?DIR_APP : DIR_FRAMEWORK;
        
    }
    private function _obtUbicacionFormulario($argumentos){
        $cantidad = count($argumentos);
        if($cantidad==2){
            
            $this->_modulo = $argumentos[1];
            
        
        }elseif($cantidad == 3){        
            $this->_ambito = $argumentos[1];
            $this->_modulo = $argumentos[2];    
        }
        
        $ubicacion = $this->ubicacion();
        $ubicacion .= DS . $this->_nombreJSON($argumentos[0]);
        $ubicacion = implode(DS,array_filter(explode(DS,$ubicacion)));
        
        
        return $ubicacion;
    }
    /**
     * Agrega la extension json al nombre de un archivo si no la tiene
     * @method _nombreJson
     * @param {string} $nombre Nombre del archivo 
     */
    private function _nombreJSON($nombre){
        if(strpos($nombre, '.json')===FALSE){
            return $nombre . '.json';
        }
        return $nombre;
        
    }
    /**
     * Permite definir la ubicación del formulario
     * 
     * @method ubicacion
     * @param string $ambito o jida.
     * @param string $modulo [opcional] Permite definir el modulo del formulario
     */
    function ubicacion($ambito="",$modulo=""){
        
        $ubicacion="";
        if(empty($ambito)) $ambito = $this->_ambito;
        if(empty($modulo)) $modulo = $this->_modulo;
        
        if($ambito=='app'){
            
            $ubicacion = DIR_APP;
            if(!empty($modulo)){
                
               $ubicacion .= DS . 'Modulos' . DS . Helpers\Cadenas::upperCamelCase($modulo);
               if(!Helpers\Directorios::validar($ubicacion)){
                   throw new Excepcion("El modulo pasado para guardar el formulario no existe ".$ubicacion, $this->_ce . '003');
               }
               $ubicacion .=    DS . 'Formularios';
               
               if(!Helpers\Directorios::validar($ubicacion)){
                   throw new Excepcion("El Formulario pasado no existe en el modulo ". $modulo ." no existe ".$ubicacion, $this->_ce . '004');
                   
               } 
            }else $ubicacion.= DS . 'Formularios';
            
        }else $ubicacion =  DIR_FRAMEWORK . DS . 'Formularios';
        
        $this->_ubicacion = $ubicacion;
        return $this->_ubicacion;
    }
    
    private function _procesarCampos(){
            
        $camposOrdenados = [];
        
        foreach ($this->campos as $key => $campo) {
            
            $campoClase = new CampoFormulario($campo);
            $camposOrdenados[$campoClase->orden] = $campoClase;
            $this->_campos[$campoClase->name] =(array) $campoClase;    
        }
        asort($camposOrdenados);
        
        $this->campos = $camposOrdenados;

    }
      
    
    /**
     * Crea el identificador en camelCase de un formulario
     * 
     * Usa el nombre del formulario para generar el identificador
     * @method _crearIdentificador
     * @param string $nombre Nombre del formulario
     * @return string $identificador Nombre del formulario en UpperCamelCase
     * 
     */
    private function _crearIdentificador($nombre=""){
            
        if(empty($nombre)) $nombre = $this->nombre;
        
        
        $this->identificador = $identificador;
        return $identificador;
        
        
    }
    /**
     * Registra los campos del formulario
     * 
     * Verifica si los campos pasados en la data existen en el formulario y sino los agrega.
     * @method _validarCampos
     * @param {mixed}  $campos String o Arreglo de campos
     */
    private function _validarCampos($campos){
          
        $campos = (is_array($campos))? $campos: explode(',',$campos);
        $array =[];

        foreach ($campos as $key => $nombre) {
            
            $nombreID = trim(str_replace(" ", "_", $nombre));
                        
            if(array_key_exists($nombreID, $this->campos))
                $array[$nombreID] = $this->campos[$nombreID];
            else{
                $campo =  new CampoFormulario();
                $campo->id = $nombreID;
                $campo->name = $nombreID;
                $campo->label = $nombre;
                $array[$nombreID] = $campo;
                
            }
                
                
        }
        $this->campos = $array;
        return $array;
        
    }
    /**
     * Genera un json con la data del formulario
     */
    private function _generarJson(){
        $json = [];
        foreach ($this->_modelo as $key => $campo) {
            
            $json[$campo] = $this->{$campo};
        }
        
        
        
        return json_encode($json,JSON_PRETTY_PRINT,JSON_UNESCAPED_SLASHES);
    }
    /**
     * Guarda el contenido del objeto
     * @method salvar
     */
    function salvar($data=[]){
            
        if(!empty($data)){
            foreach ($data as $key => $valor) {
                    
                if($key!='campos' and property_exists($this,$key)){
                    $this->{$key} = $valor;
                }
                    
            }
            if(empty($this->identificador)) $this->_crearIdentificador();
                    
            $this->_validarCampos($data['campos']);    
        }
        
        
        $json  = $this->_generarJson();
        
        if(empty($this->identificador)) $this->_crearIdentificador();
        
        
        $directorio = $this->_ubicacion;
        
        if(Helpers\Directorios::validar($directorio)){
            Helpers\Directorios::crear($directorio);
        }
        
        $this
            ->crear($directorio . DS  .$this->identificador.".json")
            ->escribir($json)
            ->cerrar();
        
        return true;    
    }
    /**
     * Define el ambito de los formularios
     * 
     * Los ambitos posibles son app y jida si el ambito
     * jida es declarado los formularios se guardaran en la carpeta
     * formularios dentro del framework y no de la aplicacion
     */
    function _ambito($ambito='app'){
        $this->_ambito = $ambito;
    }
    
    
    function orden($campos){
        
        $totalCampos = count($this->campos);
        for($i = 0; $i<$totalCampos; ++$i){
            
            $campo =& $this->campos[$i];
            
            if(is_object($campo) and array_key_exists($campo->id, $campos)){
                $campo->orden = $campos[$campo->id];
            }
        }
        
        
        return $this;
        
    }
    
    function dataCampo($campo){
        
        if(array_key_exists($campo, $this->_campos)){
            if(is_object($this->_campos[$campo]['eventos'])){
                $this->_campos[$campo]['eventos'] ="";
            }
            return $this->_campos[$campo];
        }
        return false;
        
    }


}//fin clase