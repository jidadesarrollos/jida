<?PHP 
/**
 * Modelo de Opciones de menu
 *
 * @package Framework
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class OpcionMenu extends DBContainer {
	
	/**
	 * Identificador de la opcion
     * @var integer $id_opcion
     * @access public 
	 */
	var $id_opcion;
    /**
     * Id del menu al que pertenece la opcion
     * @var integer $id_menu
     * @access $id_menu 
     */
    var $id_menu;
    
    /**
     * Url a la que direcciona la opcion
     * @var string $url_opcion
     * @access $url_opcion 
     */
    var $url_opcion;
    
    /**
     * Nombre de la opcion que ven los usuarios 
     * @var string $nombre_opcion
     * @access $nombre_opcion 
     */
    var $nombre_opcion;
    /**
     * Codifico identificador de la opcion padre, en caso
     * de que la opcion actual sea padre es 0
     * @var int $padre
     * @access public
     */
    var $padre;
    /**
     * Define si la opcion tiene sub-opciones hijas
     * Si tiene subopciones es 1 caso contrario 0
     * @var int $hijo
     * @access public
     */
    var $hijo;
    var $id_estatus;
    var $orden;
	/**
     * Arreglo de opciones de un menu
     */
	/**
	 * Funcion constructora de opción menu
     
     * @param $id valor de la clave a instanciar 
	 */
	function __construct($id=""){
	    try{
    	    $this->nombreTabla = 's_opciones_menu';
            $this->clavePrimaria = 'id_opcion';
            
            parent::__construct(__CLASS__,$id);        
        }catch(Exception $e){
            controlExcepcion($e->getMessage());
        }
		
	}//final constructor
	
	function setOpcion($post){
	    
        $this->establecerAtributos($post, __CLASS__);
        $this->hijo = (empty($this->hijo))?0:$this->hijo;
	    $proceso = $this->salvar(null,TRUE);
        if($this->padre!=0){
            $this->setHijoPadre(1);
        }
        
        return $proceso;
        
	    
	}
    /**
     * Elimina una opción de menu
     */
	function eliminarOpcion($id=""){
        try{
            if(!empty($id)){
                $this->id_opcion = $id;
                $this->inicializarObjeto($this->id_opcion);
                
            }
            $this->eliminarObjeto();
            $this->verificarHermanosAntesEliminar();
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }    
	}
    
    /**
     * Verifica que la opción padre tenga la propiedad hijo en 1, si se encuentra en 0 la modifica.
     * 
     *  
     */
	private function setHijoPadre($valor=1){
	   $query = "update $this->nombreTabla set hijo=$valor where $this->clavePrimaria=$this->padre";
       $this->bd->ejecutarQuery($query); 
	}
    /**
     * valida si la opción tiene padre y hermanos
     * 
     * En caso de que la opción tenga padres y no hermanos modifica el valor "hijo" a la opción padre a 0
     */
     
     private function verificarHermanosAntesEliminar(){
         try{
            if($this->padre>0){
                $query = "select * from $this->nombreTabla where padre=$this->padre";
                $result = $this->bd->ejecutarQuery($query);
                if($this->bd->totalRegistros==0){
                    $this->setHijoPadre(0);
                }
                
             }
             
         }catch(Exception $e){
             Excepcion::controlExcepcion($e);
         }
         
     } 
     
} // END

?>