<?PHP 
/**
 * Clase Modelo de componentes del sistema u aplicación
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category Model
 * @version 0.1 12-02-2014
 */

 
class Componente extends DBContainer{
    
    
    /**
     * id numerico del componente
     * @var  int $id_componente
     */
    var $id_componente;
    /**
     * Nombre identificador del componente
     * @var $componente
     */
    var $componente;
    function __construct($id=""){
        $this->nombreTabla="s_componentes";
        $this->clavePrimaria="id_componente";
        parent::__construct(__CLASS__,$id);
        
    }
    
    
    /**
     * Guarda o modifica un componente en base de datos
     * 
     * @method guardarComponente
     * @acces public
     */
    function guardarComponente($datos=""){
        
        $guardado = $this->salvarObjeto(__CLASS__,$datos);
        return $guardado;
    }
    
    
    /**
     * Elimina un componente de base de datos.
     */
    function eliminarComponente(){
        $this->eliminarObjeto();
    }
      /**
     * Registra los perfiles que tienen acceso a un objeto determinado
     * 
     * @param array $perfiles Perfiles con acceso al objeto
     * @param string $metodo ID del metodo al que se asigna el permiso
     * @return boolean true or false 
     */
    function asignarAccesoPerfiles($perfiles){
            
            $insert="insert into s_componentes_perfiles values ";
            $i=0;
            foreach ($perfiles as $key => $idPerfil) {
                if($i>0)$insert.=",";
                $insert.="(null,$idPerfil,$this->id_componente)";
                $i++;
            }
            
            $delete = "delete from s_componentes_perfiles where id_componente=$this->id_componente";
            $this->bd->ejecutarQuery($delete);
            $this->bd->ejecutarQuery($insert);
            return array('ejecutado'=>1);
        
    }
    
    /**
     * Verifica si un perfil o grupo de perfiles tienen acceso al componente
     * @method validarAccesoComponente
     * @access public
     * @param array $arreglos Perfiles a validar
     * @return boolean TRUE or FALSE
     * 
     */
    function validarAccesoComponente($perfiles){
        echo "ak";
        $query = "";
        foreach ($perfiles as $key => $idPerfil) {
                if($i>0)$insert.=",";
                $insert.="(null,$idPerfil,$this->id_objeto)";
                $i++;
            }
        #Arrays::mostrarArray($perfiles);exit;
        
    }
    
    
}


?>