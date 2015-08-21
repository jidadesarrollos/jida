<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version 0.1
 */

 
class PerfilesController extends Controller{
    
	/**
	 * Funcion constructora
	 */
    function __construct($id=""){
        parent::__construct();
		$this->url="/jadmin/perfiles/";
        $this->layout="jadmin.tpl.php";
        
    }
	
	function index(){
	    
		$this->tituloPagina = "Lista de Perfiles";
		$this->vista="vistaPerfiles";
		$qVista = "select id_perfil,perfil  \"Perfil\" from s_perfiles";
		$vista = new Vista($qVista,$GLOBALS['configPaginador'],'Perfiles');
		$vista->setParametrosVista($GLOBALS['configVista']);
		$vista->tipoControl=2;
		$vista->acciones=[
            'Registrar'=>['href'=>$this->url.'set-perfiles'],
            'Modificar'=>['href'=>$this->url.'set-perfiles',
                                            'data-jvista'=>'seleccion',
                                            'data-multiple'=>'true','data-jkey'=>'perfil'],
            'Eliminar'=>['href'=>$this->url.'eliminar',
                                            'data-jvista'=>'seleccion',
                                            'data-multiple'=>'true','data-jkey'=>'perfil'],
                                                                            
                                ];
		$this->dv->vistaPerfiles = $vista->obtenerVista();
	}
	
	/**
     * Procesar un perfil
     * @method process
     */
	function setPerfiles(){
	    
	    $pk="";$tipoForm=1;
        if(isset($_GET['id']) and $this->getEntero($_GET['id'])){
            $pk=$_GET['id'];$tipoForm=2;
        }
        
        $form=new Formulario('Perfiles',$tipoForm,$pk,2);
        $form->action=$this->url."set-perfiles/";
        $form->tituloFormulario="Gesti&oacute;n de Perfiles";
        if(isset($_POST['btnPerfiles'])){
            $msj = 'No se ha podido registrar el perfil, vuelva a intenarlo';
            $validacion = $form->validarFormulario();
            if($validacion===TRUE){
                $perfil = New Perfil($pk);
                $_POST['clave_perfil'] = String::upperCamelCase($_POST['perfil']);
                #Debug::mostrarArray($_POST);
                $guardado = $perfil->salvar($_POST);
                if($guardado['ejecutado']){
                    $msj = "El perfil <strong>$perfil->perfil</strong> ha sido registrado exitosamente";
                    Vista::msj('perfiles', 'suceso', $msj,'/jadmin/perfiles/');
                }else{
                    if($guardado['unico']==1){
                        $msj = "El perfil <strong>$_POST[nombre_perfil]</strong> ya se encuentra registrado";
                    }
                }
            }
            Formulario::msj('error', $msj);            
        }
        $this->data['form']=$form->armarFormulario();
	}//final funcion
	
	function eliminar(){
	            
        if($this->get('perfil')){
            $total = explode(",",$this->get('perfil'));
            $perfil = new Perfil();
            
            if(count($total)==1 and $this->get('perfil')>0){    
                $perfil->eliminarObjeto($this->get('perfil'));
                $msj = "Perfil eliminado exitosamente";
                Vista::msj('perfiles', 'error',$msj,$this->urlController());
            }else{
                $noNumerico = false;
                foreach ($total as $key => $value) {
                    if($this->getEntero($value)==0){      
                        $noNumerico=TRUE;
                    }
                    
                }
                if($noNumerico!==true){
                    $perfil->eliminarMultiplesDatos($total, 'id_perfil');
                    $msj = "Perfiles eliminados exitosamente";
                    Vista::msj('perfiles', 'error',$msj,$this->urlController());
                }else{
                    $msj = "No se ha logro eliminar el perfil, porfavor vuelva a intentarlo";
                    Vista::msj('perfiles', 'error',$msj,$this->urlController());        
                }
            }              
        }else{
            $msj = "No se ha podido realizar la acci&oacute;n vuelva a intentarlo";
            Vista::msj('perfiles', 'error',$msj,$this->urlController());    
        }
        
        
    }//fin eliminarCategorias
	
}


