<?PHP 
/**
 * Controlador de errores generales
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category controlador
 * @version 0.1 02/01/2014
 */
class ExcepcionController extends Controller{
    
    /**
     * Funcion por defecto para manejar
     * las excepciones existentes en el entorno de desarrollo
     * @method estandard
     * @param object $message
     * @return boolean true
     */
     
    function error($e){
        
        $this->data['msjError'] =  Excepcion::controlExcepcion($e);
        $this->vista="500";
        
       
    }
    function index(){
        
        $this->vista = 'excepcion';
    }
    function error500($msj){
        $this->data['__msjError']=$msj;   
        $this->vista='500';
    }
    
    function error403(){
        $this->vista='403';
    }
    function error404(){
        $this->vista='404';
    }
}
?>