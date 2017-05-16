<?php
/**
* Clase Controladora
* @author Julio Rodriguez
* @package
* @version
* @category Controller
*/
namespace Jida\Jadmin\Modulos\Formularios\Controllers;

use Jida\Helpers as Helpers;
use Jida\Render as Render;

class FormulariosController extends FController{
    
    private $_rutaJida;
    public $manejoParams = TRUE;
    
    function __construct(){
        

        parent::__construct();
        $this->_rutaJida = DIR_FRAMEWORK . 'formularios';
        
        //$this->dv->incluirJS('/Framework/htdocs/js/jadmin/formularios.js',FALSE);
        
    }
    function index(){
        
        $this->vista = 'vista';
        $jidaForms = Helpers\Directorios::listar($this->_rutaJida);
        Helpers\Sesion::destruir('JFormulario');
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
        
        $data = $this->_dataFormulario($formulario);
        if($data){
           if(array_key_exists('query', $data)){
                   
               unset($data['query']);
               return $data;
               
           }
        }
        return false;
    }

    function gestion($id=""){
                 Helpers\Debug::imprimir("aki",$id,func_get_args(), true);
        $this->_instanciarFormulario($id);
        $dataForm = [];
        $nombreFormulario = "";
        if(!empty($id)){
            
            $nombreFormulario = $id .'.json';
            $dataForm = $this->_dataFormulario($nombreFormulario);
            
            $titulo = 'Editar <strong>' . $dataForm['nombre']. '</strong>';
                
        }else{
            
            $titulo = 'Crear Nuevo Formulario';
                        
        }   
        
        $form = new Render\Formulario('GestionFormulario',$dataForm);
        $form->boton('principal','Guardar y editar campos');
        $form->titulo($titulo);     
            
        if($this->post('btnGestionFormulario') or $this->post('btnCampos')){
            if($this->_guardarFormulario($nombreFormulario)){
              
              $this->redireccionar($this->obtUrl('gestionCampos',[$this->_formulario->identificador]));
            }else{
                exit("no guarda");
            }
        }    
        
    
        $this->data([
            'form' =>$form->armarFormulario()
        ]);
       
    }



    /**
     * Gestiona el guardado del formulario
     * @param string $nombreFormulario identificador del formulario en UpperCamelCase
     */
    function _guardarFormulario($nombreFormulario){
        
        $post = $this->post();
        $bandera=false;
        #Helpers\Debug::imprimir($post,true);
        if($this->_formulario->salvar($post)){
            
          $msj = Helpers\Mensajes::crear('suceso','Formulario guardado correctamente');
          Helpers\Sesion::set('__msj',$msj);
          return true;      
        }
        return false;
      
    }
    function eliminar(){
        
    }

    
}
