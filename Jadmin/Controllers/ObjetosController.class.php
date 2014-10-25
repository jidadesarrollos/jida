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
        $this->layout="jadmin.tpl.php";
		$this->url = "/jadmin/objetos/";
		$this->modelo = new Objeto();		
        
    }
	
	function index(){
		
  			$this->vista="lista";
			$this->tituloPagina="Objetos del Sistema";
            
			$query = "select id_objeto,objeto as \"Objeto\",a.descripcion \"Descripci&oacute;n\", componente  as Componente
						from s_objetos a 
						join s_componentes b on (b.id_componente = a.id_componente)";
                        
			$vista = new Vista($query,$GLOBALS['configPaginador'],"Objetos");
			$vista->setParametrosVista($GLOBALS['configVista']);
            $vista->seccionBusqueda=true;
            $vista->camposBusqueda=array('objeto','componente');
            $vista->tipoControl=2;
			$vista->filaOpciones=array(0=>array('a'=>array(
															'atributos'=>array(	'class'=>'btn',
																				'title'=>'ver metodos',
																				'href'=>"/jadmin/objetos/metodos/obj/{clave}"
																				),
															'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-eye-open'))))),
									   1=>array('a'=>array(
                                                            'atributos'=>array( 'class'=>'btn',
                                                                                'title'=>'Asignar perfiles de acceso',
                                                                                'href'=>"/jadmin/objetos/asignar-acceso/obj/{clave}"
                                                                                ),
                                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-edit')))))
												);
	        				
			$vista->acciones=array(
								'Agregar Descripci&oacute;n'=>array('href'=>'/jadmin/objetos/set-objeto',
								            		'data-jvista'=>'seleccion',
								    		        'data-multiple'=>'false','data-jkey'=>'obj'),
								 	                 );
			$msjError = "No hay registros de ".$vista->tituloVista . " <a href=\"".$this->url."set-objeto\">Agregar objeto</a>";
			$vista->mensajeError= Mensajes::mensajeAlerta($msjError);
			$this->data['vista'] = $vista->obtenerVista();
           
           
           
       	
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
          if(isset($_GET['comp'])){
               $idComponente = $this->getEntero(Globals::obtGet('comp'));
               $comp = new Componente($idComponente);
               $this->validarObjetos($comp);
               $query = "select id_objeto,objeto as \"Objeto\" from s_objetos where id_componente = $idComponente";
               $vista = new Vista($query,$GLOBALS['configPaginador'],"Objetos del Componente $comp->componente");
			   $vista->setParametrosVista(array('idDivVista'=>'objetos'));
			   $vista->setParametrosVista($GLOBALS['configVista']);
			   $vista->filaOpciones=array(0=>array('a'=>array(
															'atributos'=>array(	'class'=>'btn',
																				'title'=>'ver metodos',
																				'href'=>"/jadmin/objetos/metodos/obj/{clave}"
																				),
															'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-eye-open')))))
												);
               $vista->acciones=array(
                                'Agregar Descripci&oacute;n'=>array('href'=>'/jadmin/objetos/set-objeto-comp/comp/'.$idComponente,
                                                                'data-jvista'=>'seleccion',
                                                                'data-multiple'=>'true','data-jkey'=>'comp'),
                                );
               $vista->mensajeError= "No hay registros de ".$vista->tituloVista . " <a href=\"".$this->url."set-objeto/comp/$idComponente\">Agregar objeto</a>";
               $this->data['vista'] = $vista->obtenerVista();
           }else{
               Session::set('__idVista','componentes');
               Session::set('__msjVista',Mensajes::mensajeAlerta("Debe seleccionar un componente"));
           }
           
           
       	
   }

    function setObjetoComp(){
        
        	$this->tituloPagina="Registro de objetos";
            if(isset($_GET['comp'])){
                $tipoForm=1;$campoUpdate="";
				
				if(isset($_GET['obj'])){
				$tipoForm=2;
				$campoUpdate=Globals::obtGet('obj');	
				}
				$form = new Formulario('SistemaObjetos',$tipoForm,$campoUpdate);
				$form->valueSubmit = "Guardar Objeto";
				$form->tituloFormulario = "Gesti&oacute;n de Objetos";
				$form->action=$this->url . "set-objeto/comp/".Globals::obtGet('comp');
				
				if(isset($_POST['btnSistemaObjetos'])){
					$post = $_POST;
					$validacion = $form->validarFormulario($post);
					if($validacion===TRUE){
						$obj = new Objeto();
						$post['id_componente'] = $this->getEntero(Globals::obtGet('comp'));
						if($this->validarNombreObjeto(Globals::obtPost('objeto'))){
						$post['objeto'] = String::upperCamelCase($post['objeto']);
							$accion = $obj->setObjeto($post);
							if($accion['ejecutado']==1){
								Session::set('__msjVista', Mensajes::mensajeSuceso("Se registro el objeto ". $obj->objeto.""));
								Session::set('__idVista','objetos');
								redireccionar($this->url."lista/comp/".Globals::obtGet('comp'));
							}else{
								$msj = Mensajes::mensajeError("No se pudo registrar el objeto");
								if(isset($accion['msj'])){
									$msj = $accion['msj'];
								}
								Session::set('__msjForm',$msj);	
							}
						}else{
							Session::set('__msjForm', Mensajes::mensajeError("No existe el objeto <strong>".Globals::obtPost('objeto')."</strong>"));
						}
					}else{
						Session::set('__msjForm', Mensajes::mensajeError("No se pudo registrar el objeto"));
					}
				}
				
				$this->data['formObj']   = $form->armarFormulario();
				
				
            }else{
            	Session::set('__msjVista', Mensajes::mensajeAlerta("Debe seleccionar un componente"));
				Session::set('__idVista','componentes');
            }
        
		
    }// final funcion setObjetoCompo
    
    /**
	 * Crea formulario para registrar un objeto solicitando la selección del 
	 * componente al que pertenece
	 * 
	 * @method setObjeto
	 * @access public
	 */
    function setObjeto(){
        
        	$this->tituloPagina="Registro de objetos";
        
            $tipoForm=1;$campoUpdate="";
			
			if(isset($_GET['obj'])){
			$tipoForm=2;
			$campoUpdate=Globals::obtGet('obj');	
			}
            $obj = new Objeto($campoUpdate);
            $formulario = ($tipoForm==2)?'SistemaObjetos':'RegistroObjetos';
			$form = new Formulario($formulario,$tipoForm,$campoUpdate,2);
			$form->valueSubmit = "Guardar Objeto";
			$form->tituloFormulario = "Gesti&oacute;n de Objetos";
			$form->action=$this->url . "set-objeto/";
			if($tipoForm==2)
                $form->action .= 'obj/'.$obj->id_objeto;
			if(isset($_POST['btnRegistroObjetos']) or isset( $_POST['btnSistemaObjetos'])){
				$post = $_POST;
				$validacion = $form->validarFormulario($post);
				if($validacion===TRUE){
					
					if($this->validarNombreObjeto(Globals::obtPost('objeto'))){
						$post['objeto'] = String::upperCamelCase($post['objeto']);
						$accion = $obj->setObjeto($post);
						if($accion['ejecutado']==1){
							Session::set('__msjVista', Mensajes::mensajeSuceso("Se registro el objeto ". $obj->objeto.""));
							Session::set('__idVista','objetos');
							redireccionar($this->url);
						}else{
							$msj = Mensajes::mensajeError("No se pudo registrar el objeto");
								if(isset($accion['msj'])){
									$msj = $accion['msj'];
								}
							Session::set('__msjForm', $msj);	
						}
					}else{
						Session::set('__msjForm', Mensajes::mensajeError("No existe el objeto <strong>".Globals::obtPost('objeto')."</strong>"));	
					}
				}else{
					Session::set('__msjForm', Mensajes::mensajeError("No se pudo registrar el objeto"));
				}
			}
			$this->data['formObj']   = $form->armarFormulario();
			
		
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
        $this->data = $controladorMetodos->metodosObjeto();
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
				
			
				$metodo = new Metodo(Globals::obtGet('metodo'));
				$form = new Formulario('PerfilesAMetodos',2,Globals::obtGet('metodo'),2);
				
				$form->action=$this->url."acceso-perfiles/metodo/".Globals::obtGet('metodo');
				$form->valueSubmit="Asignar Perfiles";
				$form->tituloFormulario="Asignar acceso de perfiles a metodo $metodo->nombre_metodo";
				if(isset($_POST['btnPerfilesAMetodos'])){
					$validacion = $form->validarFormulario($_POST);
					if($validacion===TRUE){
						
						$accion = $metodo->asignarAccesoPerfiles(Globals::obtPost('id_perfil'));
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
				$this->data['formAcceso'] =$form->armarFormulario();
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
        
        if(isset($_GET['obj']) and $this->getEntero($_GET['obj'])!=""){            
            $this->vista="accesoPerfiles";
            $form = new Formulario('PerfilesAObjetos',2,Globals::obtGet('obj'),2);
            $obj = new Objeto($this->getEntero(Globals::obtGet('obj')));
            $form->action=$this->url."asignar-acceso/obj/".Globals::obtGet('obj');
            $form->valueSubmit="Asignar Perfiles a Objeto";
            $form->tituloFormulario="Asignar acceso de perfiles al objeto $obj->objeto";
            if(isset($_POST['btnPerfilesAObjetos'])){
                $validacion = $form->validarFormulario($_POST);
                if($validacion===TRUE){
                    
                    $accion = $obj->asignarAccesoPerfiles(Globals::obtPost('id_perfil'));
                    if($accion['ejecutado']==1){
                        Session::set('__idVista', 'objetos');
                        $msj = Mensajes::mensajeSuceso('Asignados los perfiles de acceso al objeto '.$obj->objeto);
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
            Session::set('__idVista','objetos');
            redireccionar($this->url);  
        }    
    }

	
	
	
}


?>