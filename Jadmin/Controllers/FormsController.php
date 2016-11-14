<?PHP
/**
*   Controlador de Formularios
 *
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 0.1.7 16/03/2014
 * @edited 0.1.8	30/03/2014
 * @update 0.1.9   	13/11/2016
*/

namespace Jida\Jadmin\Controllers;
use Exception;
use Jida\Modelos as Modelos;
use Jida\Helpers as Helpers;
use Jida\RenderHTML as RenderHTML;
class FormsController extends JController{
    /**
     * objeto modelo jidaControl
     * @access private
     * @var object $jctrl
     */
    private $jctrl;

    /**
     * Funcion constructora
     */
    function __construct(){

        $this->jctrl = new Modelos\JidaControl();
        $this->url="/jadmin/forms/";
        parent::__construct();

        $this->layout="jadmin.tpl.php";
    }
    function index(){

        $this->vista='vistaFormularios';
        $this->dv->vistaForms = $this->mostrarVistaForms();

    }
    /**
     * Vista de Formularios Pertenecientes al Framework
     * @method jidaForms
     *
     */
    function jidaForms(){

        $conForms = "select id_form,nombre_f as \"Nombre Formulario\",clave_primaria_f as \"Clave Primaria\",
                    nombre_identificador as \"Identificador\" from s_jida_formularios";

        $vForms = new RenderHTML\Vista($conForms,$GLOBALS['PaginadorJida'],"Formularios del Framework");

        $vForms->acciones=[
        'Nuevoo'=>['href'=>'/jadmin/forms/gestion-jida-form/','class'=>'btn'],
        'Modificar'=>['href'=>'/jadmin/forms/gestion-jida-form/',
        'data-jvista'=>'seleccion','class'=>'btn','data-multiple'=>'false'],
        'Eliminar'=>['href'=>'/jadmin/forms/gestion-jida-form/','class'=>'btn','data-jvista'=>'seleccion','data-multiple'=>'true'],
        ];
         $vForms->filaOpciones=
        [0=>['a'=>['atributos' =>[
            'class'=>'btn','title'=>'Eliminar Formulario',
            'href'=>"/jadmin/forms/eliminar-formulario/id/{clave}"],
            'html'=>['span'=>['atributos'=>['class' => 'glyphicon glyphicon-trash']]]]],

        1=>['a'=>[
            'atributos'=>[ 'class'=>'btn',
            'title'=>'Editar',
            'href'=>"/jadmin/forms/gestion-jida-form/id/{clave}"],
            'html'=>['span'=>['atributos'=>['class' =>'glyphicon glyphicon-edit']]]]]
        ];
        $vForms->setParametrosVista($GLOBALS['configVista']);
        $vForms->seccionBusqueda=TRUE;
        $vForms->tipoControl=2;
        $bcArray = [1=>'prueba',2=>'algo',3=>'otra cosa'];

        $vForms->camposBusqueda=['nombre_f','query_f','clave_primaria_f'];
        $this->dv->vista=$vForms->obtenerVista();
    }
    /**
     * Muestra formulario para registro o edición de Formularios propios del Framework
     *
     * @method gestionJidaForm
     *
     */
    function gestionJidaForm(){
        $this->vista="gestionFormulario";
        $this->gestionFormulario(2);
    }
    /**
     * Estructura el grid para visualización de los Formularios registrados
     * @method mostrarVistaForms
     */
    private function mostrarVistaForms(){


        $conForms = "select id_form,nombre_f as \"Nombre Formulario\",
                    nombre_identificador as \"Identificador\" from s_formularios";


        $vForms = new RenderHTML\Vista($conForms,$GLOBALS['PaginadorJida'],"Formularios");

        $vForms->acciones=[
        'Nuevoo'=>['href'=>$this->getUrl('gestionFormulario'),'class'=>'btn btn-adm'],
        'Modificar'=>[
            'href'=>$this->getUrl('gestionFormulario'),#'/jadmin/forms/gestion-formulario/',
            'data-jvista'=>'seleccion',
            'data-jkey'=>'id',
            'class'=>'btn btn-default','data-multiple'=>'false'],
        'Eliminar'=>[
            'href'=>$this->getUrl('eliminarFormulario'),
            'class'=>'btn btn-default','data-jvista'=>'seleccion','data-multiple'=>'true'],
        ];
        $vForms->filaOpciones=
        [0=>['a'=>['atributos' =>[
            'class'=>'btn','title'=>'Eliminar Formulario',
            'href'=>"/jadmin/forms/eliminar-formulario/id/{clave}"],
            'html'=>['span'=>['atributos'=>['class' => 'glyphicon glyphicon-trash']]]]],

        1=>['a'=>[
            'atributos'=>[ 'class'=>'btn',
            'title'=>'Editar',
            'href'=>"/jadmin/forms/gestion-formulario/id/{clave}"],
            'html'=>['span'=>['atributos'=>['class' =>'glyphicon glyphicon-edit']]]]]
        ];

        $vForms->setParametrosVista($GLOBALS['configVista']);
        $vForms->seccionBusqueda=TRUE;
        $vForms->tipoControl=2;
        $vForms->mensajeError="<p>No hay Registro de formularios</p> <a href=\"/jadmin/forms/gestion-formulario/\">Click Aqu&iacute; si desea registrar uno</a>";
        $vForms->camposBusqueda=['nombre_f','query_f','clave_primaria_f'];
        return $vForms->obtenerVista();
    }
   	/**
	 * Permite registrar o modificar formularios
	 * @method gestionFormulario
	 * @access public
	 */
    function gestionFormulario($ambito=1){


        $tipoForm = 1;
        $id_form=(isset($_GET['id']) and $this->getEntero($_GET['id']))?$_GET['id']:"";
		$this->dv->title="Registro de Formulario";
		$this->dv->totalCampos =0;

        $jctrol =  new Modelos\JidaControl($id_form,$ambito);

        if($this->getEntero($this->get('id'))){

        	$this->tituloPagina="Modificación de formulario";
    		$this->dv->totalCampos = $jctrol->obtenerTotalCamposFormulario($_GET['id']);
            $tipoForm = 2;
        }else{
            $tipoForm=1;
        }


		$formulario = new RenderHTML\Formulario('Formularios',$tipoForm,$id_form,$ambito);
        if($ambito==2){
            $formulario->action=(isset($_GET['id']) and $this->getEntero($_GET['id']))?$this->getUrl('gestionJidaForm',['id'=>$id_form]):$this->getUrl('gestionJidaForm');
        }else{
            $formulario->action=(isset($_GET['id']) and $this->getEntero($_GET['id']))?$this->getUrl('gestionFormulario',['id'=>$id_form]):$this->getUrl('gestionFormulario');
        }

		if(isset($_POST['btnFormularios'])){
			$validacion = $formulario->validarFormulario($_POST);
			if($validacion===true){

		        if($jctrol->validarQuery($_POST['query_f'])===TRUE){
		            $_POST['query_f'] = addslashes($_POST['query_f']);
					if($_POST['btnFormularios']!='Modificar'){
						$_POST['nombre_identificador'] = $this->armarNombreIdentificador($_POST['nombre_f']);
					}

					$guardado = $jctrol->salvar($_POST);
                    $jctrol->query_f=stripslashes($jctrol->query_f);

					if($guardado['ejecutado']==1){
						$jctrol->procesarCamposFormulario($guardado);
						RenderHTML\Formulario::msj('suceso', "El formulario <strong> $_POST[nombre_f]</strong> ha sido registrado exitosamente");

                        if($ambito==2){
                            redireccionar('/jadmin/forms/configuracion-jida-form/formulario/'.$guardado['idResultado']);
                        }else{
                            redireccionar('/jadmin/forms/configuracion-formulario/formulario/'.$guardado['idResultado']);
                        }

					}
                }else{

                    Helpers\Sesion::set('__msjForm',Helpers\Mensajes::mensajeError("El query <strong>$_POST[query_f]</strong> no est&aacute; formulado correctamente"));
                }
			}else{
				Helpers\Sesion::set('__msjForm',Helpers\Mensajes::mensajeError("No se ha podido registrar el formulario"));
			}
		}
        $this->dv->formulario = $formulario->armarFormulario();


     }
	 /**
	  * Arma el nombre identificador de un formulario
	  * @method armarNombreIdentificador
	  * @access private
	  */

	private function armarNombreIdentificador($nombre){
    	$nombreIdentificador = ucwords(strtolower($nombre));
		$nombreIdentificador = str_replace(" ", "", $nombreIdentificador);
		return $nombreIdentificador;

    }
 	/**
	 * Verifica los campos de un formulario y los actualiza
	 *
	 * Si el formulario es nuevo realiza una inserción inicial de los campos, si ya existe valida
	 * si hay campos nuevos para agregarlos o si se debe eliminar alguno
	 * @method validarCamposFormulario
	 * @access private
	 * @param array Accion Arreglo resultado de gestion de formulario (result dbContainer)
	 */
	private function validarCamposFormulario($accion){

	 }
    function eliminarFormulario(){

        $ids = $_GET['id'];
        $arrayIds = explode(",",$ids);
        foreach($arrayIds as $key=>$id){
            $arrayIds[$key] = $this->getEntero($id);
        }
        if($this->jctrl->eliminarFormulario($arrayIds)){
         Helpers\Sesion::set('__msjVista', Helpers\Mensajes::mensajeSuceso("Se han eliminados los formularios"));

         Helpers\Sesion::set('__idVista','formularios');
         redireccionar('/jadmin/forms/');
        }else{
            throw new Exception("No se pudo eliminar el formulario", 1);

        }


    }
    private function crearFormularioRegistroForms($update="",$seleccion=""){
            $formulario = new RenderHTML\Formulario('Formularios',$update,$seleccion);
            $formulario->action="/jadmin/forms/configuracion-formulario";
            $form = $formulario->armarFormulario('Formularios');
            return $form;
    }
    /**
     * Configurar un Formulario de Framework
     *
     * Hace uso interno de la funcion configuracionFormulario
     * @see configuracionFormulario
     */
    function configuracionJidaForm(){
        $this->vista='configuracionFormulario';
        $this->configuracionFormulario(2);
    }
    /**
     * Permite realizar las configuraciones para los campos de un formulario
     * @method configuracionFormulario
     * @param int $tabla Si el parametro es pasado se buscará editar un formulario perteneciente a la tabla
     * s_jida_formularios.
     */
    function configuracionFormulario($form=1){

        if($form==2){
            $this->dv->formFramework=2;
        }else{
            $this->dv->formFramework=1;
        }
    	$jctrl = new Modelos\JidaControl(null,$form);

        $this->tituloPagina="Configuracion de Formulario";
        /**
         * Entra aqui al ser enviado un formulario de configuración
         *
         */
        if($this->post('btnCamposFormulario')){

            $formCampo = $this->getFormCampo($_POST['id_campo'],$form);
            $formCampo->setHtmlEntities=FALSE;
            if($formCampo->validarFormulario()===TRUE){

                $proceso = $jctrl->procesarCampos($_POST,$form);
                Helpers\Sesion::set('__msj',Helpers\Mensajes::mensajeSuceso("Campo $_POST[name] ha sido modificado exitosamente"));
            }else{
                Helpers\Sesion::set('__msj',Helpers\Mensajes::mensajeError("No se pudo guardar la configuraci&oacute;n"));
            }
          $this->dv->formCampo=$formCampo->armarFormularioEstructura();
        }
		if($this->getEntero($this->get('formulario'))){
			$jctrl->id_form=$this->get('formulario');
		}else{
			$this->redireccionar($this->url);
		}

        $camposFormulario = $jctrl->getCamposFormulario();
        //echo $vista;
        $this->dv->camposFormulario =$camposFormulario;

    }
    /**
     * Ordena los campos a partir del orden pasado via post
     * @method ordenarCampos
     */
    function ordernarCampos(){

        if(isset($_POST['s-ajax'])){
            $campos = explode(",", $_POST['campos']);
            $orden = 1;
            $arrayOrden=array();
            $jctrl = new Modelos\JidaControl(null,$_POST['ambito']);
            foreach($campos as $campo){
                $idCampo = explode("-", $campo);
                $arrayOrden[]=array('id_campo'=>$idCampo[1],'orden'=>$orden);
                $orden++;
            }

            $jctrl->setOrdenCamposForm($arrayOrden,$form="");
            $msj = Helpers\Mensajes::mensajeSuceso("Se ha guardado el orden del formulario");
            respuestaAjax(json_encode(array("ejecutado"=>TRUE,'msj'=>$msj)));
        }
    }

    /**
     * Arma el formulario de un campo HTML
     * @method getFormCampo
     * @param int $idCampo Identificador del Campo en caso de edicion
     * @param int $tipoForm Tipo del Formulario a editar : 1 Aplicación, 2 Framework;
     */
    private function getFormCampo($idCampo="",$tipoForm=1){

        $form = new RenderHTML\Formulario ( 'CamposFormulario',2,$idCampo,$tipoForm );
        $form->action = "#";
        $form->tipoBoton = "submit";
        $form->valueBotonForm="Guardar Configuración";
        return $form;
    }
    /**
     * Formulario para configuración del campo del formulario
     * @method configuracionCampo
     */

    function configuracionCampo(){
       $campo = new RenderHTML\CampoHTML();

        $this->layout="ajax.tpl.php";

        if($this->post('idCampo')){
            $idCampo = $_POST['idCampo'];

            $form=$this->getFormCampo($idCampo,$_POST['form']);

            $this->dv->formCampo = $form->armarFormularioEstructura();

        }else{
            throw new Exception("No se ha obtenido el id del campo", 1);

        }
    }

}