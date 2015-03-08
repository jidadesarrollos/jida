<?PHP 
/**
 * Definición de la clase
 * 
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package
 * @category
 * @version
 */
class Selector{
    
    
        
     /**
      * Define el selector a crear
      * @var $selector
      * @access public
      */
    var $selector="";
    var $id="";
    var $class="";
    var $style="";
    /**
     * Atributos data para el selector
     * @var array $data
     */
    var $data=array();
    /**
     * Arreglo para agregar atributos adicionales al selector
     * @var array $attr
     */
    var $attr=array();
    /**
     * Contenido del selector
     * @var mixed $contenido
     */
    var $contenido;
    /**
     * Contiene el HTML que se genera al crear el selector
     * @var string $selectorCreado;
     * 
     */
    private $selectorCreado;
    /**
     * Permite agregar propiedades adicionales al selector
     */
    private $propiedades=array();
    function __construct($selector=""){
        $this->selector=$selector;
    }
    /**
     * Genera el HTML del selector instanciado
     * @method getSelector
     * @access public
     * @param int $tabs Numero de tabulaciones ha imprimir
     */
    function getSelector($tabs=0){
        $s =& $this->selectorCreado;
        $tabulaciones = self::addTabs($tabs);
        $s = "\n".$tabulaciones;
        $s .="<".$this->selector;
        
        if(!empty($this->id)){
            $s.=" id=\"".$this->id."\"";
        }
        if(!empty($this->class)){
            $s.=" class=\"".$this->class."\"";
        }
        if(!empty($this->style)){
            
            $s.=" style=\"".$this->style."\"";
        }        
        $this->getElementosData();
        $this->getAttr();
        $s.=">\n".$tabulaciones."\t".$this->contenido."\n".$tabulaciones."</$this->selector>";
        
        return $this->selectorCreado;
        
    }//fin funcion
    /**
     * Verifica si existen elementos datas que deban ser agregados al selector
     * y los agrega
     * @method getElementosData
     * @access private;
     */
    private function getElementosData(){
        
        if(count($this->data)>0){
            
            if($this->selector=='TABLE'){
                #Debug::mostrarArray($this->data);
            }
            foreach ($this->data as $key => $value) {
                
                $this->selectorCreado.=" $key='".$value."'";
            }
        }
    }//fin funcion
    /**
     * Verifica si existen elementos datas que deban ser agregados al selector
     * y los agrega
     * @method getElementosData
     * @access private;
     */
    private function getAttr(){
        if(count($this->attr)>0){
            foreach ($this->attr as $key => $value) {
                $this->selectorCreado.=" $key=\"$value\"";
            }
        }
    }//fin funcion
    /**
     * Genera un selector HTML
     * @method crear
     * @param string $selector Nombre Etiqueta HTML a crear
     * @param array $atributos Arreglo de atributos para el selector
     * @param string $content Contenido del selector
     */
    public static function crear($selector, $atributos = array(), $content = "",$tabs=0) {
        $tabulaciones = self::addTabs($tabs);
        $selectorHTML ="".$tabulaciones;
        $selectorHTML .= "<$selector";
        // if (is_array($atributos) and array_key_exists ( 'content', $atributos )) {
//             
            // $content = $atributos ['content'];
            // unset ( $atributos ['content'] );
        // }
        if(is_array($atributos)){
            
            foreach ( $atributos as $key => $value ) {
                
                $selectorHTML .= " $key=\"$value\"";
            }    
        }
        if(!in_array($selector,array('img','hr','br','link','meta'))){
            
            if(!empty($content)){
                $selectorHTML .= ">\n".$tabulaciones."$content";
                $selectorHTML .= "\n".$tabulaciones."</$selector>";    
            }else{
                $selectorHTML.=">$content</$selector>";
            }
                
        }else{
            $selectorHTML.=" />";
        }
        
        
        return $selectorHTML."\n";
    }
    
    /**
     * Crea una lista OL con estilo bootstrap de breadcrumb
     * @param array $data
     * @return string $html Código HTML generado
     */
    public static function crearBreadCrumb($data,$config=[]){
        $default=[
            "keyLink"=>"link",
            "keyHTML"=>"html",
            "attrLI"=>[],
            "attrUL"=>["class"=>"breadcrumb"]
        ];
        $config=array_merge($default,$config);
        
        $lista="";
        foreach ($data as $key => $value) {
            $data = array_merge(["href"=>$value[$config['keyLink']]],$config['attrLI']);
            $link = self::crear('a',$data,$value[$config['keyHTML']]);
            $lista.= self::crear('li',null,$link);
        }
        $html = self::crear('ol',$config['attrUL'],$lista);
        
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
    
    static function crearUL($content,$attrUL=array(),$attrLi=array()){
        $li="";
        
        foreach ($content as $key => $item) {
            
            $li.=self::crear("li",$attrLi,$item);
        }
        
        return self::crear("UL",$attrUL,$li,2);
        
    }
      /**
     * Crea Un boton Input
     *
     * El valor por defecto es un submit, permite modificar los atributos
     * del control a crear por medio de un arreglo asociativo con los
     * datos que se desean.
     *
     * @param string $value
     *          Va a ser el valor mostrado en el "value" del boton
     * @param array $valores
     *          arreglo de atributos personalizados.
     *          
     */
    public static function crearInput($value, $valores = "") {
        $valoresXDefecto = array (
            'type' => 'submit',
            'name' => "btn" . ucwords ( str_replace ( " ", "", $value ) ),
            'id' => "btn" . ucwords ( str_replace ( " ", "", $value ) ),
            'value' => $value 
        );
        $arrAtributos = (is_array ( $valores )) ? array_merge ( $valoresXDefecto, $valores ) : $valoresXDefecto;
        
        $control = "<input";
        foreach ( $arrAtributos as $atributo => $valorAtributo ) {
            $control .= " $atributo=\"$valorAtributo\"";
        }
        $control .= ">";
        
        return $control;
    }
    protected function establecerAtributos($arr, $clase="") {
        if(empty($clase)){
            $clase=__CLASS__;
        }
        
        $metodos = get_class_vars($clase);
        foreach($metodos as $k => $valor) {
            
            if (isset($arr[$k])) {
                $this->$k = $arr[$k];
            }
        }
    
    }
    static function addTabs($nums){
        $tabs = "";
        for($i=0;$i<$nums;++$i):
            $tabs.="\t";
        endfor;
        return $tabs;
    }
}


