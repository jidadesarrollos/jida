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


   public function index($id_menu,$padre=0) {

        if ($padre==0) {
            $padre='n-a';
        }

        $menu = new Modelos\Menus($id_menu);
        $nombre = $menu->obt();


        $tabla = new Render\JVista('Jida\Modelos\OpcionesMenu.obtOpciones',
					        ['titulos'=>['Url','Nombre','Orden','Estatus']],
					        'Opciones de Menu '.$nombre[0]['nombre_menu']
						);

        $tabla->clausula('filtro',['id_menu'=>$id_menu,'padre'=>$padre]);


        $tabla->accionesFila([
                ['span'=>'glyphicon glyphicon-plus','title'=>'Agregar Opciones','href'=>$this->obtUrl('gestionOpcion',['{clave}',$id_menu])],
                ['span'=>'glyphicon glyphicon-edit','title'=>'Modificar opcion','href'=>$this->obtUrl('gestionOpcion',[$padre,$id_menu,'{clave}'])],
                ['span'=>'glyphicon glyphicon-eye-open','title'=>'ver','href'=>$this->obtUrl('index',[$id_menu,'{clave}'])],
                ['span'=>'glyphicon glyphicon-trash','title'=>'Eliminar opcion','href'=>$this->obtUrl('eliminarOpcion',['{clave}',$id_menu,$padre]),
                 'data-jvista'=>'confirm','data-msj'=>'<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el menu seleccionado?']
            ]);



        $tabla->addMensajeNoRegistros('No hay opciones Registradas', [
                                                                'link'  =>$this->obtUrl(''),
                                                                'txtLink' =>'Crear Opcion'
                                                                ]); 
        $tabla->acciones(['nuevo ' => ['href'=>$this->obtUrl('gestionOpcion',[$padre,$id_menu])]]);
        $tabla->acciones(['volver ' => ['href'=>$this->obtUrl('index',[$id_menu])]]);

        $this->data(['tablaOpciones'=>$tabla->obtenerVista()]);

  }



	public function gestionOpcion($padre=0,$id_menu,$id=''){

        $modelosPerfiles = new modelos\opcionMenuPerfil();

        $padre= ($padre == 'n-a')? 0:$padre;

        if ($id!='') {

            $formulario= new Render\Formulario('RegistroOpcion',$id);
            $opcion = new modelos\opcionesMenu($id);

        }else{

            $formulario= new Render\Formulario('RegistroOpcion');
            $opcion = new modelos\opcionesMenu();
        }

        $formulario->boton('principal')->attr('value',"Crear Opción");

        if ($this->post('btnRegistroOpcion')) {

            // Helpers\debug::imprimir($this->post(),true);
            if ($formulario->validar()) {
               
                $paraGuardar = $this->post();
                $paraGuardar['id_menu']=$id_menu;
                $paraGuardar['padre']=$padre;
                // Helpers\debug::imprimir($paraGuardar,$id_menu,true);
                if ($opcion->salvar($paraGuardar)) {   
                        
                        if ($id=='') 
                             $id = $opcion->getResult()->idResultado();
                        
                        $modelosPerfiles->eliminar($id,'id_opcion_menu');

                        $id_perfil = $this->post('id_perfil');
                        $matriz = [];

                        foreach ($id_perfil as $key => $value) 
                            $matriz[] = ['id_opcion_menu'=> $id,'id_perfil'=> $value ];
                     
                        $modelosPerfiles->salvarTodo($matriz);

                        $this->redireccionar('/jadmin/menus/opciones/'.$id_menu.'/'.$padre);
                        
                    }else Helpers\debug::imprimir('error al guardar');

                // $this->redireccionar('\jadmin\menus\index');
            }
        }

        $this->data(['tituloForm'=>'Registro De Opciones']);
        $this->dv->form = $formulario -> armarFormulario();
  }



      function eliminarOpcion($id='',$id_menu,$padre) {

        if ($this->getEntero($id)) {

            $padre= ($padre == 'n-a')? 0:$padre;

            $cMenu = new Modelos\opcionesMenu($id);
            
            if(!empty($cMenu->id_opcion_menu)){
                $cMenu->eliminar($id);
                $modelosPerfiles = new modelos\opcionMenuPerfil();
                $modelosPerfiles->eliminar($id,'id_opcion_menu');
                // Render\Vista::msj('menus','suceso', 'Menu eliminado');

            }else{
                // Render\Vista::msj('menus',"error","No se ha eliminado menu");
            }

            $this->redireccionar('/jadmin/menus/opciones/'.$id_menu.'/'.$padre);


        }else {
            throw new Exception("Debe seleccionar un menu", 1);
        }
    }//fin funcion


}

