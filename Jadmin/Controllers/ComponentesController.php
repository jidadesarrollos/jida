<?PHP
/**
 * Definición de la clase
 *
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version 0.1
 */

namespace Jida\Jadmin\Controllers;
use Jida\Render as Render;
use Jida\Helpers as Helpers;
use Jida\Modelos\Viejos as Modelos;
class ComponentesController extends JController{
    var $layout="jadmin.tpl.php";
    function __construct($id=""){
        parent::__construct();
        $this->manejoParams=TRUE;
        $this->url="/jadmin/componentes/";

        $this->dv->title="Componentes de ".TITULO_SISTEMA;

    }
    function index(){

        $this->vista="vistaComponentes";
		
		
        // $query = "select id_componente, Componente as \"Componente\" from s_componentes";
        // $vista = new Render\Vista($query,$GLOBALS['configPaginador'],'Componentes');
		// $vista->setParametrosVista($GLOBALS['configVista']);
// 
        // $vista->filaOpciones=[
        // 0=>['a'=>['atributos'   =>[
                                    // 'class'         =>'btn','title'=>'Ver objetos del componente',
                                    // 'href'          =>$this->getUrl('objetos.lista',['comp'=>'{clave}']),
                                    // //'data-jvista'   =>'modal'
                                  // ],
                    // 'html'      =>[ 'span'=>['atributos'=>['class' => 'glyphicon glyphicon-folder-open']]]
                 // ]
             // ],
          // 1=>['a'   =>[ 'atributos' =>[ 'class' =>'btn',
                                        // 'title' =>'Asignar perfiles de acceso',
                                        // 'href'  =>"/jadmin/componentes/asignar-acceso/comp/{clave}",
                                        // //'data-jvista'=>'modal'
                                    // ],
                        // 'html'      =>[ 'span'=>['atributos'=>['class' =>'glyphicon glyphicon-edit']]]
                       // ]
                // ]
        // ];
        // $vista->acciones=['Nuevo'=>['href'=>$this->getUrl('setComponente'),'data-jvista'=>'modal']];
// 
        // $vista->mensajeError = Helpers\Mensajes::mensajeAlerta("No hay registro de componentes <a href=\"".$this->url."set-componente\">Agregar uno</a>");
        // $this->dv->vista = $vista->obtenerVista();
		
		
		
		
		$vista = new Render\JVista('Jida\Modelos\Componente.obtComponentes',[
				'titulos' =>['','Componente','Opciones']
				], 'Componentes');

			$vista->controlFila=1;
			$vista->accionesFila([
					['span'=>'glyphicon glyphicon-folder-open','title'=>"Ver objetos del componente",'href'=>$this->obtUrl('objetos.lista',['comp'=>'{clave}'])],
					['span'=>'glyphicon glyphicon-edit','title'=>"Asignar perfiles de acceso",'href'=>$this->obtUrl('asignarAcceso',['comp'=>'{clave}'])]
				]);

			$vista->addMensajeNoRegistros('No hay Componentes');



			$this->dv->vista = $vista->obtenerVista();
		
    }
    function setComponente(){

        $tipoForm=1;
        $idComponente = "";
        if($this->get('comp')){
            $idComponente = $this->get('comp');
            $tipoForm=2;
        }

         $F = new RenderHTML\Formulario('Componente',$tipoForm,$idComponente,2);
         $F->action=$this->url.'set-componente';
         $F->valueSubmit = "Guardar Componente";

         if($this->post('btnComponente')){
             $this->getEntero($this->post('id_componente'));

			 if($this->validarComponente($this->post('componente'))){
                 $comp = new Modelos\Componente($idComponente);
                 if($F->validarFormulario()){
                     $_POST['componente'] = strtolower($this->post('componente'));
                     if($comp->salvar($_POST)->ejecutado()==1){
						 RenderHTML\Vista::msj('componentes','suceso','Componente <strong>'.$this->post('componente').'</strong> guardado',$this->url.'');
                     }else{
                         RenderHTML\Formulario::msj('error', 'No se pudo registrar el componente');
                     }
                 }
			 }else{
			 	RenderHTML\Formulario::msj('error', 'El componente no existe');
			 }
         }

         $this->dv->fComponente = $F->armarFormulario();
    }


	private function validarComponente($componente){
		if(in_array($componente, $GLOBALS['modulos']))
			return true;
		else
			return false;
	}
    function asignarAcceso($acceso){
Helpers\Debug::imprimir('asignarAcceso',$acceso,$this->get(),true);
        if($this->getEntero($this->get('comp'))){

            $this->vista="accesoPerfiles";
            $form = new RenderHTML\Formulario('PerfilesAComponentes',2,$this->get('comp'),2);
            $comp = new Modelos\Componente($this->getEntero($this->get('comp')));

            $form->action=$this->url."asignar-acceso/comp/".$this->get('comp');
            $form->valueSubmit="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar acceso de perfiles al componente $comp->componente";

            if($this->post('btnPerfilesAComponentes')){
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    $accion = $comp->asignarAccesoPerfiles($this->post('id_perfil'));
                    if($accion['ejecutado']==1){
                        Render\JVista::msj('componentes', 'suceso', 'Asignados los perfiles de acceso al componente '.$comp->componente,$this->urlController());
                    }else
                        RenderHTML\Formulario::msj('error', 'No se pudieron asignar los perfiles, por favor vuelva a intentarlo');
                }else
                    RenderHTML\Formulario::msj('error', 'No se han asignado perfiles');
            }
            $this->dv->formAcceso =$form->armarFormulario();
        }else{
            Render\JVista::msj('componentes', 'error', "Debe seleccionar un componente");
            if(!$this->solicitudAjax())
                redireccionar($this->url);
            else{
                echo Helpers\Mensajes::mensajeError("Debe seleccionar un componente");
            }
        }
    }
}
