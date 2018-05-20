<?php
/**
 * Autoload de clases del framework
 * 
 * @internal Clase para realizar cargas automaticas de clases
 * 
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * 
 * @version 0.1 - 27/12/2013
 * @package Framework
 * @subpackage core 
 * @deprecated 0.5
 * @since 0.1
 * 
 */
 
 namespace Jida\Core;
 class Autoload{
    public static $cargado;
    
    /**
     * Define los directorios de clases a incluir
     * @var array $directorios
     */ 
    var $directorios;
    public static function init() {
        if (self::$cargado == NULL)
            self::$cargado = new self();
        
        return self::$cargado;
    }
    
    
    function definirDirectorios(){
        
        $this->directorios = array(
            'BD/',
            'Core/',
            'Helpers/',
            'ControllerFramework/',
            'ModelFramework/',
            'Controller/',
            'Componentes/',
            'Modelos/',
            'Core/GeneradorCodigo/',
            'Render/'
        );
        
        if(isset($GLOBALS['modulos'] )){
            $modulos = $GLOBALS['modulos'];
            foreach($modulos as $key => $modulo){
                $total =count($this->directorios);
                if($modulo=="Jadmin"){
                    $this->directorios[$total+1]= $modulo ."/Controllers/";
                    $this->directorios[$total+2]= $modulo ."/Modelos/";
                }else{       
                    $this->directorios[$total+1]="Modulos/" . $modulo ."/Controller/";
                    $this->directorios[$total+2]="Modulos/" . $modulo ."/Modelos/";
                }
            }
        }
        
        
    }
    
    function __construct(){
        spl_autoload_register(array($this,'autocarga'));
        $this->definirDirectorios();    
    }
    
    
    function autocarga($clase){ 
        $dir = "";
        $bandera=FALSE;
        foreach ($this->directorios as $key => $directorio) {
            $archivo = $directorio . $clase .".class.php";
			$archivo2 = $directorio . $clase .".php";
            if($bandera!==TRUE){
                if(file_exists(DIR_FRAMEWORK . $archivo)){
                    require_once $archivo;
                    $bandera = TRUE;
                }elseif(file_exists(DIR_APP . $archivo)){
                    require_once $archivo;
                    $bandera = TRUE;
                }elseif(file_exists(DIR_FRAMEWORK . $archivo2)){
                	require_once $archivo2;
                    $bandera = TRUE;
            	}elseif(file_exists(DIR_APP . $archivo2)){
                	require_once $archivo2;
                    $bandera = TRUE;
            	}else{
                    $bandera=FALSE;
                    $dir = "$archivo";
                }
                    
            }
        }//fin foreach
        
        if($bandera===FALSE){
            Session::set("__errorAutoload", "Error en la autocarga, La clase $clase  no se encuentra en el directorio indicado<br>$dir");
        }
    }

 }
