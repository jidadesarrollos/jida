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
     * @var object $excepcion Objeto con excepción capturada
     */
    var $excepcion;
	var $layoutPropio =FALSE;    
    /**
     * Funcion por defecto para manejar
     * las excepciones existentes en el entorno de desarrollo
     * @method estandard
     * @param object $message
     * @return boolean true
     */
    function error($e){
        $this->excepcion=$e;
        $this->data['msjError'] = $this->procesarError();
        
    }
    

    protected function procesarError($view=""){
        
        if(!empty($view)){
               $this->vista=$view;        
         }else{
             
         	$this->tituloPagina="Error ".$this->excepcion->getCode();
             switch ($this->excepcion->getCode()) {
                 case 404:
				 case 403:
                    $this->vista="404";
                    break;
                    
                 default:
                    
                    $this->vista="500";
                    
                    break;
             }
         }
         $msj  = $this->getDetalleExcepcion();
         return $msj; 
        //Excepcion::mailError($this->excepcion);
            
    }
    
    private function getDetalleExcepcion(){
        switch (entorno_app) {
             case 'dev':
                    
                    $msj = $this->getHTMLMessage();
                    
                 break;
             
             case 'prod':
                 
                    $msj = $this->excepcionProduccion();
                 break;
        }//fin switch
        return $msj;   
    }
    private function getHTMLMessage(){
        $e =& $this->excepcion;
        $msj = '<h3>Error Capturado!</h3><hr>';
            $msj.="<strong>Mensaje : </strong>".$e->getMessage()."<br/>";
            $msj.="<strong>L&iacute;nea : </strong>".$e->getLine()."<br/>";
            $msj.="<strong>Archivo : </strong>".$e->getFile()."<br/>";
            $msj.="<strong>C&oacute;digo : </strong>".$e->getCode()."<br/>";
            $msj.="<hr/><div style=\"font-size:12px\"><h4>Traza</h4><br/>";
            #$msj.="<strong>Traza : </strong>".implode(",",$e->getTrace())."<br>";
            foreach($e->getTrace() as $key =>$traza){
                $msj.="<strong>Archivo</strong> ". $traza['file']."<br/>";
                $msj.="<div style=\"padding-left:20px\">";
                $msj.="<strong>Linea</strong> ".$traza['line']."<br/>";
                $msj.="<strong>Funcion</strong> ".$traza['function']."<br/>";
                $msj.="</div>";
                #$msj.="<hr>";
            }
            $msj.="</div>";
        return $msj;
    }
    /**
     * Maneja las excepciones en entorno de producción
     * 
     * Registra el error en el log y si se ha definido un correo administrador es enviado
     * un mail de notificacion
     * @method excepcionProduccion
     */
    private function excepcionProduccion(){
        $msj = $this->getHTMLMessage();

        if(defined('MAIL_ADMIN') and defined('MAIL_NO_RESPONDER') and defined('MAIL_NO_RESPONDER')){
            
            $mail = new EmailComponente(MAIL_NO_RESPONDER,MAIL_NO_RESPONDER);
            $mail->setTemplatePath('jidaPlantillas/mail/');
            $data=array(':detalle_error'=>$msj,':aplicacion'=>'Junquito En Línea');
            $title = 'Error en aplicacion '.NOMBRE_APP;
            if(defined('APP_NAME')) $title.=" ".NOMBRE_APP;
            $mail->enviarEmail(MAIL_ADMIN, $title, $data,'error.tpl.php' );
            
        }else{
            Debug::string("Ups, lo sentimos. Comuniquese con el Administrador",true);
        }
        return $msj;
    }
    
    
}




