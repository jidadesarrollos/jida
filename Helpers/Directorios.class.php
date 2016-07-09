<?PHP
/**
 * Helper para manejo de Archivos y directorios
 *
 * @package Framework
 * @category Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class Directorios extends Directory{


	/**
     * Verifica si un directorio existe, hace uso de funcion file_exists de PHP
     * @method validar
     * @param string $dir Ubicacion de la carpeta o archivo
     * @see PHP file_exists
     */
	static function validar($dir){
	    if(file_exists($dir)){
	        return true;
	    }else{
	        return false;
	    }
	}


    /**
     * Crea un directorio
     * @method crear
     * @param mixed $directorio String o Arreglo de Directorios a crear
     */
    static function crear($directorio,$mode=0777){
        if(is_array($directorio)){
            foreach ($directorio as $key => $dir) {
            	if(!self::validar($dir))
                	mkdir($dir,$mode,TRUE);
            }
        }else{
            if(!file_exists($directorio)){
                mkdir($directorio,$mode,TRUE);
            }
        }
    }

	static function listar($ruta){
		$listado=[];
		if(is_dir($ruta)){
			if($directorio = opendir($ruta)){
				while (($file = readdir($directorio)) !== false) {
					if($file!="." and $file!='..' and $file!='TP_LINK Consumo'){

						$listado[]=$file;
					}

				}
			}
		}
		return $listado;
	}
    /**
    * Funcion que recorre y lista todos archivos segun el patron contenido en $expReg
    *
    * @param string $ruta   # directorio a recorrer
    * @param string $arr    # arreglo que guarda los archivos recorridos
    * @param string $expReg # expresion regular para filtrar por el nombre del archivo
    * @param string $i  # indice

    * @return $arr array con todos los controladores que coincidan con $expReg
    */
    static public function listarDirectoriosRuta($ruta,&$arr,$expReg='',&$i=0){
    // Abrir un directorio y listarlo recursivamente
        if (is_dir($ruta)) {
            if ($directorio = opendir($ruta)) {
                while (($file = readdir($directorio)) !== false) {
                // Listamos todo lo que hay en el directorio, mostraría tanto archivos como directorios
                    if(empty($expReg)){
                    // Guardo todos los archivos recorridos
                    	if($file!="." and $file!="..")
                        	$arr[$i] = $file;++$i;
                    }else{
                    // Guardo los archivos que coincidan con la expresion regular
                        $esCoincidencia = (preg_match($expReg,$file))?1:0;
                        if($esCoincidencia){

                            $arr[$i] = Cadenas::removerAcentos($file);++$i;
                        }
                    }
                    if (is_dir($ruta . $file) && $file!="." && $file!=".."){
                    // Solo si el archivo es un directorio, distinto a "." y ".."
                    // echo "<br>Directorio: $ruta$file<hr>";
                    self::listarDirectoriosRuta($ruta . $file . "/",$arr,$expReg,$i);
                    }
                }//fin while
                closedir($directorio);
            }//fin if openRuta
        }else{
            throw new Exception("La ruta a listar no es una ruta valida $ruta", 333);
        }
        return $arr;
    }
    /**
     * Recorre un directorio y aplica una funcion por cada archivo encontrado en el directorio
     * @param string $ruta URL del directorio
     * @param mixed $callback funcion o nombre de función a ejecutar, se le pasara como parametro el nombre del archivo
     * @param boolean $recursive Si es colocado en TRUE la función se aplicara en los subdirectorios
     */
    static function recorrerDirectorio($ruta,$callback,$recursive=FALSE){
        // Abrir un directorio y listarlo recursivamente
        if (is_dir($ruta)) {
            if ($directorio = opendir($ruta)) {
                while (($file = readdir($directorio)) !== false and ($file!="." && $file!="..")) {
                    $callback($file,$ruta);
                    if($recursive===TRUE){
                        if (is_dir($ruta . $file) && $file!="." && $file!=".."){
                            // Solo si el archivo es un directorio, distinto a "." y ".."
                            self::listarDirectoriosRuta($ruta . $file . "/",$arr,$expReg,$i);
                        }
                    }

                }//fin while
                closedir($directorio);
            }//fin if openRuta
        }else{
            throw new Exception("La ruta a listar no es una ruta valida $ruta", 333);
        }



    }
    /**
     * Elimina un directorio y su contenido
     *
     * Se debe tener cuidado de su uso pues elimina absolutamente todo lo contenido en la carpeta pasada
     * @method eliminar
     */
    static function eliminar($dir){
        foreach(glob($dir . "/*") as $files){
            if (is_dir($files)){
                eliminarDir($files);
            }else{
                unlink($files);
            }
        }

        rmdir($dir);
    }

    /**
     * Limpia un directorio
     *
     * Elimina todo lo que exista dentro de un directorio
     * @method limpiar
     * @param url $directorio Ubicación del directorio a limpiar
     */
    static function limpiar($dir){
       foreach(glob($dir . "/*") as $files){
            if (is_dir($files)){
                eliminarDir($files);
            }else{
                unlink($files);
            }
        }
    }
    /**
     * Cuenta los archivos en un directorio
     * @param string $ruta Ruta del directorio
     * @patrom Patron para contar Ejemplo {*.jpg,*.gif,*.png}
     */
    static function getTotalArchivos($ruta){
        $totalArchivos = 0;
        if ($handle = opendir($ruta)) {
            while (($file = readdir($handle)) !== false){
                if (!in_array($file, array('.', '..')) && !is_dir($ruta.$file))
                $totalArchivos++;
            }
        }
        return $totalArchivos;


    }
	/**
	 * Copia el contenido de un directorio a otro
	 */
	static function copiar($origen,$destino){
		if(is_dir($origen) and is_readable($origen)){
			if(!self::validar($destino)) self::crear($destino);
			$origenDir = dir($origen);
			while (($file=$origenDir->read())!==FALSE) {
				if($file=='.' or $file=='..') continue;
				if(is_dir($origenDir->path.$file)){
					self::copiar($origen.'/'.$file, $destino.'/'.$file);
					continue;
				}else
					copy($origen.'/'.$file,$destino.'/'.$file);
				
					
			}
		}
	}


} // END