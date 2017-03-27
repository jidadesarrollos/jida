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

class Formulario extends JsonManager{
        
    use GeneradorCodigo\GeneradorArchivo;    
	var $id_form;
	var $nombre;
	var $query;
	var $clave_primaria;
	var $identificador;
	var $estructura;
    var $campos;
    
    private $_ambito='app';
    function __construct($form){

        parent::__construct($form);
        
        if($this->campos){
            $this->_procesarCampos();
        }
        
    }
    
    private function _procesarCampos(){
        foreach ($this->campos as $key => $campo) {
            $this->campos[$key] = new CampoFormulario($campo);    
        }
    }
    /**
     * Guarda el contenido del objeto
     * @method salvar
     */
    function salvar($data){
                
        foreach ($data as $key => $valor) {
                
            if(property_exists($this,$key)){
                
                $this->{$key} = $valor;
                $json  = json_encode($form,JSON_PRETTY_PRINT,JSON_UNESCAPED_SLASHES);
                
            }
                
        }
        
        $ubicacion = ($this->_ambito=='app')?DIR_APP : DIR_FRAMEWORK;
        $directorio = $ubicacion . DS . 'formularios';
        
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
    


}//fin clase