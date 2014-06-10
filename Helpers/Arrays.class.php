<?PHP 

/**
 * Clase Helper de Arreglos
 *
 * @package Framework
 * @subpackage Helpers
 * @author  Julio Rodriguez <jirc48@gmail.com>
 */
class Arrays {
	
	
	
	static function mostrarArray($ar){
		echo "<pre style=\"background:black;color:#dcdcdc\">";
		print_r($ar);
		echo "</pre>";
		
	}
    /**
     * Recorre un arreglo multidimensional buscando las columnas solicitadas y devuelve
     * un nuevo arreglo solo con esos valores
     * @method getColummnasArray
     * @param array $array Arreglo a recorrer
     * @param array $columnas Arreglo con columnas deseadas
     * @return array $data Arreglo creado solo con los valores solicitados
     */
    static function getColumnasArray($array,$columnas){
        $datos=array();
        foreach ($array as $key => $columna) {
            
        }
        
    }
    
    static function recorrerArray(){}
    
    /**
     * Recorre un array recursivo buscando los valores solicitados
     * 
     * Recorre un arreglo de forma recursiva buscando todos los valores que coincidan
     * con una clave dada y retorna un nuevo arreglo ordenado con las posiciones relacionadas
     * a la clave
     * @param array $arr Arreglo a recorrer
     * @param string $busqueda Nombre o valor a buscar
     * @param string $filtro Campo de filtro en estructura del arreglo
     */
    static function obtenerHijosArray($arr,$busqueda,$filtro){
        $nuevoArreglo = array();
        
        foreach ($arr as $key => $value) {
                if($value[$filtro]==$busqueda){
                    $nuevoArreglo[]=$value;   
                }
                    
        }//fin foreach
        if(is_array($nuevoArreglo)){
            
            //echo "SI<hr>";
        }
        
        if(count($nuevoArreglo)>0){
            //echo "SI again<hr>";
            return $nuevoArreglo;
        }else{
            return false;
        }
        
    }
	
} // END

?>