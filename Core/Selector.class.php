<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category
 * @version
 */

 
 
 
class Selector extends DBContainer{
     /**
      * Define el selector a crear
      * @var $selector
      * @access public
      */
     var $selector="";   
    function __construct($selector){
        $this->selector=$selector;
    }
    /**
     * Genera un selector HTML
     * @method crear
     * @param string $selector
     *          Nombre Etiqueta HTML a crear
     * @param array $atributos
     *          Arreglo de atributos para el selector
     * @param string $content Contenido del selector
     */
    public static function crear($selector, $atributos = array(), $content = "") {
       $selectorHTML = "<$selector";
        if (is_array($atributos) and array_key_exists ( 'content', $atributos )) {
            
            $content = $atributos ['content'];
            unset ( $atributos ['content'] );
        }
        if(is_array($atributos)){
            foreach ( $atributos as $key => $value ) {
                
                $selectorHTML .= " $key=\"$value\"";
            }    
        }
        if(!in_array($selector,array('img','hr','br'))){
            $selectorHTML .= ">$content";
            $selectorHTML .= "</$selector>";    
        }else{
            $selectorHTML.="/>";
        }
        
        
        return $selectorHTML;
    }
    
    /**
     * Crea una lista OL con estilo bootstrap de breadcrumb
     * @param array $data
     * @return string $html Código HTML generado
     */
    public static function crearBreadCrumb($data,$class='breadcrumb'){
        $lista="";
        foreach ($data as $key => $value) {
            
            $link = self::crear('a',array('href'=>$value['link']),$value['html']);
            $lista.= self::crear('li',null,$link);
        }
        $html = self::crear('ol',array('class'=>$class),$lista);
        
        return $html;
    }
    /**
     * Genera el codigo HTML de una Lista ul
     * @param $css Estilo css desado para selector ul
     * @param array Arreglo de contenido de la lista, debe contener al menos una clave "content"
     * @example array('content'=>array(uno,dos,tres,cuatro))
     * @example array('selectorInterno'=>'img','content'=>array(...))
     */
     
    public static function crearLista($css,$content){
        $lista = "";
        if(is_array($content)){
            if(array_key_exists('content', $content)){
                foreach($content['content'] as $key => $content){
                    if(array_key_exists('selector', $content)){
                        $selector = $content['selector'];
                        $lista.=self::crear($selector['label'],$se);       
                    }
                }       
            }else{
                throw new Exception("No se ha definido el arreglo de contenido para la lista", 1);
                
            }
                 
        }
    }
    
}


?>