<?PHP
/**
 * Controlador de modulo de Menus
 *
 * @package Framework
 * @subpackage Jadmin
 * @author  kerby Tabares <krtabares@gmail.com>
 * @version 0.5.1 
 */

namespace Jida\Jadmin\Modulos\menus\Controllers;

use Exception;
use Jida\Helpers as Helpers;
use Jida\RenderHTML as RenderHTML;
use Jida\Render as Render;
use Jida\Modelos\Viejos as ModelosViejos;
use Jida\Modelos as Modelos;


class MenusController extends \Jida\Jadmin\Controllers\JController {


   var $manejoParams=true;

    function __construct(){
        $this->layout="jadmin.tpl.php";
        $this->url="/jadmin/menus/";
        parent::__construct();

    }

    public function index() {


        $tabla = new Render\jvista('Jida\Modelos\Menus.obtMenus',['titulos'=>['nombre']],'Menus');

        $tabla->accionesFila([
                ['span'=>'glyphicon glyphicon-folder-open','title'=>'Opciones menu','href'=>'/jadmin/menus/opciones/{clave}/'],
                ['span'=>'glyphicon glyphicon-edit','title'=>'Modificar menu','href'=>$this->obtUrl('gestionMenu',['{clave}'])],
                ['span'=>'glyphicon glyphicon-trash','title'=>'Eliminar menu','href'=>$this->obtUrl('eliminarMenu',['{clave}']),
                 'data-jvista'=>'confirm','data-msj'=>'<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?']
            ]);

        $tabla->addMensajeNoRegistros('No hay Menus Registrados', [
                                                                'link'  =>$this->obtUrl(''),
                                                                'txtLink' =>'Crear Menu'
                                                                ]);
        $tabla->acciones(['nuevo ' => ['href'=>$this->obtUrl('gestionMenu')]]);

        $this->data(['tablaVista'=>$tabla->obtenerVista()]);


  }


    public function gestionMenu($id='')
    {
        if ($id != '') {
            $form = new Render\formulario('Menus',$id);
            $classMenu = new Modelos\Menus('menus',$id);
        }else{
            $form = new Render\formulario('Menus');  
            $classMenu = new Modelos\Menus('menus',$id); 
        }

        $form->boton('principal')->attr('value',"Crear menu");

        if ($this->post('btnMenus')) {
            if ($form->validar()) {
               $classMenu->salvar($this->post()); 
            }
        }

        $this->dv->form = $form -> armarFormulario();
    }



    function eliminarMenu($id='') {

        if ($this->getEntero($id)) {

			$cMenu = new Modelos\Menus($id);
            
	        if(!empty($cMenu->id_menu)){
	        	$cMenu->eliminar($id);
				// Render\Vista::msj('menus','suceso', 'Menu eliminado');

	        }else{
	        	// Render\Vista::msj('menus',"error","No se ha eliminado menu");
	        }

            $this->redireccionar('/jadmin/menus/');


        }else {
            throw new Exception("Debe seleccionar un menu", 1);
        }
    }//fin funcion

}

