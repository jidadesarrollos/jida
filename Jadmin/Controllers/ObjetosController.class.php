<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category Controller
 * @version 0.1
 */

 
class ObjetosController extends Controller{
    
	
	var $Mperfil = "";
    function __construct($id=""){
        $this->helpers=array('Arrays');
        parent::__construct();
        
        if(!$this->solicitudAjax()) $this->layout="jadmin.tpl.php";
		$this->url = "/jadmin/objetos/";
		$this->modelo = new Objeto();	
        $this->dv->title = "Objetos";	
        
    }
	
	function index(){
		
  			$this->vista="lista";
			$this->tituloPagina="Objetos del Sistema";
            $query = "select id_objeto,objeto as \"Objeto\",a.descripcion \"Descripci&oacute;n\", componente  as Componente
                        from s_objetos a 
                        join s_componentes b on (b.id_componente = a.id_componente)";
                        
			
			$vista = $this->vistaObjetos($query);
            $vista->tituloVista="Objetos";
			$msjError = "No hay registros de ".$vista->tituloVista . " <a href=\"".$this->url."set-objeto\">Agregar objeto</a>";
			$vista->mensajeError= Mensajes::mensajeAlerta($msjError);
			$this->dv->vista = $vista->obtenerVista();
           
           
           
       	
	}
    /**
     * Verifica los objetos existentes en un directorio especificado o en todos.
     * 
     * Si la funcion consigue nuevos controladores Los registra en base de datos, si valida que se encuentran
     * registrados controladores que ya no existen, los elimina 
     * 
     * @method validarObjetos
     * @access private
     */
    private function validarObjetos(Componente  $componente){
        $objetosInexistentes =array();
        $objetosNuevos=array();
        $nombreComponente = String::upperCamelCase($componente->componente);
		if($nombreComponente=='Principal'){
			$rutaComponente= app_dir."Controller/";
		}else{
			$rutaComponente = ($nombreComponente=='Jadmin')?framework_dir.'Jadmin/Controllers/':app_dir."Modulos/".$nombreComponente."/Controller/";	
		}
        
        
        
        $objetosCarpeta =array();
        # "/^(?:\+|-)?\d+$/"
        Directorios::listarDirectoriosRuta($rutaComponente,$objetosCarpeta,"/^.*Controller.class.php$/");
        array_walk($objetosCarpeta,function(&$objeto,$key){
                     $objeto =str_replace("Controller.class.php", "", $objeto);
                });
                
        $objetos = new Objeto();
        $dataBD = $objetos->getTabla(null,array('id_componente'=>$componente->id_componente));
        $objetosBD=array();
        //Recorro los objetos de la bd
        foreach($dataBD as $key=>$valor){    
            $objetosBD[]=$valor['objeto'];
        }
        
        $nuevos = array_diff($objetosCarpeta, $objetosBD);
        $inexistentes = array_diff($objetosBD, $objetosCarpeta);

        if(count($nuevos)>0){
            $objetos->insert(array('objeto','id_componente'), $this->Arrays->addColumna($nuevos,$componente->id_componente));
        }
        
        if(count($inexistentes)>0){
            $objetos->eliminarMultiplesDatos($inexistentes, 'objeto');
        }
    }
    /**
     * Lista los objetos registrados
     * @method lista
     * 
     */
    function lista(){
		  $this->tituloPagina="jida-Registro Componentes";
          $this->dv->vista="";   
          if($this->get('comp')){
               $idComponente = $this->getEntero($this->get('comp'));
               $comp = new Componente($idComponente);
               $this->validarObjetos($comp);
               $query = "select id_objeto,objeto as \"Objeto\",descripcion \"Descripci&oacute;n\" from s_objetos where id_componente = $idComponente";
               $vista =$this->vistaObjetos($query);
               $vista->tituloVista="Objetos del Componente ".$comp->componente;
               $vista->mensajeError= "No hay registros de ".$vista->tituloVista . " <a href=\"".$this->url."set-objeto/comp/$idComponente\">Agregar objeto</a>";
               $this->dv->vista = $vista->obtenerVista();
           }else{
               Session::set('__idVista','componentes');
               Session::set('__msjVista',Mensajes::mensajeAlerta("Debe seleccionar un componente"));
           }
   }


   protected function vistaObjetos($query){
       
       $vista = new Vista($query,$GLOBALS['configPaginador'],"Objetos");
       $vista->setParametrosVista(array('idDivVista'=>'objetos'));
       $vista->setParametrosVista($GLOBALS['configVista']);
       $vista->filaOpciones=[
        0=>['a'=>[
            'atributos'=>[ 'class'=>'btn',
                                'title'=>'ver metodos',
                                'href'=>$this->url."metodos/obj/{clave}"
                                ],
            'html'=>['span'=>['atributos'=>['class' =>'glyphicon glyphicon-eye-open']]]]],
        
        1=>['a'=>[
            'atributos'=>[ 'class'=>'btn',
                                'title'=>'Agregar Descripci&oacute;n',
                                'href'=>$this->url."/add-descripcion/obj/{clave}"
                                ],
            'html'=>['span'=>['atributos'=>['class' =>'fa fa-info']]]]],
        2=>['a'=>[
            'atributos'=>[ 'class'=>'btn',
                                'title'=>'Asignar Accesos',
                                'href'=>$this->url."asignar-acceso/obj/{clave}"
                                ],
            'html'=>['span'=>['atributos'=>['class' =>'fa fa-users']]]]],
                            
            ];
       $vista->acciones=[
                        'Agregar Descripci&oacute;n'=>['href'=>$this->url.'set-objeto/obj/',
                                                        'data-jvista'=>'seleccion',
                                                        'data-multiple'=>'true','data-jkey'=>'obj'],
                        ];
       $vista->mensajeError= "No hay registros de ".$vista->tituloVista . " <a href=\"".$this->url."set-objeto/\">Agregar objeto</a>";
       return $vista;
   }

    /**
     * Permite agregar un nombre descriptivo a un objeto
     * 
     * La descripción del objeto es usada para que un usuario final pueda visualizar un nombre entendible
     * @method addDescripcion
     */
    function addDescripcion(){
        if(isset($_GET['obj']) and $this->getEntero($_GET['obj'])){
                
            if(isset($_POST['s-ajax'])){
                $this->layout='ajax.tpl.php';
            }
            
            $form = new Formulario('DescripcionMetodo',2,$_GET['obj'],2);
            $Objeto = new Objeto($_GET['obj']);
            
            $form->action="$this->url".'add-descripcion/obj/'.$Objeto->id_objeto;
            $form->tituloFormulario="Agregar Descripci&oacute;n del Objeto ".$Objeto->objeto;
            if(isset($_POST['btnDescripcionMetodo'])){
                $validacion = $form->validarFormulario();
                if($validacion===TRUE){
                    $guardado = $Objeto->salvar($_POST);
                    
                    if($guardado['ejecutado']==1){
                        Vista::msj('objetos', 'suceso', "La descripci&oacute;n del Metodo <strong>$Objeto->objeto</strong> ha sido registrada exitosamente");
                    }else{
                        Vista::msj('objetos', 'error', "No se ha podido registrar la descripci&oacute;n, por favor vuelva a intentarlo");
                    }
                }else{
                    Vista::msj('objetos', 'error', "No se ha podido registrar la descripci&oacute;n, vuelva a intentarlo luego");
                }
                redireccionar('/jadmin/objetos/lista/comp/'.$Objeto->id_componente);
            }
            
            $this->dv->form = $form->armarFormulario();
        }else{
            
            throw new Exception("Pagina no conseguida", 404);
        }
        
    }

    /**
     * Valida la estructura del nombre de un objeto
     * @method validarNombreObjeto
     */
	private function validarNombreObjeto($nombre){
		$nombreClase = String::upperCamelCase($nombre."Controller");
		if(class_exists($nombreClase)){
			return true;
		}else {
			return false;
		}
	}
	
	/**
     * Permite visualizar los metodos de un controlador
     * 
     * @see MetodosController::vistaMetodos();
     * @method metodos
     * @access public
     */
	function metodos(){
		$this->vista ="listaMetodos";
        $controladorMetodos = new MetodosController();
        $this->dv->vistaMetodos = $controladorMetodos->metodosObjeto();
	}
	/**
     * Muestra un formulario para dar acceso de los perfiles registrados al metodo de un objeto
     * 
     * @method accesoPerfiles
     * @access public
     * 
     */
	function accesoPerfiles(){
		
			if(isset($_GET['metodo'])){
				
			
				$metodo = new Metodo($this->get('metodo'));
				$form = new Formulario('PerfilesAMetodos',2,$this->get('metodo'),2);
				
				$form->action=$this->url."acceso-perfiles/metodo/".$this->get('metodo');
				$form->valueSubmit="Asignar Perfiles";
				$form->tituloFormulario="Asignar acceso de perfiles a metodo $metodo->nombre_metodo";
				if(isset($_POST['btnPerfilesAMetodos'])){
					$validacion = $form->validarFormulario($_POST);
					if($validacion===TRUE){
						
						$accion = $metodo->asignarAccesoPerfiles($this->post('id_perfil'));
						if($accion['ejecutado']==1){
							Session::set('__idVista', 'metodosObjeto');
							$msj = Mensajes::mensajeSuceso('Asignado los perfiles de acceso al metodo '.$metodo->nombre_metodo);
							Session::set('__msjVista',$msj);
							redireccionar($this->url."metodos/obj/".$metodo->id_objeto);
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
				Session::set('__msjVista',Mensajes::mensajeError("Debe seleccionar un metodo"));
				Session::set('__idVista','objetos');
				redireccionar($this->url);	
			}
		
	}
    /**
     * Muestra un formulario para asignar el acceso de los perfiles del sistema a un objeto determinado
     * @method asignarAcceso
     * @access public 
     *
     */
    function asignarAcceso(){
        
        if($this->getEntero($this->get('obj'))>0){            
            $this->vista="accesoPerfiles";
            $form = new Formulario('PerfilesAObjetos',2,$this->get('obj'),2);
            $obj = new Objeto($this->getEntero($this->get('obj')));
            
            $form->action = $this->getUrl('asignarAcceso',['obj'=>$this->get('obj')]);
            $form->valueBotonForm="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar acceso de perfiles al objeto $obj->objeto";
            if($this->post('btnPerfilesAObjetos')){
                $validacion = $form->validarFormulario();
                if($validacion===TRUE){
                    $accion = $obj->asignarAccesoPerfiles($this->post('id_perfil'));
                    if($accion['ejecutado']==1){
                        Vista::msj("objetos", 'suceso', 'Asignados los perfiles de acceso al objeto '.$obj->objeto,$this->getUrl('lista',['comp'=>$obj->id_componente]));
                    }else{
                        Formulario::msj('error', "No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                    }
                }else{
                    Formulario::msj('error', "No se han asignado perfiles");
                    
                }
            }
            $this->dv->formAcceso =$form->armarFormulario();
        }else{
            Vista::msj("objetos", 'suceso', "Debe seleccionar un objeto",$this->urlController());  
        }    
    }

	
	
	
}