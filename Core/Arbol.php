<?PHP 
/**
 * Clase para manejo de Arreglos con estructura de arbol
 * 
 */

namespace Jida\Core;
class Arbol{
    
    var $keyPadre;
    var $keyHijo;
    var $padres;
    var $arbol;
    /**
     * @param array $arbol Arreglo con estructura de arbol
     * @param string $keyPadre Nombre del campo Clave Padre
     * @param string $keyHijo Nombre del campo Clave hijo
     */
    function __construct($arbol,$keyPadre='padre',$keyHijo='hijos'){
        $this->arbol=$arbol;
        $this->keyPadre=$keyPadre;
        $this->keyHijo=$keyHijo;
    }
    /**
     * Reorganiza la estructura del arbol colocando los key 
     * principales con el valor $clave pasado
     * @method estructurarArbolById 
     * @param string $clave Nombre de la clave a usar como indice de primer nivel
     */
    function estructurarArbolById($clave){
        $estructura=array();
        foreach ($this->arbol as $key => $value) {
            if(array_key_exists($clave, $value)){
                $estructura[$value[$clave]]=$value;
            }
        }
        $this->arbol=$estructura;
        
    }
    /**
     * Devuelve el arbol a partir de un indice dado
     * @method obtenerArbol
     * @param mixed Id o clave principal desde donde se comienza a crear el arbol
     * @return array Arbol obtenido
     */
    function obtenerArbol($id){
        $arbol = array();
        
        if(array_key_exists($id, $this->arbol)){
            $i = 0;
            $arbol[$i] = $this->arbol[$id];
            $padre  =$arbol[$i][$this->keyPadre];
            if($padre!=0){
                while($padre!=0){
                    if(array_key_exists($padre, $this->arbol)){
                        $arbol[$i]=$this->arbol[$padre];
                        $padre = $this->arbol[$padre][$this->keyPadre];
                    }
                    $i++;
                }//fin while
            }
            return $arbol;
        }else{
            throw new Exception("No existe la clave $id en el arreglo", 200);
        }
        
        
        
    }
}
?>
