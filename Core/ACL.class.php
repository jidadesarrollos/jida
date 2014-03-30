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
 
 
class ACL extends DBContainer{
    
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
     
    private $acl;
    private $perfiles =array();
    /**
     * @var array $componentes Conjunto de componentes a los que tiene el usuario
     */
    private $componentes =array();
    /**
     * Funcion constructora
     */
    function __construct(){
        parent::__construct();
        
        if(!isset($_SESSION['usuario']['perfiles'])){
            Session::set('usuario', 'perfiles',array('UsuarioPublico'));
            Session::set('acl_default',true);
        }
        
        $this->perfiles = $_SESSION['usuario']['perfiles'];
        
        $this->obtenerAccesoComponentes();
        
        $this->obtenerAccesoObjetos();
    }
    /**
     * Define los componentes a los que tiene acceso el perfil
     * 
     * @method obtenerAccesoComponentes
     */
    private function obtenerAccesoComponentes(){
       # try{
            $query = "select id_componente,componente from v_acceso_componentes where clave_perfil in (";
            $i=0;
            foreach ($this->perfiles as $key => $value) {
                ($i==0)?$i++:$query.=",";
                $query.="'$value'";
                
            }
            
            $query.=") group by componente;";
            
            
            $result = $this->bd->ejecutarQuery($query);
            $componentes = array();
            $access = array();
            while($data = $this->bd->obtenerArrayAsociativo($result)){
                $access[$data['componente']] =array(); 
                $componentes[$data['id_componente']] = array(
                                                              'componente'=>$data['componente']  
             
                                                            );           
            }
            
            //EL componente PRINCIPAL siempre es visible;
            $componentes[1]=array('componente'=>'principal');
            $this->componentes = $componentes;
            $this->acl = $access;
            
        // }catch(Exception $e){
            // Excepcion::controlExcepcion($e);
        // } 
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
        // try{
            #if(Session::get('id_acl')!=Session::getIdSession()){
            if(!isset($_SESSION['acl'])){
                
                $perfiles ="";
                 $i=0;
                foreach ($this->perfiles as $key => $value) {
                    ($i==0)?$i++:$perfiles.=",";
                    $perfiles.="'$value'";
                    
                }
                $query = sprintf("select * from v_acceso_objetos where id_componente in(%s)
                                and clave_perfil in (%s)
                                ",
                                    implode(",",array_keys($this->componentes)),
                                    $perfiles
                                    );
                
                $objetos = $this->bd->obtenerDataCompleta($query);
                $accesoObjetos=array();
                $accesoMetodos = $this->obtenerAccesoMetodos();
                
                foreach($objetos as $key =>$dataObjeto){
                    
                    $componente = $this->componentes[$dataObjeto['id_componente']]['componente'];
                    $perfil = $dataObjeto['clave_perfil'];
                    
                    $accesoObjetos[$perfil][$componente]['objetos'][$dataObjeto['objeto']]['nombre'] =$dataObjeto['objeto'];
                    
                    $this->acl[$componente]['objetos'][$dataObjeto['objeto']]['nombre'] =$dataObjeto['objeto'];
                    foreach ($accesoMetodos as $key => $dataMetodo) {
                        if($dataMetodo['objeto']==$dataObjeto['objeto'] and $dataObjeto['clave_perfil']==$dataMetodo['clave_perfil']){
                                
                            $accesoObjetos[$perfil][$componente]['objetos'][$dataObjeto['objeto']]['metodos'][$dataMetodo['nombre_metodo']]=$dataMetodo['nombre_metodo'];
                            $this->acl[$componente]['objetos'][$dataObjeto['objeto']]['metodos'][$dataMetodo['nombre_metodo']]=$dataMetodo['nombre_metodo'];
                        }
                    }
                }
                /* El arreglo es guardado en sesión para que la BD solo sea consultada 1na vez*/
                
                Session::set('acl',$this->acl);
                $this->accesos  =  $accesoObjetos;
                
            }//fin validacion existencia
            
        // }catch(Exception $e){
            // Excepcion::controlExcepcion($e);
        // }
        
    }
    
    
    /**
     * Verifica los accesos del perfil a los metodos de cada objeto
     * 
     * @method obtenerAccesoMetodos
     * 
     */
    
    private function obtenerAccesoMetodos(){
         // try{
            $query ="select * from v_acceso_metodos";
            $accesoMetodos = $this->bd->obtenerDataCompleta($query);
            return $accesoMetodos;
             //Arrays::mostrarArray($accesoMetodos);
         // }catch(Exception $e){
             // Excepcion::controlExcepcion($e);
         // }
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
        
        // $user = Session::get('usuario');
        // $perfilesUser = $perfilesUser['perfiles'];
        $perfilesUser = $this->perfiles;
        //Arrays::mostrarArray($perfilesUser);
        if(empty($componente)){
            $componente = "principal";
        }
        
        $listaAcl  = Session::get('acl');
        #Arrays::mostrarArray($listaAcl);
        $accesosUser = array();
        $acceso=FALSE;
        $i=0;
         //Arrays::mostrarArray($listaAcl);
        #while($acceso == FALSE and $i<count($perfilesUser)){
            
            $perfil = $perfilesUser[$i];
                if(isset($listaAcl[$componente])){
                    $arrComponentes = $listaAcl[$componente];
                    
                    if(!array_key_exists('objetos', $arrComponentes)){
                     //   echo "acceso a todo el componente<hr>";
                        $acceso=TRUE;
                    }else{
                        #echo "acceso al componente<hr>";
                        $arrObjetos =$arrComponentes['objetos'];
                                
                        if(array_key_exists($controlador,$arrObjetos)){
                            #echo "acceso al objeto<hr>";
                            $arObjeto = $arrObjetos[$controlador];
                               
                            if(!array_key_exists('metodos',$arObjeto)){
                            #    echo "acceso a todos los metodos";
                                $acceso=TRUE;
                            }elseif(isset($arObjeto['metodos'][$metodo])){
                           #     echo "acceso al metodo";
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
            
         #   $i++;
        #}
        
        // Arrays::mostrarArray($listaAcl);
        return $acceso;
    }
}

?>