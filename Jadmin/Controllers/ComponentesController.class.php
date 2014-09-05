<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version 0.1
 */

 
class ComponentesController extends Controller{
    
    function __construct($id=""){
        #Arrays::mostrarArray($_SERVER);exit;
        $this->url="/jadmin/componentes/";
        $this->layout="jadmin.tpl.php";        
        
    }
    function index(){
        $this->vista="vistaComponentes";
        $query = "select id_componente, Componente as \"Componente\" from s_componentes";
        $vista = new Vista($query,$GLOBALS['configPaginador'],'Componentes');
		$vista->setParametrosVista($GLOBALS['configVista']);
        $vista->acciones=array(
                                'Registrar Componente'=>array('href'=>'/jadmin/componentes/set-componente'),
                                'Modificar Componente'=>array('href'=>'/jadmin/componentes/set-componente',
                                                                'data-jvista'=>'seleccion',
                                                                'data-multiple'=>'true','data-jkey'=>'comp'),
                                );
        $vista->filaOpciones=array(0=>array('a'=>array('atributos' =>array(
                                                                    'class'=>'btn','title'=>'Ver objetos del componente',
                                                                    'href'=>"/jadmin/objetos/lista/comp/{clave}"),
                                                        'html'=>array('span'=>array('atributos'=>array('class' => 'glyphicon glyphicon-folder-open'))))),
                                  1=>array('a'=>array(
                                                            'atributos'=>array( 'class'=>'btn',
                                                                                'title'=>'Asignar perfiles de acceso',
                                                                                'href'=>"/jadmin/componentes/asignar-acceso/comp/{clave}"
                                                                                ),
                                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-edit')))))
                                                );
                                
		
        $vista->mensajeError = Mensajes::mensajeAlerta("No hay registro de componentes <a href=\"".$this->url."set-componente\">Agregar uno</a>");
        $this->data['vista'] = $vista->obtenerVista();
    }
    function setComponente(){
        
        $tipoForm=1;
        $idComponente = "";
        if(Globals::obtGet('comp')){
            $idComponente = Globals::obtGet('comp');
            $tipoForm=2;
        }

         $F = new Formulario('Componente',$tipoForm,$idComponente,2);
         $F->action=$this->url.'set-componente';
         $F->valueSubmit = "Guardar Componente";
         
         if(Globals::obtPost('btnComponente')){
             $this->getEntero(Globals::obtPost('id_componente'));
			 
			 if($this->validarComponente(Globals::obtPost('componente'))){
                 $comp = new Componente($idComponente);
                 $validacion = $F->validarFormulario($_POST);
                 if($validacion===TRUE){
                     $guardado  = $comp->guardarComponente($_POST);
                     if($guardado['ejecutado']==1){
                         Session::set('__idVista', 'componentes');
                         Session::set('__msjVista',Mensajes::mensajeSuceso('Componente <strong>'.Globals::obtPost('componente').'</strong> guardado'));
                         redireccionar($this->url."");    
                     }else{
                         Session::set('__msjForm', Mensajes::mensajeError('No se pudo registrar el componente'));
                     }
                     
                 }
			 }else{
		         Session::set('__msjForm', Mensajes::mensajeError('El componente no existe'));	 	
			 }   
         }

         $this->data['fComponente'] = $F->armarFormulario();
    }


	private function validarComponente($componente){
		if(in_array($componente, $GLOBALS['modulos'])){
			return true;
		}else{
			return false;
		}
	}
    function asignarAcceso(){
        
        if(isset($_GET['comp']) and $this->getEntero($_GET['comp'])!=""){
                        
            $this->vista="accesoPerfiles";
            $form = new Formulario('PerfilesAComponentes',2,Globals::obtGet('comp'),2);
            $comp = new Componente($this->getEntero(Globals::obtGet('comp')));
            
            $form->action=$this->url."asignar-acceso/comp/".Globals::obtGet('comp');
            $form->valueSubmit="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar acceso de perfiles al componente $comp->componente";
            if(isset($_POST['btnPerfilesAComponentes'])){
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    
                    $accion = $comp->asignarAccesoPerfiles(Globals::obtPost('id_perfil'));
                    if($accion['ejecutado']==1){
                        Session::set('__idVista', 'componentes');
                        $msj = Mensajes::mensajeSuceso('Asignados los perfiles de acceso al componente '.$comp->componente);
                        Session::set('__msjVista',$msj);
                        redireccionar($this->url);
                    }else{
                    
                        $msj = Mensajes::mensajeError("No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                        Session::set('__msjForm', $msj);
                    }
                }else{
                    
                    Session::set('__msjForm',Mensajes::mensajeError("No se han asignado perfiles"));
                }
            }
            $this->data['formAcceso'] =$form->armarFormulario();
        }else{
            Session::set('__msjVista',Mensajes::mensajeError("Debe seleccionar un objeto"));
            Session::set('__idVista','componentes');
            redireccionar($this->url);  
        }
    
    }
}


?>