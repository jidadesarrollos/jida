<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version 0.1
 */

 
class MetodosController	 extends Controller{
    
    function __construct($id=""){
        
        $this->layout="jadmin.tpl.php";
        $this->url="/jadmin/metodos/";
        parent::__construct();
    }
    
    /**
     * Funcion controladora de metodos de un objeto
     */
    function metodosObjeto($url=""){
        $url = (empty($url))?$this->url:$url;
        
        if(isset($_GET['obj'])){
            $objeto = new Objeto($this->getEntero(Globals::obtGet('obj')));
            
            $this->tituloPagina="Objeto $objeto->objeto - Metodos";
            $nombreClase = $objeto->objeto."Controller";
            $clase = new ReflectionClass($nombreClase);
            $metodos = $clase->getMethods(ReflectionMethod::IS_PUBLIC);
            //Debug::mostrarArray($metodos,false);
            foreach ($metodos as $key => $value) {
                if($value->name!='__construct' and $value->class==$nombreClase){
                    $arrayMetodos[$key]=$value->name;
                }
                    
            }
            
            $claseMetodo = new Metodo();
            $claseMetodo->validarMetodosExistentes($arrayMetodos, $objeto->id_objeto);
            $this->data['vistaMetodos'] = MetodosController::vistaMetodos($objeto);
            
        }
        return $this->data;
    }
	
    
    function addDescripcion(){
        if(isset($_GET['metodo']) and $this->getEntero($_GET['metodo'])){
                
            if(isset($_POST['s-ajax'])){
                $this->layout='ajax.tpl.php';
            }
            
            $form = new Formulario('DescripcionMetodo',2,$_GET['metodo'],2);
            $metodo = new Metodo($_GET['metodo']);
            $form->action="$this->url".'add-descripcion/metodo/'.$metodo->id_metodo;
            $form->tituloFormulario="Agregar Descripci&oacute;n del metodo ".$metodo->metodo;
            if(isset($_POST['btnDescripcionMetodo'])){
                $validacion = $form->validarFormulario();
                if($validacion===TRUE){
                    $guardado = $metodo->salvar($_POST);
                    if($guardado['ejecutado']==1){
                        Vista::msj('metodos', 'suceso', "La descripci&oacute;n del Metodo <strong>$metodo->metodo</strong> ha sido registrada exitosamente");
                    }else{
                        Vista::msj('metodos', 'error', "No se ha podido registrar la descripci&oacute;n, por favor vuelva a intentarlo");
                    }
                }else{
                    Vista::msj('metodos', 'error', "No se ha podido registrar la descripci&oacute;n, vuelva a intentarlo luego");
                }
                redireccionar('/jadmin/objetos/metodos/obj/'.$metodo->id_objeto);
            }
            
            $this->data['form'] = $form->armarFormulario();
        }else{
            
            throw new Exception("Pagina no conseguida", 404);
        }
        
    }
	protected function vistaMetodos(Objeto $obj){
        $query = "select id_metodo,metodo as \"Metodo\",descripcion as \"Descripci&oacute;n\" from s_metodos where id_objeto=$obj->id_objeto";
        $vista = new Vista($query,$GLOBALS['configPaginador'],'metodos');
        $vista->tituloVista="Metodos del objeto ".$obj->objeto;
        $vista->setParametrosVista(array('idDivVista'=>'metodosObjeto'));
        
        $vista->filaOpciones=array(1=>array('a'=>array(
                                                'atributos'=>array( 'class'=>'btn',
                                                                    'title'=>'Agregar Descripci&oacute;n',
                                                                    'data-link'=>$this->url."add-descripcion/metodo/{clave}",
                                                                    'data-jvista'=>'modal'
                                                                    ),
                                                'html'=>array('span'=>array('atributos'=>array('class' =>'fa fa-edit fa-lg'))))
                                                ),
                                    2=>array('a'=>array(
                                                'atributos'=>array( 'class'=>'btn',
                                                                    'title'=>'Editar Perfiles',
                                                                    'data-link'=>$this->url."asignar-acceso/metodo/{clave}",
                                                                    'data-jvista'=>'modal'
                                                                    ),
                                                'html'=>array('span'=>array('atributos'=>array('class' =>'fa fa-users fa-lg'))))
                                                ),
                                    );
        $vista->acciones=array( 'Asignar perfiles de acceso'=>array('href'=>$this->url.'asignar-acceso/',
                                                                'data-jvista'=>'seleccion',
                                                                'data-multiple'=>'true','data-jkey'=>'metodo'),
                                
                              );
                              
        $vista->setParametrosVista($GLOBALS['configVista']);
        return $vista->obtenerVista();
    }
	 /**
     * Muestra un formulario para asignar el acceso de los perfiles del sistema a un metodo
     * @method asignarAcceso
     * @access public 
     *
     */
    function asignarAcceso(){
        if(isset($_GET['metodo']) and $this->getEntero($_GET['metodo'])!=""){            
            $this->vista="accesoPerfiles";
            
            $form = new Formulario('PerfilesAMetodos',2,Globals::obtGet('metodo'),2);
            $metodo = new Metodo($this->getEntero($_GET['metodo']));
            
            $form->action=$this->url."asignar-acceso/metodo/".$_GET['metodo'];
            $form->valueSubmit="Asignar Perfiles a Metodo";
            $form->tituloFormulario="Asignar acceso de perfiles al Metodo ".$metodo->metodo;
            if(isset($_POST['btnPerfilesAMetodos'])){
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    
                    $accion = $obj->asignarAccesoPerfiles(Globals::obtPost('id_perfil'));
                    if($accion['ejecutado']==1){
                        Vista::msj('metodos', 'suceso', 'Asignados los perfiles de acceso al metodo '.$obj->objeto);
                        redireccionar($this->url);
                    }else{
                        Formulario::msj('error', "No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                    }
                }else{
                    Formulario::msj('error', "No se han asignado perfiles");
                }
            }
            $this->data['formAcceso'] =$form->armarFormulario();
        }else{
            Vista::msj('metodos', 'error',"Debe seleccionar un objeto");
            redireccionar($this->url);  
        }    
    }

}


?>