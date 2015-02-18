<?PHP 
/**
 * Helper para manejar Archivos
 *
 * @package Framework
 * @category Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class Archivo{
    /* Atributos para archivos cargados */
    /**
     * @var mixed $name String o arreglo de nombres originales de los archivos cargados
     */
    protected $name;
    /**
     * @var mixed $type Tipo de Archivo Cargado
     */
    protected $type;
    /**
     * @var mixed $size Tamaño de un archivo cargado
     */
    protected $size;
    /**
     * @var string $tmp_name Nombre temporal de un archivo cargado
     */
    protected $tmp_name;
    protected $error;
    /**
     * @var mixed $extension extension o arreglo con extensiones de los archivos
     */
    protected $extension;
    /**
     * Define si un archivo a sido subido al servidor exitosamente
     */
    protected $cargaRealizada="";
    /**
     * @var int $$totalArchivosCargados Total de archivos cargados
     */
    protected $totalArchivosCargados;
    
    /**
     *  @var mixed $nombreArchivosCargados Arreglo con los nombres a agregar a los archivos cargados, false
     * si no son creados. 
     */    
    protected $nombresArchivosCargados=FALSE;
    /**
     * @var array $archivosCargados Registra los archivos cargados 
     * 
     */
    protected $archivosCargados = array();
    
    function __construct($file=""){
        if(!empty($file) and array_key_exists($file, $_FILES))
            $this->checkCarga($_FILES[$file]);
    }
    
    /**
     * Instancia valores de un archivo cargado en la variable global $_FILES
     * @method checkCarga
     */
    private function checkCarga($file){
        if(!isset($file) or is_array($file)){
            
            $this->name  = $file['name'];
            $this->type = $file['type'];
            $this->tmp_name = $file['tmp_name'];
            $this->error = $file['error'];
            $this->size = $file['size'];
            $this->obtenerExtension();
            $this->totalArchivosCargados = count($file['tmp_name']);
            $this->validarCarga();    
            
        }
    }//fin checkCarga
    
    /**
     * Verifica la carga de uno o varios archivos
     * @method validarCarga
     */
    function validarCarga(){
        
        $totalCarga = (is_array($this->tmp_name))?count($this->tmp_name):1;
        $archivosCargados = 0;
        if(is_array($this->tmp_name)){
            foreach ($this->tmp_name as $key) {
                
                if(is_uploaded_file($key));
                    ++$archivosCargados;
            }//fin foreach
        }else{
            if(is_uploaded_file($this->tmp_name))
                ++$archivosCargados;
        }
        if($totalCarga==$archivosCargados){
            $this->totalArchivosCargados=$archivosCargados;
            return TRUE;
        }else{
            return false;
        }
        
    }
    
    
	/**
     * obtiene la extensión de un archivo
     * @method obtenerExtension
     */
	   private function obtenerExtension(){
        if(is_array($this->type)){
            $i=0;
            foreach ($this->type as $key ) {
                $explode= explode("/",$key);
                
                if($explode[0]=='application'){
                    $this->extension[$i] = substr($this->name[$i],strrpos($this->name[$i],".")+1);
                }else
                    $this->extension[$i] = $explode[1];
                $i++;
            }
            
        }else{
          $explode = explode("/",$this->type);
		  $this->extension = $explode[1];
        }
		
	}
    /**
     * Mueve un archivo cargado a una nueva ubicacion
     * 
     * @method moverArchivo
     * @param string $directorio Url en la cual se movera el archivo
     * @param mixed $nombreArchivo Archivo a mover
     */
    function moverArchivoCargado($directorio,$nombreArchivo){
        
		if(move_uploaded_file($nombreArchivo, $directorio)){
			return true;
		}else{
			return false;
		}
		
		
	}
    /**
     * Retorna el número de archivos cargados
     * @method getTotalArchivosCargados
     * @return int see::archivosCargados
     */
    function getTotalArchivosCargados(){
        return $this->totalArchivosCargados;
    }
    /**
     * Permite editar los nombres para archivos cargados
     * 
     */
    function setNombresArchivosCargados($nombresArchivos){
        $this->nombresArchivosCargados = $nombresArchivos;
    }
    /**
     * Mueve los archivos cargados por $_FILES a ur directorio especificado
     * @method moverArchivosCargados
     * @param string $directorio Directorios al cual serán movidos
     * @param $nombreAleatorio Indica si el nombre del archivo será aleatorio, sl es pasado false se colocara el mismo nombre que contenga el archivo
     * o se validara el array NombresArchivosCargados, si es pasado true se colocará un nombre aleatorio
     * @param string $prefijo Si nombreAleatorio es pasado en true, puede definirse un prefijo
     * para agregar antes de la parte aleatoria del nombre del archivo
     * @return object
     */
    function moverArchivosCargados($directorio,$nombreAleatorio=FALSE,$prefijo=""){
        $bandera=TRUE;
        if($this->totalArchivosCargados>=1){
            for($i=0;$i<$this->totalArchivosCargados;++$i){
                $nombreArchivo = $this->validarNombreArchivoCargado($i, $nombreAleatorio,$prefijo);
                $destino =$directorio."/". $nombreArchivo.".".$this->extension[$i];
                $this->archivosCargados[]=[
                    'directorio'=>$directorio,
                    'path'=>$destino,
                    'nombre'=>$nombreArchivo,
                    'extension'=>$this->extension[$i]
                ];
                if(!move_uploaded_file($this->tmp_name[$i],$destino))
                    throw new Exception("No se pudo mover el archivo cargado", 900);   
            }    
        }else{
            $nombreArchivo= $this->validarNombreArchivoCargado(1, $nombreAleatorio,$prefijo);
            $destino  =$directorio. "/".$nombreArchivo.".".$this->extension;
            $this->archivosCargados[]=[
                'path' => $destino,
                'directorio'=>$directorio,
                'nombre'=>$nombreArchivo,
                'extension'=>$this->extension
            ];  
            if(!move_uploaded_file($this->tmp_name,$destino))
                throw new Exception("No se pudo mover el archivo cargado", 900);
        }  
        return $this;
    }
    /**
     * Devuelve el nombre a asignar al archivo cargado en el servidor
     * 
     * El archivo puede ser definido por el usuario por medio de la función setNombresArchivosCargados,
     * puede ser creado aleatoriamente o [por defecto] es usado el mismo nombre del archivo original, reemplazando
     * los espacios por guiones [-]
     * @method validarNombreArchivoCargado
     * @param int $numero Número del archivo cargado
     * @param boolean $aleatorio. Define si el archivo es cargado de forma aleatoria o no
     * @param $prefijo Prefijo agregado al archivo cuando el nombre es aleatorio.
     */
    private function validarNombreArchivoCargado($numero,$aleatorio,$prefijo=""){
        
        if($aleatorio){
            $fecha = md5(Date('U'));
            $ramdon = rand(100000,999999);
            $name = $fecha.$ramdon;
            if(!empty($prefijo)) $name = $prefijo."-".$name;
            return $name;
        }else{
            
            if(is_array($this->nombresArchivosCargados)){
                if(!array_key_exists($numero, $this->nombresArchivosCargados))
                    throw new Exception("no existe la clave solicitada", 901);
                return $this->nombresArchivosCargados[$numero];
            }else{
                if(is_array($this->name))
                    return str_replace(" ", "-", $this->name[$numero]);
                else
                    return str_replace(" ", "-", $this->name);
            }    
        }
        
    }
    function getArchivosCargados(){
        return $this->archivosCargados;
    }

	/**
     * Obtiene un archivo
     * 
     * Usa file_get_contents para devolver el archivo como un string
     */
    static function obtArchivo($rutaArchivo){
        
        if(is_readable($rutaArchivo)){
            
        }else{
            throw new Exception("La ruta del archivo no es legible : ".$rutaArchivo);
            
        }
        
    }
    
    /**
     * Crea un archivo 
     * 
     * Genera un archivo con el contenido indicado y lo guarda en la ruta
     * señalada.
     * @method crearArchivo
     * @access static
     * @param string $nombreArchivo Nombre del archivo a crear
     * @param string $contenido Contenido del archivo a crear
     * @param string $ruta Ruta donde debe ser guardado el archivo
     */
    static function crearArchivo($nombreArchivo,$contenido,$ruta){
        
    }//final funcion crearArchivo
    
    /**
     * Elimina un archivo
     * @method eliminar
     */
    function eliminar(){
        if(unlink($dir)){
            return true;
        }else{
            throw new Exception("No se puede eliminar el directorio $dir", 1);
            
            return false;
        }
    }
    
    /**
     * @method eliminarArchivo
     * @deprecated
     */
    static function eliminarArchivo($dir){
    	if(unlink($dir)){
    		return true;
    	}else{
    	    throw new Exception("No se puede eliminar el directorio $dir", 1);
			
    		return false;
    	}
    }

    
    
} // END