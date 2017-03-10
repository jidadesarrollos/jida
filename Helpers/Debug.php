<?PHP
/**
 * Clase con funcionalidades generales que permiten al programador hacer tests
 *
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category Helpers
 * @version 0.1
 */

namespace Jida\Helpers;
class Debug{

    /**
     * @internal Muestra el contenido de un arreglo envuelto en tag <pre>
     * @method mostrarArray
     * @access public
     * @since 0.1
     *
     */
    static function mostrarArray($ar,$exit=true){
        echo "\n<pre style=\"background:black;color:#dcdcdc\">\n";
        print_r($ar);
        echo "</pre>";
        if($exit==TRUE){
            exit;
        }
    }

	/**
     * Muestra el contenido de las variables pasadas como parametros en bloques de impresion
	   *
	   * @internal Mantiene la ejecucion a menos que reciba como parametro explicito true
     *
     * @access public
     * @since 0.1
     *
     */
	static function imprimir(){
		$numero = func_num_args();
		for($i=0;$i<$numero;++$i)
		{
			$arg = func_get_arg($i);
			if(is_array($arg) or is_object($arg)) self::mostrarArray($arg,0);
			elseif(is_string($arg) or is_int($arg) or is_float($arg))
				self::string($arg,0);
			elseif(is_bool($arg) and $arg){
				exit;

			}
		}
	}

    /**
     * @internal Muestra el contenido de una variable String
     *
     * @access public
     * @since 0.1
     *
     */

    static function string($content,$exit=false,$tag="hr"){
        if(!is_array($content)){
            echo $content."<$tag/>";
            if($exit==TRUE){
                exit;
            }
        }elseif(is_array($content) or is_object($content)){
            self::mostrarArray($content,$exit);
        }
    }
}
?>
