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

class FormulariosController extends JController{
    
	use GeneradorCodigo\GeneradorArchivo;
    
    private $_rutaJida;
    public $manejoParams = TRUE;
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
        
        $data = $this->_dataFormulario($formulario);
        if($data){
           if(array_key_exists('query', $data)){
                   
               unset($data['query']);
               return $data;
               
           }
        }
        return false;
    }
    private function _dataFormulario($formulario){
            
        $archivoFormulario = $this->_rutaJida . '/' . $formulario;
        if(Helpers\Archivo::existe($archivoFormulario)){
            $contenido =file_get_contents($archivoFormulario);
            $data = json_decode($contenido);
            if(is_object($data)){
                return [
                    'id'=> $data->identificador,
                    'nombre'=> $data->nombre,
                    'estructura' => property_exists($data, 'estructura')?$data->estructura:'',
                    'identificador'=> $data->identificador,
                    'clave_primaria'=> property_exists($data, 'clave_primaria')?$data->clave_primaria:'',
                    'campos'=>count($data->campos),
                    'query'=>(property_exists($data, 'query'))?$data->query:""
                
                ];    
            }
        }
    }
    
    private function _formulario($formulario){
        $archivoFormulario = $this->_rutaJida . '/' . $formulario;
        if(Helpers\Archivo::existe($archivoFormulario)){
                
            $contenido =file_get_contents($archivoFormulario);
            $data = json_decode($contenido);
            return $data;
            
        }
    }
    
    function gestion($id=""){
        
        //$this->dv->usarPlantilla('formulario');
        $nombreFormulario = $id .'.json';
        $form = $this->_dataFormulario($nombreFormulario);
        
        if($form){
            
            $form = new Render\Formulario('GestionFormulario',$form);
            
            if($this->post('btnGestionFormulario')){
                if($this->_guardarFormulario($nombreFormulario)){
                    
                    Render\Formulario::msj('suceso','Formulario Registrado exitosamente',$this->obtUrl('index'));
                }
            }    
            $this->data([
                'form' =>$form->armarFormulario()
            ]);
        }else{
            Render\JVista::msj('formularios','alerta','No existe el formulario solicitado',$this->obtUrl('index'));
        }
       
    }
    function _guardarFormulario($nombreFormulario){
        
        $post = $this->post();
        $form = $this->_formulario($nombreFormulario);
        $bandera=false;
        
        foreach ($post as $key => $valor) {
                
            if(property_exists($form,$key)){
                    
                $bandera = true;
                $form->{$key} = $valor;
                $json  = json_encode($form,JSON_PRETTY_PRINT,JSON_UNESCAPED_SLASHES);
                
            }
            
        }
        if($bandera){
            if(!Helpers\Directorios::validar(DIR_APP . 'formularios/')){
             
                Helpers\Directorios::crear(DIR_APP . 'formularios/');
            }
            $this
                ->crear(DIR_APP . 'formularios/'.$form->identificador.".json")
                ->escribir($json)
                ->cerrar();
        }
    }
    function eliminar(){
        
    }
    function gestionCampos(){
        
    }
}
