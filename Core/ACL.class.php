<?PHP 


/**
 * Clase para manejar listas de accesos en el framework
 * 
 * Permite manejar distintos roles y grupos de usuario para administrar los accesos y permisos
 * a los mismos a partir de los controladores creados.
 * 
 * @package Framework
 * @subpackage Core
 * @category permisologia
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 0.1 10/02/2014
 * 
 */
 
 
class ACL extends DataModel{
    
    /**
     * Arreglo que contiene los objetos y metodos a los que
     * tiene acceso el usuario
     * @var $accesos
     */
    var $accesos=array();
    /**
     * Perfiles asociados al usuario actual
     * @var $perfiles
     * @access private
     */
     
    private $acl=[];
    /**
     * Perfiles asociados al usuario que inicia sesión
     * @var $perfiles
     * @access public
     * 
     */
    private $perfiles =array();
    /**
     * @var array $componentes Conjunto de componentes a los que tiene el usuario
     */
    private $componentes =array();
	private $usuario;
    /**
     * Funcion constructora
     */
    protected $tablaBD = '';
    function __construct(){
        parent::__construct();
        $this->layout="";
        $this->usuario = Session::get('Usuario');
		
        if(!isset($_SESSION['usuario']['perfiles'])){
        	   
            Session::set('usuario', 'perfiles',array('UsuarioPublico'));
            Session::set('acl_default',true);
        }
       	/**
		 * El objeto de instancia debe ser user siempre pues es el objeto usuario padre
		 * del framework.
		 */
       	$objetoUser = 'User';
        if($this->usuario instanceof $objetoUser){
        	
			$this->perfiles = Session::get('Usuario')->perfiles;
        	if(count($this->perfiles)<1){
        		$this->perfiles = array('UsuarioPublico');		
        	}
            
        }else{
        	if(array_key_exists('usuario', $_SESSION) and array_key_exists('perfiles', $_SESSION['usuario']))
        		$this->perfiles = $_SESSION['usuario']['perfiles'];
			else {
				
				$this->perfiles=[];
			}
		}
        
		if($this->usoBD!==FALSE){
			
		    $this->obtenerAccesoComponentes();
            
		    $this->obtenerAccesoObjetos();
		}else{
			
		}
    }
    /**
     * Define los componentes a los que tiene acceso el perfil
     * 
     * @method obtenerAccesoComponentes
     */
    private function obtenerAccesoComponentes(){
        $componentes=[];
		
        // $query = "select id_componente,componente from s_componentes ";/**
		$query = "select id_componente,componente from vj_acceso_componentes where clave_perfil in (";
        $i=0;

        foreach ($this->perfiles as $key => $value) {
            ($i==0)?$i++:$query.=",";
            $query.="'$value'";
            
        }
        $query.=") group by componente, id_componente;";
		
        $result = $this->bd->ejecutarQuery($query);
        $componentes = array();
        $access = array();
        while($data = $this->bd->obtenerArrayAsociativo($result)){
            $this->acl[$data['componente']]=[];
            $access[$data['componente']] =[]; 
            $componentes[$data['id_componente']] =['componente'=>$data['componente']];           
        }
                
        //EL componente PRINCIPAL siempre es visible;
        
        $this->componentes = $componentes;
        
           
    }
    
    /**
     * Define el acceso del usuario a los objetos de la aplicación
     * 
     * Valida si el perfil tiene acceso a los objetos principales de la aplicación
     * y a los objetos de cada componente, si el perfil no tiene definido acceso a un componente
     * por defecto se niega el acceso a todos los objetos del mismo
     * @method obtenerAccesoObjetos
     * @access private 
     */
    private function obtenerAccesoObjetos(){
        if(ENTORNO_APP=='dev')	Session::destroy('acl');
        if(!Session::get('acl') and count($this->componentes)>0){
                    
            $perfiles ="";
             $i=0;
            foreach ($this->perfiles as $key => $value) {
                ($i==0)?$i++:$perfiles.=",";
                $perfiles.="'$value'";
                
            }

            $query = sprintf("select  id_objeto_perfil,id_perfil,clave_perfil,nombre_perfil,id_objeto,objeto,
								id_componente from vj_acceso_objetos where id_componente in(%s)and clave_perfil in (%s)",
                                implode(",",array_keys($this->componentes)),
                                $perfiles);
			
            $objetos        =   $this->bd->obtenerDataCompleta($query);
            $accesoObjetos  =   array();
            $accesoMetodos  =   $this->obtenerAccesoMetodos();
            
//            Debug::mostrarArray($accesoMetodos,false);
            
            foreach($objetos as $key =>$dataObjeto){
                    
                $componente     = $this->componentes[$dataObjeto['id_componente']]['componente'];
                $perfil         = $dataObjeto['clave_perfil'];    
                $accesoObjetos[$perfil][$componente]['objetos'][$dataObjeto['objeto']]['nombre'] =$dataObjeto['objeto'];
                
                $this->acl[$componente]['objetos'][$dataObjeto['objeto']]['nombre'] =$dataObjeto['objeto'];
                foreach ($accesoMetodos as $key => $dataMetodo) {
                    if($dataMetodo['objeto']==$dataObjeto['objeto'] and $dataObjeto['clave_perfil']==$dataMetodo['clave_perfil']){
                        $this->acl[$componente]['objetos'][$dataObjeto['objeto']]['metodos'][$dataMetodo['metodo']]=$dataMetodo['metodo'];
                    }
                }
                
            }//fin foreach recorrido de objetos
            
            #Debug::mostrarArray($this->acl,false);
            /**
             * Se recorren solo los metodos para validar la existencia de metodos que no requieran validación de sesion y perfiles
             */
            
           
            foreach($accesoMetodos as $key=>$dataMetodo){
        			
                    $componente = $dataMetodo['componente'];
                    $soloElMetodo=false;
                     if($dataMetodo['loggin']==1){
                        
                        if(!array_key_exists($componente, $this->acl)){
                            
                            $this->acl[$componente]=['objetos'=>[]];
                            $soloElMetodo=TRUE;
                        }
                        
                        if(array_key_exists('objetos', $this->acl[$componente]) or $soloElMetodo===TRUE){
                            
                            $objetosComponente =& $this->acl[$componente]['objetos'];
                            if(!array_key_exists($dataMetodo['objeto'],$objetosComponente)){
                                //$objetosComponente[]= $dataMetodo['objeto'];
                                $objetosComponente[$dataMetodo['objeto']]['metodos'][$dataMetodo['metodo']]=$dataMetodo['metodo'];
                            }elseif(array_key_exists('metodos', $objetosComponente[$dataMetodo['objeto']])){
                                $objetosComponente[$dataMetodo['objeto']]['metodos'][$dataMetodo['metodo']]=$dataMetodo['metodo'];    
                                
                            }}
                    }else{
                    	$valores = Arrays::convertirAObjeto($dataMetodo);
						/**
						 * Se agregan los metodos que no requieren loggin, solo si los perfiles actuales no tienen acceso al componente o,
						 * si tienen acceso solo a algunos metodos del componente.
						 */
						if(!array_key_exists($valores->componente, $this->acl) or (is_array($this->acl[$valores->componente]) and array_key_exists('objetos', $this->acl[$valores->componente]))){
							$this->acl[$valores->componente]['objetos'][$valores->objeto]['nombre']=$valores->objeto;
							$this->acl[$valores->componente]['objetos'][$valores->objeto]['metodos'][$valores->metodo]=$valores->metodo;	
						}
                    	
                    }
                }//fin foreach
            /* El arreglo es guardado en sesión para que la BD solo sea consultada 1na vez*/
            
            
            
#            $this->accesos  =  $accesoObjetos;
            
        }else{
        	$this->obtenerMetodosSinLogin();
        }
    	if(!array_key_exists('principal', $this->acl)) $this->acl['principal']=[];
            
        
        
        Session::set('acl',$this->acl);
        
    }
    
	/**
	 * Devuelve los metodos a los que puede acceder un usuario sin logguearse.
	 * 
	 * @method obtenerMetodosSinLogin
	 */
	function obtenerMetodosSinLogin(){
        $query ="select id_metodo,id_objeto,objeto,metodo,loggin,id_perfil,clave_perfil,perfil,id_componente,componente
         from vj_acceso_metodos where loggin=0";
        $accesoMetodos = $this->bd->obtenerDataCompleta($query);
		
		foreach ($accesoMetodos as $key => $valores) {
			$valores = Arrays::convertirAObjeto($valores);
			$this->acl[$valores->componente]['objetos'][$valores->objeto]['nombre']=$valores->objeto;
			$this->acl[$valores->componente]['objetos'][$valores->objeto]['metodos'][$valores->metodo]=$valores->metodo;
		}
		
	}
    
    /**
     * Verifica los accesos del perfil a los metodos de cada objeto
     * 
     * @method obtenerAccesoMetodos
     * 
     */
    
    private function obtenerAccesoMetodos(){
        $query ="select * from vj_acceso_metodos";
        
        $accesoMetodos = $this->bd->obtenerDataCompleta($query);
        return $accesoMetodos;
    }
    /**
     * Verifica si el perfil tiene acceso a la url requerida
     * 
     * Valida el acceso del perfil al componente, objeto y metodo solicitado,
     * si la funcion no recibe un componente definira el componente como el componente "principal"
     * @method validarAcceso
     * @access public
     * @param string $controlador Nombre del controlador requerido
     * @param string $metodo Nombre del metodo requerido
     * @param string $componente [opcional] Componente al que pertenece el objeto, x defecto se hace referencia al principal
     * @return boolean TRUE or FALSE
     */
    function validarAcceso($controlador,$metodo,$componente=""){
    	
    	if($this->usoBD===FALSE)
    		return true;
		
        $componente = strtolower($componente);
		
        $perfilesUser = $this->perfiles;
        if(empty($componente)){
            $componente = "principal";
        }
        
        $listaAcl  = Session::get('acl');
		
		if(defined('DEBUG_ACL') and DEBUG_ACL==TRUE){
		  Debug::mostrarArray($listaAcl,0);    
		}
        
		//Se da acceso si no existe una lista acl creada
        if(!is_array($listaAcl)){
        	return true;
        } 
        
        
        $accesosUser = array();
        $acceso=FALSE;
        $i=0;
        
        while($acceso == FALSE and $i<count($perfilesUser)){
            
            $perfil = $perfilesUser[$i];
                //Se valida acceso al componente
                        
                if(array_key_exists($componente, $listaAcl)){
                	
                    $arrComponentes = $listaAcl[$componente];
                    
                    if(!array_key_exists('objetos', $arrComponentes)){
                        //Si el arreglo no tiene especificado ningun objeto, es porque tiene acceso a todos los objetos
                        
                      //  if($componente=='Social') //Debug::string("si tengo acceso");
                        $acceso=TRUE;
                    }else{
                        $arrObjetos =$arrComponentes['objetos'];
                        
                                
                        if(array_key_exists($controlador,$arrObjetos)){
                              
                            $arObjeto = $arrObjetos[$controlador];
                            /**
                             * Validación de los metodos, si no existe un arreglo de metodos, el usuario tiene acceso
                             * a todos los metodos del objeto
                             */
                            if(!array_key_exists('metodos',$arObjeto)){
                            	
                                $acceso=TRUE;
                            }else
                            if(isset($arObjeto['metodos'][$metodo])){
                           
                                $acceso=TRUE;
                            }else{
                                $acceso=FALSE;
                            }
                        }else{
                            $acceso=FALSE;
                        }
                    }
                
                }else{
                   $acceso=FALSE;
                }
            $i++;    
        }
        #if($acceso===TRUE) Debug::string("TIENE ACCESO",TRUE);
        #else Debug::string("NO TIENES ACCESO",TRUE);
        return $acceso;
    }//fin funcion
}//fin clase

