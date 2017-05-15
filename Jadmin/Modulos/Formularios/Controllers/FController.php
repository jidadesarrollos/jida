<?php
/**
* Clase Controladora
* @author Julio Rodriguez
* @package
* @version
* @category Controller
*/
namespace Jida\Jadmin\Modulos\Formularios\Controllers;

use Jida\Jadmin\Controllers\JController as JController;
use Jida\Helpers as Helpers;
use Jida\Render as Render;
use Jida\Core\GeneradorCodigo;

class Fcontroller extends JController{
        
    use GeneradorCodigo\GeneradorArchivo;
    /**
     * @property object $_formulario Objeto std creado a partir del JSON de un formulario cargado
     */
    protected $_formulario;
        
    protected function _dataFormulario($formulario){
        
        $this->_instanciarFormulario($formulario);
        return [
            'id'=> $this->_formulario->identificador,
            'nombre'=> $this->_formulario->nombre,
            'estructura' => $this->_formulario->estructura,
            'identificador' => $this->_formulario->identificador,
            'clave_primaria' => $this->_formulario->clave_primaria,
            'campos' => count($this->_formulario->campos),
            'query' => $this->_formulario->query
        
        ];    
        
    }
    
    protected function _instanciarFormulario($id){
       
        if(is_object(Helpers\Sesion::obt('JFormulario'))){
                
            $clase = Helpers\Sesion::obt('JFormulario');
            if($clase->identificador == $id){
                $this->_formulario = $clase;
                return $this->_formulario;
            }
            
        }
        
        $formulario = new \Jida\Modelos\Formulario($id,'jida',false);
        $this->_formulario = $formulario;
        return $this->_formulario;
        
    }
    
    
    function __construct(){
        parent::__construct();
        $this->dv->addJsModulo('formularios.js','formularios');
    }
    function configuracion(){
        $url = '/jadmin/formularios/campos/configuracion/:formulario/:campo';
        if($this->solicitudAjax() and $this->post('campo')){
            
            
            
        }
    }
}