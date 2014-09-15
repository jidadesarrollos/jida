<?PHP 
/**
 * Helper para manejo de excepciones
 *
 * @package framework
 * @caregory Helper
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class Excepcion {
    
    
    /**
     * Función manejadora de excepciones
     * @method controlExcepción
     * @param object $e Objeto de clase Excepción
     * @param mixed $type
     */
    static function controlExcepcion(Exception $e,$type=1){
        
        
            if($e instanceof Exception){
                if(defined('entorno_app')){
                    switch (entorno_app) {
                        case 'dev':
                            
                            $msj = '<h3>Error Capturado!</h3><hr>';
                            $msj.="<strong>Mensaje : </strong>".$e->getMessage()."<br>";
                            $msj.="<strong>L&iacute;nea : </strong>".$e->getLine()."<br>";
                            $msj.="<strong>Archivo : </strong>".$e->getFile()."<br>";
                            $msj.="<strong>C&oacute;digo : </strong>".$e->getCode()."<br>";
                            $msj.="<hr/><div style=\"font-size:11px\">Traza</br>";
                            
                            foreach($e->getTrace() as $key =>$traza){
                                $msj.="<strong>Archivo</strong> ". $traza['file']."<br>";
                                $msj.="<strong>Linea</strong> ".$traza['line']."<br>";
                                $msj.="<strong>Funcion</strong> ".$traza['function']."<br>";

                            }
                            $msj.="</div>";
                            
                            
                            // Arrays::mostrarArray($e->getTrace());
                            // echo Mensajes::mensajeError($msj);exit;
                            if($type==1){
                          
                                #Mensajes::msjExcepcion(Mensajes::mensajeError($msj),'/excepcion/');
                                return Mensajes::mensajeError($msj); 
                            }elseif($type==2){
                               if(!isset($_SESSION['__msjExcepcion']) or Session::get('__msjExcepcion')!=$e->getMessage()){
                                   
                                    $mensaje = Mensajes::mensajeError($msj);
                                    echo $mensaje;   
                                    Session::set('__msjExcepcion',$e->getMessage());
                               } 
                                 
                                
                            }
                            
                                                 
                            break;
                        case 'prod':
                                                        
                            break;
                        
                        default:
                            
                            break;
                    }
                }else{
                    throw new Exception("No se encuentra definido el entorno de la aplicación", 10);
                    
                }
            }else{
                throw new Exception("Ha ocurrido un error grave.", 1);
                
            }
       
        
    }//final constructor
    
    static function controlExcepcionUser(Exception $e){
        $msj.="<h3>Ha ocurrido un error!</h3><hr>";
        $msj.=$e->getMessage();
        return Mensajes::mensajeError($msj);
    }
    
    /**
     * Envia un error capturado via mail
     * @method sendExecpcionMail
     */
    static function mailError(Exception $e){
         $msj = '<h3>Error Capturado!</h3><hr>';
            $msj.="<strong>Mensaje : </strong>".$e->getMessage()."<br>";
            $msj.="<strong>L&iacute;nea : </strong>".$e->getLine()."<br>";
            $msj.="<strong>Archivo : </strong>".$e->getFile()."<br>";
            $msj.="<strong>C&oacute;digo : </strong>".$e->getCode()."<br>";
            $msj.="<hr/><div style=\"font-size:10px\">Traza</br>";
            #$msj.="<strong>Traza : </strong>".implode(",",$e->getTrace())."<br>";
            foreach($e->getTrace() as $key =>$traza){
                $msj.="<strong>Archivo</strong> ". $traza['file']."<br>";
                $msj.="<strong>Linea</strong> ".$traza['line']."<br>";
                $msj.="<strong>Funcion</strong> ".$traza['function']."<br>";
                #$msj.="<hr>";
            }
            $msj.="</div>";
            return $msj;
        
        if(defined('MAIL_ADMIN')){
            // $mail = new EmailComponente();
            // $mail->setTemplatePath('jidaPlantillas/mail/');
            // $data=array(':detalle_error'=>$msj,':aplicacion'=>'Electron C.A');
            // $mail->enviarEmail(MAIL_ADMIN, $e->getMessage(), $data,'error.tpl.php' );
        }
        
        //error_log($msj,1,MAIL_ERROR_APP);
    }
    
} // END

?>