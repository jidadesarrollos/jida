<?php
/**
* Clase Controladora
* @author Julio Rodriguez
* @package
* @version
* @category Controller
*/
namespace Jida\Jadmin\Modulos\Formularios\Controllers;

use Jida\Core\Controller as Controller;
use Jida\Helpers as Helpers;
use Jida\Render as Render;

class FormulariosController extends Controller{
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
        $data = $params = []; 
        foreach ($jidaForms as $key => $archivo) {
            $data[] = $this->_dataFormulario($archivo);
        }
        $jvista = new Render\JVista($data, $params, 'Formularios');
        
        $this->data([
            'vista' =>$jvista->obtenerVista()
        ]);
    }
    /**
     * Lee la data del formulario y retorna un arreglo con los valores
     */
    private function _dataFormulario(){
        
        
    } 
}
