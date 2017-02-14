<?PHP 
/**
 * Helper para manejo de mensajes dentro de la aplicación
 * 
 * @internal Las variables de session para mensajes dentro del jida-framework son:
 * 
 * __msjForm Para mensajes en la clase formulario
 * __msj  Para mensajes en la clase vista y donde se desee.
 *  
 * @package framework
 * @category helper
 * @author  Julio Rodriguez 
 * @version 0.1 12/01/2014
 */

namespace Jida\Helpers;
class Mensajes {
	
    function __construct(){}
    
   /**
     * Define un arreglo con los valores css para los mensajes
     * 
     * Las clases css a aplicar deben estar definidas en las constantes
     * usadas.
     */
     
    static function crear($tipo,$msj,$hidden=false){
        $css = self::obtenerEstiloMensaje($tipo);
        if($hidden==true){
            $mensaje = "
                    <DIV class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" data-dismiss=\"alert\">
                        <span aria-hidden=\"true\">&times;</span></button>
                        $msj
                    </DIV>";    
        }else{
            $mensaje = "
                    <DIV class=\"$css\">
                        $msj
                    </DIV>";
        }
        
        return $mensaje;
    }
    static function obtenerEstiloMensaje($clave){
        
            
        $estilo=array();
        if(array_key_exists('configMensajes', $GLOBALS)){
              $estilo = $GLOBALS['configMensajes'];
        }else{
            
        }
        
        if(array_key_exists($clave, $estilo)){
            return $estilo[$clave];
        }
        

    }
    
    static function mensajeError($mensaje){
       $css = self::obtenerEstiloMensaje('error');
       $mensaje = "
                    <DIV class=\"$css\">
                    <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";
        return $mensaje;
        
    }
    
    static function mensajeAlerta($mensaje){
       $css = self::obtenerEstiloMensaje('alerta');
       $mensaje = "
                    <DIV class=\"$css\">
                    <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";
        return $mensaje;
    }
    
    static function mensajeSuceso($mensaje){
       $css = self::obtenerEstiloMensaje('suceso');
       $mensaje = "
                    <DIV class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";
        return $mensaje;
    }
    
    static function mensajeInformativo($mensaje){
       $css = self::obtenerEstiloMensaje('info');
       $mensaje = "
                    <DIV class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-label=\"Close\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";
        return $mensaje;      
    }
	
   /**
     * Imprime un mensaje si existe
     */
    static function imprimirMensaje($msj="__msj"){
        self::imprimirMsjSesion($msj);
    }
	
   /**
     * Imprime el mensaje guardado en una variable de sesión y luego es destruida la variable
     * @internal Si no se pasa ningun parametro, la función verificará si existe una variable de sesion "__msj".
     * 
     * @param string $msj Nombre de la variable de sesión a imprimir.
     * @method imprimirMsjSesion
     * 
     */
    static function imprimirMsjSesion($msj="__msj"){
        
       if(isset($_SESSION[$msj])){
            echo $_SESSION[$msj];
            Sesion::destroy($msj);
        }
    }
    
    static function msjExcepcion($msj,$ruta){
        $_SESSION['__excepcion'] = $msj;
        echo $_SESSION['__excepcion'];
    }
    
    
} // END

?>