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
    
    private function mostrarVistaForms(){
        
        if(isset($_GET['filter']) and $_GET['filter']=='jida'){
        $conForms = "select id_form,nombre_f as \"Nombre Formulario\", query_f as \"Query\", clave_primaria_f as \"Clave Primaria\",
                    nombre_identificador as \"Identificador\" from s_formularios where id_form < 20 
                    ";    
        }else{
            
        $conForms = "select id_form,nombre_f as \"Nombre Formulario\", query_f as \"Query\", clave_primaria_f as \"Clave Primaria\",
                    nombre_identificador as \"Identificador\" from s_formularios where id_form >20
                    ";    
        }
        $vForms = new Vista($conForms,$GLOBALS['PaginadorJida'],"Formularios");
        
        $vForms->acciones=array(
                            'Nuevoo'=>array('href'=>'/jadmin/forms/gestion-formulario/','class'=>'btn'),
                            'Modificar'=>array('href'=>'/jadmin/forms/gestion-formulario/', 'data-jvista'=>'seleccion','class'=>'btn','data-multiple'=>'false'),
                            'Eliminar'=>array('href'=>'/jadmin/forms/eliminar-formulario/','class'=>'btn','data-jvista'=>'seleccion','data-multiple'=>'true'),
                                );
        $vForms->setParametrosVista($GLOBALS['configVista']);
        $vForms->seccionBusqueda=TRUE;
        $vForms->tipoControl=2;
        $bcArray = array(1=>'prueba',2=>'algo',3=>'otra cosa');
        
        $vForms->camposBusqueda=array('nombre_f','query_f','clave_primaria_f');
        return $vForms->obtenerVista();
    }
   	/**
	 * Permite registrar o modificar formularios
	 * @method gestionFormulario
	 * @access public
	 */
    function gestionFormulario(){
        try{
            $tipoForm = 1;
            $id_form="";
			$this->tituloPagina="Registro de Formulario";
			$this->data['totalCampos'] =0;
            if(isset($_GET['id'])){
                if($this->getEntero($_GET['id'])){
                	$this->tituloPagina="Modificación de formulario";
                    $id_form = $this->getEntero($_GET['id']);
            		$this->data['totalCampos'] = $this->jctrl->obtenerTotalCamposFormulario($_GET['id']);        
                    $tipoForm = 2;    
                }else{
                    $tipoForm=1;
                }
            }
            
			$formulario = new Formulario('Formularios',$tipoForm,$id_form);
			$formulario->action=(isset($_GET['id']) and $this->getEntero($_GET['id']))?$this->url."gestion-formulario/id/$id_form":$this->url."gestion-formulario/";
            
			if(isset($_POST['btnFormularios'])){
				$validacion = $formulario->validarFormulario($_POST);
				if($validacion===true){
					$jctrol =  new JidaControl($id_form);
			        if($jctrol->validarQuery($_POST['query_f'])===TRUE){		
    					if($_POST['btnFormularios']!='Modificar'){
    						$_POST['nombre_identificador'] = $this->armarNombreIdentificador($_POST['nombre_f']);
    					}
    					
    					
    					$guardado = $jctrol->salvar($_POST);
    					if($guardado['ejecutado']==1){
    						$jctrol->procesarCamposFormulario($guardado);
    						Session::set('__msjForm', Mensajes::mensajeInformativo("El formulario <strong> $_POST[nombre_f]</strong> ha sido registrado exitosamente"));
    						redireccionar('/jadmin/forms/configuracion-formulario/formulario/'.$guardado['idResultado']);
    					}
                    }else{
                        Session::set('__msjForm',Mensajes::mensajeError("El query <strong>$_POST[query_f]</strong> no est&aacute; formulado correctamente"));
                    }
				}else{
					Session::set('__msjForm',Mensajes::mensajeError("No se ha podido registrar el formulario"));
				}
			}
            //$formulario = $this->crearFormularioRegistroForms($tipoForm,$id_form);
            $this->data['formulario'] = $formulario->armarFormulario();
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
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
        try{
            $ids = $_GET['id'];
            $arrayIds = explode(",",$ids);
            foreach($arrayIds as $key=>$id){
                $arrayIds[$key] = $this->getEntero($id);   
            }
            if($this->jctrl->eliminarFormulario($arrayIds)){
             Session::set('__msj', Mensajes::mensajeSuceso("se han eliminados los formularios"));
             sleep(1);
             redireccionar('/jadmin/forms/');
            }else{
                throw new Exception("No se pudo eliminar el formulario", 1);
                
            }
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
        
    }
    private function crearFormularioRegistroForms($update="",$seleccion=""){
            $formulario = new Formulario('Formularios',$update,$seleccion);
            $formulario->action="/jadmin/forms/configuracion-formulario";
            $form = $formulario->armarFormulario('Formularios');
            return $form;
    }
    
    function configuracionFormulario(){
        try{
        	$jctrl = new JidaControl();
	        $campoHtml = new CampoHTML();
	        $this->tituloPagina="Configuracion de Formulario";
	        
	        if(isset($_POST['btnCamposFormulario'])){
	            $proceso = $jctrl->procesarCampos($_POST);
                $formCampo = $this->getFormCampo($_POST['id_campo']);
                if($formCampo->validarFormulario()===TRUE){
                    $_SESSION['__msj'] = Mensajes::mensajeSuceso("Campo $_POST[name] ha sido modificado exitosamente");
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
	        
    	}catch(Exception $e){
    		Excepcion::controlExcepcion($e);
    	}
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
     */
    private function getFormCampo($idCampo=""){
        $form = new Formulario ( 'CamposFormulario',2,$idCampo );
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
            $form=$this->getFormCampo($idCampo);
            
            $this->data['formCampo'] = $form->armarFormularioEstructura();
        }else{
            throw new Exception("No se ha obtenido el id del campo", 1);
            
        }
    }

}

?>
 