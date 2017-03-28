<?PHP
/**
 * Helper para manejar Archivos
 *
 * @package Framework
 * @category Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */

namespace Jida\Helpers;
use Jida\Helpers\Debug as Debug;
use \Exception;
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
    /**
     * @var array $files Array $_FILES
     */
    protected $files;
    /**
     * Determina la existencia de un archivo
     * @var boolean $existencia
     */
    protected $existencia=FALSE;
    /**
     * Define el directorio de ubicacion del archivo
     */
    protected $directorio;
    function __construct($file=""){
        if(!empty($file) and array_key_exists($file, $_FILES))
            $this->checkCarga($_FILES[$file]);
        else {

            if(!empty($file) and self::validarExistencia($file)){
                $this->directorio = $file;
                $this->existencia = TRUE;
            }
        }
    }

    /**
     * Instancia valores de un archivo cargado en la variable global $_FILES
     * @method checkCarga
     * @access public
     * @since 0.1
     *
     */
    private function checkCarga($file){

        $this->files = $file;
        if(!isset($file) or is_array($file)){

            $this->name  = $file['name'];
            $this->type = $file['type'];
            $this->tmp_name = $file['tmp_name'];
            $this->error = $file['error'];
            $this->size = $file['size'];
			if(!empty($this->name)){
            	$this->obtenerExtension();

            	$this->totalArchivosCargados = count($file['tmp_name']);
			 	$this->validarCarga();
			}


        }
    }//fin checkCarga

    /**
     * Verifica la carga de uno o varios archivos
     * @method validarCarga
     * @access public
     * @since 0.1
     *
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
     * @access public
     * @since 0.1
     *
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
		  switch ($explode[1]){
	        case 'pdf':
	            $mime = 'pdf';
	            break;
	        case 'msword':
	            $mime = 'doc';
	            break;
	        case 'vnd.openxmlformats-officedocument.wordprocessingml.document':
	            $mime = 'docx';
	            break;
	        case 'vnd.ms-excel':
	            $mime = 'xls';
	            break;
	        case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
	            $mime = 'xlsx';
	            break;
	        case 'vnd.ms-powerpoint':
	            $mime = 'ppt';
	            break;
	        case 'vnd.openxmlformats-officedocument.presentationml.presentation':
	            $mime = 'pptx';
	            break;
	        case 'plain':
	            $mime = 'txt';
	            break;
			default: $mime = $explode[1];
				break;
	    }

		  $this->extension[0] = $mime;
        }

	}
    /**
     * Mueve un archivo cargado a una nueva ubicacion
     *
     * @method moverArchivo
     * @param string $directorio Url en la cual se movera el archivo
     * @param mixed $nombreArchivo Archivo a mover
     * @access public
     * @since 0.1
     *
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
     * @access public
     * @since 0.1
     *
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
     * @param $nombreAleatorio Indica si el nombre del archivo será aleatorio, sl es pasado false se colocara
     * el mismo nombre que contenga el archivo
     * o se validara el array NombresArchivosCargados, si es pasado true se colocará un nombre aleatorio
     * @param string $prefijo Si nombreAleatorio es pasado en true, puede definirse un prefijo
     * para agregar antes de la parte aleatoria del nombre del archivo
     * @return object
     * @access public
     * @since 0.1
     *
     */
    function moverArchivosCargados($directorio,$nombreAleatorio=FALSE,$prefijo=""){
        $bandera=TRUE;

        if($this->totalArchivosCargados>1 or is_array($this->tmp_name)){
            for($i=0;$i<$this->totalArchivosCargados;++$i){
            	
                $nombreArchivo = $this->validarNombreArchivoCargado($i, $nombreAleatorio,$prefijo);
                $destino =$directorio."/". $nombreArchivo;//validarNombreAC devuelve el nombre con la ext.
                $this->archivosCargados[]=[
                    'directorio'=>$directorio,
                    'path'=>$destino,
                    'nombre'=>$nombreArchivo,
                    'extension'=>$this->extension[$i]
                ];

                if(!move_uploaded_file($this->tmp_name[$i],$destino)){
                	
                    if(!is_writable($directorio)){
                        throw new Exception("No tiene permisos en la carpeta $directorio", 900);
                    }else
                    throw new Exception("No se pudo mover el archivo cargado $destino", 902);

                }

            }
        }else{
            $nombreArchivo= $this->validarNombreArchivoCargado(0, $nombreAleatorio,$prefijo);
            $destino  =$directorio. "/".$nombreArchivo;
            $this->archivosCargados[]=[
                'path' => $destino,
                'directorio'=>$directorio,
                'nombre'=>$nombreArchivo,
                'extension'=>$this->extension[0]
            ];

            if(!(Directorios::validar($directorio))){
                Directorios::crear($directorio);
            }
            if(!move_uploaded_file($this->tmp_name,$destino)){


                if(!is_writable($directorio)){
                    throw new Exception("No tiene permisos en la carpeta $directorio", 900);
                }else
                    throw new Exception("No se pudo mover el archivo cargado $destino", 902);
            }
        }
        return $this;
    }
    /**
     * Devuelve el nombre a asignar al archivo cargado en el servidor
     *
     * @internal El nombre puede ser definido por el usuario por medio de la función setNombresArchivosCargados,
     * puede ser creado aleatoriamente o [por defecto] es usado el mismo nombre del archivo original, reemplazando
     * los espacios por guiones [-]
     * @method validarNombreArchivoCargado
     * @param int $numero Número del archivo cargado
     * @param boolean $aleatorio. Define si el archivo es cargado de forma aleatoria o no
     * @param $prefijo Prefijo agregado al archivo cuando el nombre es aleatorio.
     * @access public
     * @since 0.1
     *
     */
    private function validarNombreArchivoCargado($numero,$aleatorio,$prefijo=""){

        if($aleatorio){
            $fecha = md5(Date('U'));
            $ramdon = rand(100000,999999);
            $name = $fecha.$ramdon;
            if(!empty($prefijo)) $name = $prefijo."-".$name;

            return $name.".".$this->extension[0];
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
     * Verifica la existencia de un archivo
     *
     * Hace uso de la funcion file_exists para ello
     * @method validarExistencia
     */
    function validarExistencia($file=""){
        if(empty($file))$file=$this->directorio;
        if(file_exists($file)){
            return true;
        }else{return false;};
    }
    /**
     * Crea un archivo
     *
     * @internal Genera un archivo con el contenido indicado y lo guarda en la ruta
     * señalada.
     * @method crearArchivo
     * @access static
     * @param string $nombreArchivo Nombre del archivo a crear
     * @param string $contenido Contenido del archivo a crear
     * @param string $ruta Ruta donde debe ser guardado el archivo
     * @access public
     * @since 0.1
     *
     */
    static function crearArchivo($nombreArchivo,$contenido,$ruta){

    }//final funcion crearArchivo

    /**
     * Elimina un archivo
     * @method eliminar
     */
    function eliminar($dir){
        if(unlink($dir)){
            return true;
        }else{
            throw new Exception("No se puede eliminar el directorio $dir", 1);

            return false;
        }
    }

    /**
     * Elimina varios archivos
     * @method	eliminarMultiplesArchivos
  	 * @param	$arr de direccion fisica de archivos a eliminar
  	 * @return	boolean o array de elementos no eliminados
     * @access public
     * @since 0.1
     *
     */
    static function eliminarMultiplesArchivos($arr){
		if(is_array($arr)){
			$noEliminados=[];
			foreach ($arr as $key => $value):
				if(!unlink($value))
					$noEliminados[]=$value;
			endforeach;

			if($noEliminados>0)
				return $noEliminados;
			else
				return true;
		}else{
			throw new Exception("Debes pasar un arreglo", 1);
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
    /**
     * Retorna el valor de existencia de un Archivo
     * @method existe;
     * @access public
     * @since 0.1
     *  
     */
    function existe(){
        return $this->existencia;
    }

    static function obtPeso($img,$unidad = 'mb'){
        $cantidades = ['mb'=>'1048576','kb'=>'1024',"b"=>'1'];
        $bytes="";

        if(array_key_exists($unidad, $cantidades)){
            return number_format(filesize($img) / $cantidades[$unidad],2);

        }else {

            return false;
        }
    }
} // END
