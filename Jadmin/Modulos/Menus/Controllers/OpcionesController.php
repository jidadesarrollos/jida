<?PHP
/**
 * Controlador de modulo de opciones
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


class OpcionesController extends \Jida\Jadmin\Controllers\JController {


   var $manejoParams=true;

    function __construct(){
        $this->layout="jadmin.tpl.php";
        $this->url="/jadmin/menus/";
        parent::__construct();

    }


   public function index($id='',$padre=0) {

        $menu = new Modelos\Menus($id);
        $nombre = $menu->obt();


        $tabla = new Render\JVista('Jida\Modelos\OpcionesMenu.obtOpciones',
					        ['titulos'=>['Url','Nombre','Orden','Estatus']],
					        'Opciones de Menu '.$nombre[0]['nombre_menu']
						);

        $tabla->clausula('filtro',['id_menu'=>$id,'padre'=>$padre]);


        $tabla->accionesFila([
                ['span'=>'glyphicon glyphicon-plus','title'=>'Agregar Opciones','href'=>$this->obtUrl('gestionOpcion',['{clave}',$id])],
                ['span'=>'glyphicon glyphicon-edit','title'=>'Modificar opcion','href'=>$this->obtUrl('gestionOpcion',['{clave}',$id])],
                ['span'=>'glyphicon glyphicon-eye-open','title'=>'ver','href'=>$this->obtUrl('index',[$id,'{clave}'])],
                ['span'=>'glyphicon glyphicon-trash','title'=>'Eliminar opcion','href'=>$this->obtUrl('eliminarOpcion',['{clave}']),
                 'data-jvista'=>'confirm','data-msj'=>'<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?']
            ]);


        $tabla->addMensajeNoRegistros('No hay Opciones Registradas',
        						 		['link'  =>$this->obtUrl('gestionOpcion'),
					                	 'txtLink' =>'Crear Opcion']
									 );
        $tabla->acciones(['Nuevo' => ['href'=>$this->obtUrl('gestionOpcion')]]);
        $tabla->acciones(['Volver' => ['href'=>$this->obtUrl('index',[$id])]]);

        $this->data(['tablaOpciones'=>$tabla->obtenerVista()]);

  }


  public function gestionOpcion($id='',$padre=''){
       
    
        if ($id!='') {

            $formulario= new Render\Formulario('RegistroOpcion',$id);
            $opcion = new modelos\opcionesMenu('OpcionMenu',$id,2);

        }else{

            $formulario= new Render\Formulario('RegistroOpcion');
            $opcion = new modelos\opcionesMenu();
        }

        $formulario->boton('principal')->attr('value',"Crear Opción");

        if ($this->post('btnRegistroOpcion')) {
            if ($formulario->validar()) {
               
                $opcion->consulta($id)->salvar($this->post());    
                $this->redireccionar('\jadmin\menus\index');
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


}

