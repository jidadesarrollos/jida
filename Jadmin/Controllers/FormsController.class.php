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
        $this->header='jadminDefault/header.php';
        $this->footer='jadminDefault/footer.php';
    }
    function index(){
        
        $this->vista='vistaFormularios';        
        $dataArray['vistaForms'] = $this->mostrarVistaForms();
        $this->data = $dataArray;
        
    }
    
     private function mostrarVistaForms(){
        $conForms = "select id_form,nombre_f as \"Nombre Formulario\", query_f as \"Query\", clave_primaria_f as \"Clave Primaria\",
                    nombre_identificador as \"Identificador\" from s_formularios
                    ";
        
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
	            $dataArray['formCampo'] = $campoHtml->formCampo(2,$_POST['id_campo'],$proceso);            
	            if($proceso!==true and is_array($proceso)){
	                $dataArray['erroresForm'] = $proceso;
	                #Se devuelve el formulario con los errores obtenidos
	                
	            }else{
	                $_SESSION['__msj'] = Mensajes::mensajeInformativo("Campo $_POST[name] ha sido modificado exitosamente");
	            }//fin if
	        }
			if(isset($_GET['formulario']) and $this->getEntero($_GET['formulario'])>0){
				$jctrl->id_form=$_GET['formulario'];
			}else{
				redireccionar($this->url);
			}
	        $vista = $jctrl->vistaCamposFormulario();
	        //echo $vista;
	        $dataArray['vistaCampos'] = $vista;
	        $this->data = $dataArray;
    	}catch(Exception $e){
    		Excepcion::controlExcepcion($e);
    	}
    }




    function configuracionCampo(){
       $campo = new CampoHTML();
       $this->vista = "configuracionFormulario";
       try{
            if(isset($_POST['idCampo'])){
                $idCampo = $_POST['idCampo'];
                $dataArray['formCampo'] = $campo->formCampo($_POST['accion'],$idCampo);    
            }else{
                throw new Exception("No se ha obtenido el id del campo", 1);
                
            }
            
            respuestaAjax($dataArray['formCampo']);
                
       }catch(Exception $e){
           controlExcepcion($e->getMessage());
       }
       
    }

}

?>
 