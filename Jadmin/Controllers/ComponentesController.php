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
use Jida\RenderHTML as RenderHTML;
use Jida\Helpers as Helpers;
use Jida\Modelos\Viejos as Modelos;
class ComponentesController extends JController{
    var $layout="jadmin.tpl.php";
    function __construct($id=""){
        parent::__construct();
        // $this->manejoParams=TRUE;
        $this->url="/jadmin/componentes/";

        $this->dv->title="Componentes de ".TITULO_SISTEMA;

    }
    function index(){

        $this->vista="vistaComponentes";


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
    function setComponente($idComponente=""){

        $tipoForm=1;

        if(!empty($idComponente)) $tipoForm=2;

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
			 }else	RenderHTML\Formulario::msj('error', 'El componente no existe');

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
		Helpers\Debug::imprimir($this->get(),true);
        if($this->getEntero($acceso)){

            $this->vista="accesoPerfiles";
            $form = new RenderHTML\Formulario('PerfilesAComponentes',2,$acceso,2);
            $comp = new Modelos\Componente($acceso);

            $form->action=$this->url."asignar-acceso/".$acceso;
            $form->valueSubmit="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar acceso de perfiles al componente $comp->componente";

            if($this->post('btnPerfilesAComponentes')){

                if($form->validarFormulario($this->post())){

                    if($comp->asignarAccesoPerfiles($this->post('id_perfil'))->ejecutado()){
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
