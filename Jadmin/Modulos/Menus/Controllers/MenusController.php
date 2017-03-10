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
        $jctrl = new Modelos\JidaControl();
        $tablas = $jctrl->obtenerTablasBD();
    }

    public function index() {


        $tabla = new Render\jvista('Jida\Modelos\Menus.obtMenus',['titulos'=>['nombre']],'Menus');

        $tabla->accionesFila([
                ['span'=>'glyphicon glyphicon-folder-open','title'=>'Opciones menu','href'=>$this->obtUrl('listarOpciones',['{clave}'])],
                ['span'=>'glyphicon glyphicon-edit','title'=>'Modificar menu','href'=>$this->obtUrl('',['{clave}'])],
                ['span'=>'glyphicon glyphicon-trash','title'=>'Eliminar menu','href'=>$this->obtUrl('eliminarMenu',['{clave}']),
                 'data-jvista'=>'confirm','data-msj'=>'<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?']
            ]);


        $tabla->addMensajeNoRegistros('No hay Menus Registrados', [
                                                                'link'  =>$this->obtUrl(''),
                                                                'txtLink' =>'Crear Menu'
                                                                ]);
        $tabla->acciones(['nuevo ' => ['href'=>$this->obtUrl('nuevo')]]);

        $this->data(['tablaVista'=>$tabla->obtenerVista()]);


  }




    private function guardarMenu($id='')
    {
        if ($id!='') {
            $classMenu = new Modelos\Menus('menus',$id);
        }else{
            $classMenu = new Modelos\Menus('menus');
        }

        $classMenu->salvar($this->post());     

    }


    /**
     * Devuelve el formulario para registro y modificación de menues.
     */
    function nuevo() {
         // Helpers\debug::imprimir('holamundo',true);
        $form = new Render\Formulario('Menus');

        $form->boton('principal')->attr('value',"Crear menu");

        if ($this->post('btnMenus')) {
            if ($form->validar()) {
                self::guardarMenu();
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



    public function listarOpciones($id='',$padre=0) {

        $menu = new Modelos\Menus($id);
        $nombre = $menu->obt();


        $tabla = new Render\jvista('Jida\Modelos\OpcionesMenu.obtOpciones',['titulos'=>['Url','Nombre','Orden','estatus']],'Opciones de menu '.$nombre[0]['nombre_menu']);

        $tabla->clausula('filtro',['id_menu'=>$id,'padre'=>$padre]);


        $tabla->accionesFila([
                ['span'=>'glyphicon glyphicon-plus','title'=>'Agregar Opciones','href'=>$this->obtUrl('agregarOpcion',['{clave}',$id])],
                ['span'=>'glyphicon glyphicon-edit','title'=>'Modificar opcion','href'=>$this->obtUrl('actualizarOpcion',['{clave}',$id])],
                ['span'=>'glyphicon glyphicon-eye-open','title'=>'ver','href'=>$this->obtUrl('listarOpciones',[$id,'{clave}'])],
                ['span'=>'glyphicon glyphicon-trash','title'=>'Eliminar opcion','href'=>$this->obtUrl('eliminarOpcion',['{clave}']),
                 'data-jvista'=>'confirm','data-msj'=>'<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?']
            ]);


        $tabla->addMensajeNoRegistros('No hay opciones Registradas', [
                                                                'link'  =>$this->obtUrl(''),
                                                                'txtLink' =>'Crear Opcion'
                                                                ]); 
        $tabla->acciones(['nuevo ' => ['href'=>$this->obtUrl('agregarOpcion',[$id])]]);
        $tabla->acciones(['volver ' => ['href'=>$this->obtUrl('index')]]);

        $this->data(['tablaOpciones'=>$tabla->obtenerVista()]);


  }

    private function guardarOpcion($id='')
    {
        if ($id!='') {
             $opcion = new modelos\opcionesMenu('OpcionMenu',$id,2);
              helpers\debug::imprimir($opcion,true);

        }else{
             $opcion = new modelos\opcionesMenu();
        }
       
        $opcion->consulta($id)->salvar($this->post());     

    }


  public function agregarOpcion($id='',$padre=''){

        $formulario= new Render\Formulario('RegistroOpcion');
    
      

              $formulario->boton('principal')->attr('value',"Crear Opción");

        if ($this->post('btnRegistroOpcion')) {
            if ($formulario->validar()) {

                self::guardarOpcion();
                $this->redireccionar('jadmin\menus\index');
            }
        }

        $this->data(['tituloForm'=>'Registro De Opciones']);
        $this->dv->form = $formulario -> armarFormulario();
  }



      function eliminarOpcion($id='') {

        if ($this->getEntero($id)) {

            $cMenu = new Modelos\opcionesMenu($id);
            
            if(!empty($cMenu->id_opcion_menu)){
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

    /**
     * Funcion controladora de gestion de opciones de un menu
     * @method procesarOpciones
     */
    public function actualizarOpcion($id=''){

        $formulario= new Render\Formulario('RegistroOpcion',$id);
        $formulario->boton('principal')->attr('value',"Crear Opción");

        if ($this->post('btnRegistroOpcion')) {
            if ($formulario->validar()) {
                Helpers\debug::imprimir($_POST,true);
                $opcion = new modelos\opcionesMenu('OpcionMenu',$id,2);
                $opcion->salvar($this->post());     
                $this->redireccionar('\jadmin\menus\index');
            }
        }
        $this->data(['tituloForm'=>'Registro De Opciones']);
        $this->dv->form = $formulario -> armarFormulario();

    }
}

