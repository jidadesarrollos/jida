
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
	var $layoutPropio =TRUE;
    var $layoutDefault="";
    var $layoutModulos = [];
    
    protected $moduloActual;
    protected $controladorError=FALSE;
	protected $modulos=[];
    
    var $layoutError ="";
    function __construct($e,$ctrlError=""){
        
        if(is_object($ctrlError)) $this->controladorError=new $ctrlError;
        
        $this->layoutDefault = LAYOUT_DEFAULT;
        $this->excepcion = $e;
        parent::__construct();
		if(array_key_exists('modulos', $GLOBALS))
        	$this->modulos = $GLOBALS['modulos'];
        
        $this->moduloActual = $this->_modulo;
        
        //if($this->moduloActual=='Jadmin') $this->processJadminError();
        if(array_key_exists($this->moduloActual, $this->layoutModulos)){
            
        }else{
            $this->layoutPropio=FALSE;
            $this->layout = LAYOUT_DEFAULT;
        }
    }
    
    protected function validarControllerError(){
        $ctrlError = new $this->controladorError();
        $this->layoutError = $ctrlError->layout;
    }
    /**
     * Funcion por defecto para manejar
     * las excepciones existentes en el entorno de desarrollo
     * @method estandard
     * @param object $message
     * @return boolean truesd
     */
    function error($e=""){
        if($e instanceof Exception) $this->excepcion=$e;
        
        $this->dv->msjError = $this->procesarError();
        if(!$this->layoutPropio and is_object($this->controladorError)){
            
            $this->layout=$this->controladorError->layout;
        }else{
            $this->layout=LAYOUT_DEFAULT;
        }
        
    }
    function procesarError($view=""){
          
        if(!empty($view)){
          
               $this->vista=$view;        
         }else{             
         	$this->dv->title="Error ".$this->excepcion->getCode();
            $this->dv->setVistaAsTemplate('error','jida');
         }
         $msj  = $this->getDetalleExcepcion();
         
         return $msj; 
        
            
    }
    /**
     * Obtiene el detalle de la excepción
     * @method getDetalleExcepción
     */
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
    /**
     * Retorna la excepción en formato HTML
     * @method getHTMLMessage
     */
    function getHTMLMessage(){
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
    function excepcionProduccion(){
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




