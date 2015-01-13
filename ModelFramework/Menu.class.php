<?PHP 
/**
 * undocumented class
 *
 * @package default
 * @author  
 */
class Menu extends DBContainer {
	
	/**
	 * Clave principal del menu
     * @var $id_menu 
     * @access public
	 */
	var $id_menu;
	/**
     * Nombre del menu
     * 
     * Descripción breve del menu
     * @var $nombre_menu
     * @access public
     * 
     */
    var $nombre_menu;
	/**
	 * Funcion constructora
	 */
	 
	private $tablaOpcionesAcceso = 's_opciones_menu_perfiles';
    private $tablaOpciones = 's_opciones_menu';
	function __construct($id_menu = ''){
		
        $this->clavePrimaria = 'id_menu';
        $this->nombreTabla = 's_menus';
        if(!is_numeric($id_menu)){
            
            parent::__construct();
            $this->obtenerMenuByNombre($id_menu);    
        }else{
            parent::__construct(__CLASS__,$id_menu);    
        }
        
        
        // if(!empty($id_menu)){
            // $this->id_menu = $id_menu;
            // $this->obtenerMenu();
        // }
	}//final constructor
	
	/**
     * Obtiene un menu desde la base de datos
     */
	private function obtenerMenu(){
	    $query = "select * from s_menus where $this->clavePrimaria = $this->id_menu";
        
        $result = $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery($query));
        if(count($result)>0){
            $this->establecerAtributos($result, __CLASS__);
        }
	    
	}//final funcion
	
	
	function procesarMenu($post){
	   try{
	       
	       if(!$this->obtenerMenuByNombre($post['nombre_menu'])){
	           $guardado  =$this->salvarObjeto(__CLASS__,$post);
               return $guardado;
            }else{
                $msj = "Ya existe un menu \"".$post['nombre_menu']."\" registrado";
                
                return $msj;   
            }    
	   }catch(Exception $e){
	       Excepcion::controlExcepcion($e);
	   }
	   
	   
	}
	
    
    private function obtenerMenuByNombre($nombre){
        $query = "select * from s_menus where nombre_menu=\"$nombre\"";
        $result = $this->bd->ejecutarQuery($query);
        
        if($this->bd->totalRegistros > 0){
            $data = $this->bd->obtenerArrayAsociativo($result);
            $this->establecerAtributos($data, __CLASS__);
            return true;
        }else{
            return false;
        }
        
    }
    
    function eliminarMenu($menu=""){
        try{
            
            if(!empty($menu)){
                if(is_array($menu)){
                    $this->eliminarMultiplesDatos($menu, 'id_menu');
                }else{
                    $this->id_menu = $menu;
                    $this->obtenerMenu();
                    $this->eliminarObjeto();
                    return true;    
                }
            }
            else{
                throw new Exception("Debe seleccionar un menú a eliminar", 1);
                
            }    
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
        
    }
    
     function obtenerOpcionesMenu($menu = ""){
         
         if(!empty($menu)){
            $this->obtenerMenuByNombre($menu);
         }else{
         	#throw new Exception("Menu no definido", 1); 	
         }
         #Debug::mostrarArray(Session::get('usuario','perfiles'),false);
         
         $perfilesUser = "'".implode("','", Session::get('usuario','perfiles'))."'";
         $query  = "
                    select a.id_opcion_menu,id_menu,url_opcion,nombre_opcion,padre,hijo,id_estatus,icono,orden,
                    selector_icono, id_metodo
                    from 
                    $this->tablaOpciones a
                    join $this->tablaOpcionesAcceso b on (a.id_opcion_menu = b.id_opcion_menu)
                    join s_perfiles c on(b.id_perfil=c.id_perfil)
                    where id_menu = $this->id_menu
                    and c.clave_perfil in($perfilesUser)
                    and  (id_estatus=1 or id_estatus=null)
                    order by padre,orden,nombre_opcion";
         #Debug::string($query);
         $data = $this->bd->obtenerDataCompleta($query);
         return $data;     
     }
    
	
	
} // END

?>