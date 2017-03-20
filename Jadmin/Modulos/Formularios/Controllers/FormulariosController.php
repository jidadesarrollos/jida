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

class FormulariosController extends JController{
    // Se define un  layout por defecto
	#var $layout="";
    private $_rutaJida;
    
    function __construct(){
        parent::__construct();
        $this->_rutaJida = DIR_FRAMEWORK . 'formularios';
    }
    function index(){
        
        $this->vista = 'vista';
        $jidaForms = Helpers\Directorios::listar($this->_rutaJida);
        // Helpers\Debug::imprimir($jidaForms,true);
        $formsInvalidos = $data = $params = [];
         
        foreach ($jidaForms as $key => $archivo) {
            $dataFormulario =$this->_dataVistaFormulario($archivo);
            if($dataFormulario){
                $data[] = $dataFormulario;
            }else{
                $formsInvalidos[] = $archivo;
            }
             
        }
        $params = [
            'titulos'=>['nombre','estructura','ID','Clave Primaria','Total Campos']
        ];
        
        $jvista = new Render\JVista($data, $params, 'Formularios');
        $jvista->accionesFila([
            // ['span'=>'fa fa-eye','title'=>"Ver Subcategorias",'href'=>$this->obtUrl('subcategorias',['{clave}'])],
            
            ['span'=>'fa fa-edit','title'=>"Editar",'href'=>$this->obtUrl('gestion',['{clave}'])],
            ['span'=>'fa fa-picture-o','title'=>'Editar Campos','href'=>$this->obtUrl('gestionCampos',['{clave}'])],
            ['span'=>'fa fa-trash','title'=>"Eliminar Formulario",'href'=>$this->obtUrl('eliminar',['{clave}']),
            'data-jvista'=>'confirm','data-msj'=>'<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el formulario seleccionado?'],
            
        ]);
        $jvista->acciones([
            'Nuevo Formulario' => ['href'=>'/jadmin/formularios/gestion']
            
        ]);
        $this->data([
            'vista' =>$jvista->obtenerVista()
        ]);
    }
    /**
     * Lee la data del formulario y retorna un arreglo con los valores
     */
    private function _dataVistaFormulario($formulario){
        $contenido =file_get_contents($this->_rutaJida . '/' . $formulario);
        $data = json_decode($contenido);
        if(is_object($data)){
            return [
                'id'=> $data->identificador,
                'nombre'=> $data->nombre,
                'estructura' => property_exists($data, 'estructura')?$data->estructura:'',
                'identificador'=> $data->identificador,
                'clave_primaria'=> property_exists($data, 'clave_primaria')?$data->clave_primaria:'',
                'campos'=>count($data->campos)
            
            ];    
        }
        return false;
    }
    
    function gestion($id=""){
        
        //$this->dv->usarPlantilla('formulario');
        $form = new Render\Formulario('GestionFormulario',$id);
        
        $this->data([
            'form' =>$form->armarFormulario()
        ]);
        //Helpers\Debug::imprimir($this->dv->form,true);
        
    }
    function eliminar(){
        
    }
    function gestionCampos(){
        
    }
}
