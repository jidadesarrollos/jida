<?PHP
/**
*   Controlador de Formularios
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 0.1.7 16/03/2014
 * @edited 0.1.8	30/03/2014
*/


class FormsController extends Controller{
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
        $this->jctrl = new JidaControl();
        $this->url="/jadmin/forms/";
        $this->layout="jadmin.tpl.php";
    }
    function index(){
        
        $this->vista='vistaFormularios';        
        $dataArray['vistaForms'] = $this->mostrarVistaForms();
        $this->data = $dataArray;
        
    }
    /**
     * Vista de Formularios Pertenecientes al Framework
     * @method jidaForms
     * 
     */
    function jidaForms(){
          $conForms = "select id_form,nombre_f as \"Nombre Formulario\", query_f as \"Query\", clave_primaria_f as \"Clave Primaria\",
                    nombre_identificador as \"Identificador\" from s_jida_formularios";
                     
                   #2;3;1;2;1x2;2;1x3;3 
        $vForms = new Vista($conForms,$GLOBALS['PaginadorJida'],"Formularios del Framework");
        
        $vForms->acciones=array(
                            'Nuevoo'=>array('href'=>'/jadmin/forms/gestion-jida-form/','class'=>'btn'),
                            'Modificar'=>array('href'=>'/jadmin/forms/gestion-jida-form/', 'data-jvista'=>'seleccion','class'=>'btn','data-multiple'=>'false'),
                            'Eliminar'=>array('href'=>'/jadmin/forms/gestion-jida-form/','class'=>'btn','data-jvista'=>'seleccion','data-multiple'=>'true'),
                                );
                                
        $vForms->setParametrosVista($GLOBALS['configVista']);
        $vForms->seccionBusqueda=TRUE;
        $vForms->tipoControl=2;
        $bcArray = array(1=>'prueba',2=>'algo',3=>'otra cosa');
        
        $vForms->camposBusqueda=array('nombre_f','query_f','clave_primaria_f');
        $this->data['vista']=$vForms->obtenerVista();
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
        
        
        $conForms = "select id_form,nombre_f as \"Nombre Formulario\", query_f as \"Query\",
                    nombre_identificador as \"Identificador\" from s_formularios";
                     
                    
        $vForms = new Vista($conForms,$GLOBALS['PaginadorJida'],"Formularios");
        
        $vForms->acciones=array(
                            'Nuevoo'=>array('href'=>'/jadmin/forms/gestion-formulario/','class'=>'btn'),
                            'Modificar'=>array('href'=>'/jadmin/forms/gestion-formulario/', 'data-jvista'=>'seleccion','class'=>'btn','data-multiple'=>'false'),
                            'Eliminar'=>array('href'=>'/jadmin/forms/eliminar-formulario/','class'=>'btn','data-jvista'=>'seleccion','data-multiple'=>'true'),
                                );
        $vForms->filaOpciones=array(0=>array('a'=>array('atributos' =>array(
                                                            'class'=>'btn','title'=>'Eliminar Formulario',
                                                            'href'=>"/jadmin/forms/eliminar-formulario/id/{clave}"),
                                                'html'=>array('span'=>array('atributos'=>array('class' => 'glyphicon glyphicon-trash'))))),
                          1=>array('a'=>array(
                                                    'atributos'=>array( 'class'=>'btn',
                                                                        'title'=>'Editar',
                                                                        'href'=>"/jadmin/forms/gestion-formulario/id/{clave}"
                                                                        ),
                                                    'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-edit')))))
                                        );

        $vForms->setParametrosVista($GLOBALS['configVista']);
        $vForms->seccionBusqueda=TRUE;
        $vForms->tipoControl=2;
        $vForms->mensajeError="<p>No hay Registro de formularios</p> <a href=\"/jadmin/forms/gestion-formulario/\">Click Aqu&iacute; si desea registrar uno</a>";
        $vForms->camposBusqueda=array('nombre_f','query_f','clave_primaria_f');
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
		$this->tituloPagina="Registro de Formulario";
		$this->data['totalCampos'] =0;
        $jctrol =  new JidaControl($id_form,$ambito);
        
        if(isset($_GET['id']) and $this->getEntero($_GET['id'])){
            
        	$this->tituloPagina="Modificación de formulario";
    		$this->data['totalCampos'] = $jctrol->obtenerTotalCamposFormulario($_GET['id']);        
            $tipoForm = 2;    
        }else{
            $tipoForm=1;
        }
        
        
		$formulario = new Formulario('Formularios',$tipoForm,$id_form,$ambito);
        if($ambito==2){
            $formulario->action=(isset($_GET['id']) and $this->getEntero($_GET['id']))?$this->url."gestion-jida-form/id/$id_form":$this->url."gestion-jida-form/";
        }else{
            $formulario->action=(isset($_GET['id']) and $this->getEntero($_GET['id']))?$this->url."gestion-formulario/id/$id_form":$this->url."gestion-formulario/";    
        }
        
		if(isset($_POST['btnFormularios'])){
			$validacion = $formulario->validarFormulario($_POST);
			if($validacion===true){
				
		        if($jctrol->validarQuery($_POST['query_f'])===TRUE){		
					if($_POST['btnFormularios']!='Modificar'){
						$_POST['nombre_identificador'] = $this->armarNombreIdentificador($_POST['nombre_f']);
					}
                    
					$guardado = $jctrol->salvar($_POST);
					if($guardado['ejecutado']==1){
						$jctrol->procesarCamposFormulario($guardado);
						Session::set('__msjForm', Mensajes::mensajeInformativo("El formulario <strong> $_POST[nombre_f]</strong> ha sido registrado exitosamente"));
                        if($ambito==2){
                            redireccionar('/jadmin/forms/configuracion-jida-form/formulario/'.$guardado['idResultado']);
                        }else{
                            redireccionar('/jadmin/forms/configuracion-formulario/formulario/'.$guardado['idResultado']);    
                        }
						
					}
                }else{
                    Session::set('__msjForm',Mensajes::mensajeError("El query <strong>$_POST[query_f]</strong> no est&aacute; formulado correctamente"));
                }
			}else{
				Session::set('__msjForm',Mensajes::mensajeError("No se ha podido registrar el formulario"));
			}
		}
        $this->data['formulario'] = $formulario->armarFormulario();
        
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
         Session::set('__msjVista', Mensajes::mensajeSuceso("Se han eliminados los formularios"));
         
         Session::set('__idVista','formularios');
         redireccionar('/jadmin/forms/');
        }else{
            throw new Exception("No se pudo eliminar el formulario", 1);
            
        }
    
        
    }
    private function crearFormularioRegistroForms($update="",$seleccion=""){
            $formulario = new Formulario('Formularios',$update,$seleccion);
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
            $this->data['formFramework']=2;   
        }else{
            $this->data['formFramework']=1;
        }
    	$jctrl = new JidaControl(null,$form);
        
        $this->tituloPagina="Configuracion de Formulario";
        /**
         * Entra aqui al ser enviado un formulario de configuración
         * 
         */
        if(isset($_POST['btnCamposFormulario'])){
            
            $formCampo = $this->getFormCampo($_POST['id_campo'],$form);
            $formCampo->setHtmlEntities=FALSE;
            if($formCampo->validarFormulario()===TRUE){
                
                $proceso = $jctrl->procesarCampos($_POST,$form);
                Session::set('__msj',Mensajes::mensajeSuceso("Campo $_POST[name] ha sido modificado exitosamente"));
            }else{
                Session::set('__msj',Mensajes::mensajeError("No se pudo guardar la configuraci&oacute;n"));                    
            }
          $this->data['formCampo']=$formCampo->armarFormularioEstructura();
        }
		if(isset($_GET['formulario']) and $this->getEntero($_GET['formulario'])>0){
			$jctrl->id_form=$_GET['formulario'];
		}else{
			redireccionar($this->url);
		}
        $camposFormulario = $jctrl->getCamposFormulario();
        //echo $vista;
        $this->data['camposFormulario'] =$camposFormulario;
            
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
            foreach($campos as $campo){
                $idCampo = explode("-", $campo);
                $arrayOrden[]=array('id_campo'=>$idCampo[1],'orden'=>$orden);
                $orden++;
            }
            $this->jctrl->setOrdenCamposForm($arrayOrden,$form="");
            $msj = Mensajes::mensajeSuceso("Se ha guardado el orden del formulario");
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
        $form = new Formulario ( 'CamposFormulario',2,$idCampo,2 );
        if($tipoForm==2){
            $form->query_f="select id_campo, id_form, label, name, maxlength, size,
                            eventos, 1 clave_evento, 2 valor_evento, control,  opciones, orden, id_propiedad, placeholder,
                            class, data_atributo, title, visibilidad from s_jida_campos_f";
        }
        
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
       $campo = new CampoHTML();
        $this->layout="ajax.tpl.php";
        
        if(isset($_POST['idCampo'])){
            $idCampo = $_POST['idCampo'];
            
            $form=$this->getFormCampo($idCampo,$_POST['form']);
            
            $this->data['formCampo'] = $form->armarFormularioEstructura();
        }else{
            throw new Exception("No se ha obtenido el id del campo", 1);
            
        }
    }

}

?>
 