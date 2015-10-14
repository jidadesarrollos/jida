<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version 0.1
 */

 
class ComponentesController extends JController{
    
    function __construct($id=""){
        parent::__construct();        
        $this->url="/jadmin/componentes/";
        $this->layout="jadmin.tpl.php";
        $this->dv->title="Componentes de ".TITULO_SISTEMA;
		#Debug::mostrarArray($this->usuario);        
        
    }
    function index(){
        
        $this->vista="vistaComponentes";
        $query = "select id_componente, Componente as \"Componente\" from s_componentes";
        $vista = new Vista($query,$GLOBALS['configPaginador'],'Componentes');
		$vista->setParametrosVista($GLOBALS['configVista']);

        $vista->filaOpciones=[
        0=>['a'=>['atributos'   =>[
                                    'class'         =>'btn','title'=>'Ver objetos del componente',
                                    'href'          =>$this->getUrl(['jadmin/objetos'=>'lista'],['comp'=>'{clave}']),
                                    //'data-jvista'   =>'modal'
                                  ],
                    'html'      =>[ 'span'=>['atributos'=>['class' => 'glyphicon glyphicon-folder-open']]]
                 ]
             ],
          1=>['a'   =>[ 'atributos' =>[ 'class' =>'btn',
                                        'title' =>'Asignar perfiles de acceso',
                                        'href'  =>"/jadmin/componentes/asignar-acceso/comp/{clave}",
                                        'data-jvista'=>'modal'
                                    ],
                        'html'      =>[ 'span'=>['atributos'=>['class' =>'glyphicon glyphicon-edit']]]
                       ]
                ]
        ];
        $vista->acciones=['Nuevo'=>['href'=>$this->getUrl('setComponente'),'data-jvista'=>'modal']];

        $vista->mensajeError = Mensajes::mensajeAlerta("No hay registro de componentes <a href=\"".$this->url."set-componente\">Agregar uno</a>");
        $this->dv->vista = $vista->obtenerVista();
    }
    function setComponente(){
        
        $tipoForm=1;
        $idComponente = "";
        if($this->get('comp')){
            $idComponente = $this->get('comp');
            $tipoForm=2;
        }

         $F = new Formulario('Componente',$tipoForm,$idComponente,2);
         $F->action=$this->url.'set-componente';
         $F->valueSubmit = "Guardar Componente";
         
         if($this->post('btnComponente')){
             $this->getEntero($this->post('id_componente'));
			 
			 if($this->validarComponente($this->post('componente'))){
                 $comp = new Componente($idComponente);
                 $validacion = $F->validarFormulario($_POST);
                 
                 if($validacion===TRUE){
                     $_POST['componente'] = strtolower($this->post('componente'));
                     $guardado  = $comp->guardarComponente($_POST);
                     if($guardado['ejecutado']==1){
                         Session::set('__idVista', 'componentes');
                         Session::set('__msjVista',Mensajes::mensajeSuceso('Componente <strong>'.$this->post('componente').'</strong> guardado'));
                         redireccionar($this->url."");    
                     }else{
                         Session::set('__msjForm', Mensajes::mensajeError('No se pudo registrar el componente'));
                     }
                     
                 }
			 }else{
		         Session::set('__msjForm', Mensajes::mensajeError('El componente no existe'));	 	
			 }   
         }

         $this->dv->fComponente = $F->armarFormulario();
    }


	private function validarComponente($componente){
		if(in_array($componente, $GLOBALS['modulos'])){
			return true;
		}else{
			return false;
		}
	}
    function asignarAcceso(){
        
        if($this->getEntero($this->get('comp'))){
                        
            $this->vista="accesoPerfiles";
            $form = new Formulario('PerfilesAComponentes',2,$this->get('comp'),2);
            $comp = new Componente($this->getEntero($this->get('comp')));
            
            $form->action=$this->url."asignar-acceso/comp/".$this->get('comp');
            $form->valueSubmit="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar acceso de perfiles al componente $comp->componente";
            if($this->post('btnPerfilesAComponentes')){
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    
                    $accion = $comp->asignarAccesoPerfiles($this->post('id_perfil'));
                    if($accion['ejecutado']==1){
                        Vista::msj('componentes', 'suceso', 'Asignados los perfiles de acceso al componente '.$comp->componente,$this->urlController());
                        
                    }else{
                        
                        $msj = Mensajes::mensajeError("No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                        Session::set('__msjForm', $msj);
                    }
                }else{
                    
                    Session::set('__msjForm',Mensajes::mensajeError("No se han asignado perfiles"));
                }
            }

            $this->dv->formAcceso =$form->armarFormulario();
        }else{
            
            Vista::msj('componentes', 'error', "Debe seleccionar un componente");
            if(!$this->solicitudAjax())
                redireccionar($this->url);
            else{
                echo Mensajes::mensajeError("Debe seleccionar un componente");
            }  
        }
    
    }
}


