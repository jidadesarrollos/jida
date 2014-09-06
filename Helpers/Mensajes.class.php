<?PHP 
/**
 * Helper para manejo de mensajes dentro de la aplicación
 * 
 * Las variables de session para mensajes dentro del jida-framework son:
 * <ul>
 * <li><b>__msjForm</b> Para mensajes en la clase formulario</li>
 * <li><b>__msj </b> Para mensajes en la clase vista y donde se desee.</li>
 * existentes 
 * @package framework
 * @category helper
 * @author  Julio Rodriguez 
 * @version 0.1 12/01/2014
 */
class Mensajes {
    
    
    
    function __construct(){
        
    }//final constructor
    /**
     * Define un arreglo con los valores css para los mensajes
     * 
     * Las clases css a aplicar deben estar definidas en las constantes
     * usadas.
     */
     
    static function crear($tipo,$msj,$hidden='true'){
        $css = self::obtenerEstiloMensaje($tipo);
        $mensaje = "
                    <DIV class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $msj
                    </DIV>";
        return $mensaje;
    }
    static function obtenerEstiloMensaje($clave){
        
            
        $estilo=array();
        // if(defined(cssMsjError) and defined(cssMsjAlerta) 
        // and defined(cssMsjSucess) and defined(cssMsjInformacion)){
            $estilo['error']=cssMsjError;
            $estilo['alerta']=cssMsjAlerta;
            $estilo['suceso']=cssMsjSuccess;
            $estilo['informacion']=cssMsjInformacion;    
        // }else{
            // $excepcion = "No se encuentran definidas las constantes css para los mensajes, verifique
            // el archivo de configuración";
            // throw new Exception($excepcion, 1);
        // }
        
        return $estilo[$clave];

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
       $css = self::obtenerEstiloMensaje('informacion');
       $mensaje = "
                    <DIV class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
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
     * Si no se pasa ningun parametro, la función verificará si existe una variable de sesion 
     * "__msj".
     * 
     * @param string $msj Nombre de la variable de sesión a imprimir.
     * @method imprimirMsjSesion
     * 
     */
    static function imprimirMsjSesion($msj="__msj"){
       if(isset($_SESSION[$msj])){ 
            echo $_SESSION[$msj];
            Session::destroy($msj);
        }
    }
    
    static function msjExcepcion($msj,$ruta){
        $_SESSION['__excepcion'] = $msj;
        
        echo $_SESSION['__excepcion'];
  //      redireccionar($ruta);
    }
    
    
} // END

?>