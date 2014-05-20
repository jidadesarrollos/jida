<?php
/**
 * Crea Controles HTML
 *
 * Genera controles HTML con todas las especificaciones requeridas basada en estandards HTML5.
 *
 * Controles disponibles
 * <ul>
 * <li>HIDDEN</li>
 * <li>TEXT</li>
 * <li>SELECT</li>
 * <li>RADIO</li>
 * <li>CHECKBOX</li>
 * <li>TEXTAREA</li>
 * </ul>
 * Campos Compuestos:
 * <ul>
 * <li>Documentación(Para cedulas y rif)</li>
 * 
 * @package default
 * @author
 *
 */
class CampoHTML extends DBContainer {
    /**
     * Define un esquema de ubicacion de base de datos para la tabla
     * @access private
     * @var string $private
     */ 
    private $esquema="";  
        

    var $id_campo = "";
    var $id_form = "";
    var $label = "";
    var $name = "";
    var $maxlength = "";
    var $size = "";
    var $eventos = "";
    var $control = "";
    var $opciones = "";
    var $orden = "";
    var $id_propiedad = "";
    var $data_atributo = "";
    var $title = "";
    var $class = "";
    
    /**
     * Registra si el campo debe ser mostrado de forma normal, disabled o readonly
     * 
     * @var int $visibilidad
     *      <ul>
     *      <li>1 = Normal</li>
     *      <li>2 = Readonly</li>
     *      <li>3 = Disabled</li>
     *      </ul>
     *     
     */
    var $visibilidad = 1;
    
    /**
     * Define el atributo de visibilidad posible para el campo creado, basandose en los valores
     * que tenga la variable $visibilidad
     * 
     * @var $attrVisibilidad
     *
     */
    private $attrVisibilidad;
    
    /**
     * Define clase ccs obligatoria e independiente de la que agregue el programador
     */
    private $ccsControlRequerida = "form-control";
    
    private $attrTitle = "";
    
    private $atributosAdicionales = "";
    
    /* Valores nuevos */
    var $placeholder = "";
    
    private $value = "";
    
    // selects
    protected $multiple = "";
    
    protected $cn = "";
    
    protected $externo = "";
    
    private $typeForm = 1;
    
    private $valueUpdate = array ();
    private $valueUpdateMultiple=array();
    
    /**
     * Constructor
     * 
     * @param string $arr
     * @param string $arrValue
     * @param string $campoExterno
     */
    public function __construct($arr = "", $arrValue = "", $campoExterno = "") {
        parent::__construct ();
        $this->externo = $campoExterno;
        $this->nombreTabla = "s_campos_f";
        $this->momentoSalvado=FALSE;
        if(!empty($this->esquema)){
            $this->nombreTabla= $this->esquema .".". $this->nombreTabla;
        }
        
        $this->clavePrimaria = "id_campo";
        if (is_array($arrValue)) {
            $this->typeForm = 2;
            if(is_array($arrValue) and count($arrValue)>0){
                //Validar si el campo update es multi-selección.
                if(isset($arrValue[0]))
                    $this->valueUpdate=$arrValue[0];
                $this->valueUpdateMultiple=$arrValue;    
            }
        }
        $this->establecerAtributos ( $arr, __CLASS__ );
        $this->cn = $this->bd;
    }
    
    /**
     * 
     */
    private function setValueProperty() {
        if (isset ( $this->valueUpdate [$this->name] )){
            $validacion = json_decode("{".$this->eventos."}");
            
            if($validacion!==null and array_key_exists("decimal", $validacion) and !empty($this->valueUpdate[$this->name])){
                
                $this->value = number_format($this->valueUpdate[$this->name],2,",",".");
            }else{
                $this->value = $this->valueUpdate[$this->name]; 
            }
            
        }
    }
    
    /**
     * 
     */
    private function establecerControl() {
        $arrCampo = array (
            1 => "hidden",
            2 => "text",
            3 => "textarea",
            4 => "password",
            5 => "checkbox",
            6 => "radio",
            7 => "select",
            8 => "identificacion",
            9 => "Telefono",
            10=>"Fecha"
            
             
        );
        if(empty($this->control)){
            
        
            $this->control=2;
        }
        $this->type = $arrCampo [$this->control];
    }
    
    /**
     * 
     * @param string $arr
     * @return string
     */
    public function crearControl($arr = "") {
        if ($this->typeForm == 2){
            $this->setValueProperty();
        }
        
        $this->establecerControl();
        
        switch ($this->visibilidad) {
            case 1 :
                $this->attrVisibilidad = " ";
                break;
            case 2 :
                $this->attrVisibilidad = "readonly=\"readonly\"";
                break;
            case 3 :
                $this->attrVisibilidad = "disabled=\"disabled\"";
                break;
        }
        
        $class = (!empty($this->class)) ? "class=\"$this->ccsControlRequerida $this->class\"" : "class=\"$this->ccsControlRequerida\"";
        $this->attrTitle = (!empty($this->title)) ? " title=\"$this->title\" " : "";
        $data = ($this->data_atributo != "") ? " $this->data_atributo" : "";
        $this->atributosAdicionales = " " . $this->attrTitle . " " . $class . " " . $data . " " . $this->attrVisibilidad . " ";
        
        switch ($this->control) {
            case 3 :
                $this->control = $this->crearTextArea ();
                break;
            case 5 :
                $this->control = $this->crearCheck ();
                break;
            case 6 :
                $this->control = $this->crearRadio ();
                break;
            case 7 :
                $this->control = $this->crearSelect ();
                break;
            case 8 :
                $this->control = $this->crearControlIdentificacion();
                break;
            case 9:
                $this->control = $this->crearTelefono();
                break;
            case 10:
                $this->control = $this->crearInputDate();
                break;
            default :
                $this->control = "<input type=\"$this->type\" name=\"$this->name\" id=\"$this->id_propiedad\" " . trim ( $this->atributosAdicionales ) . " ";
                $this->control .= ($this->placeholder != "") ? "placeholder=\"$this->placeholder\"" : "";
                $this->control .= ($this->value != "") ? " value=\"$this->value\" " : "";
                $this->control .= ($this->maxlength != "") ? " maxlength=\"$this->maxlength\" " : "";
                $this->control .= ($this->size != "") ? "size=\"$this->size\"" : "";                
                $this->control .= ">";
                break;
        }
        
        return $this->control;

    }
    /**
     * Crea un input con formato para telefono
     */
    private function crearInputDate(){
        if(!empty($this->value) and is_integer($this->value)){
            $this->value = FechaHora::convertirUnixADate($this->value);
        }elseif(!empty($this->value) and !is_integer($this->value)){
            $this->value = FechaHora::fechaInvertida($this->value);
        }
        $this->control = "<input type=\"text\" name=\"$this->name\" id=\"$this->id_propiedad\" readonly data-control=\"datepicker\" " . trim ( $this->atributosAdicionales ) . " ";
        $this->control .= ($this->placeholder != "") ? "placeholder=\"$this->placeholder\"" : "";
        $this->control .= ($this->value != "") ? " value=\"$this->value\" " : "";
        $this->control .= ($this->maxlength != "") ? " maxlength=\"$this->maxlength\" " : "";
        $this->control .= ($this->size != "") ? "size=\"$this->size\"" : "";                
        $this->control .= ">";
        return $this->control;
        
    }
    private function crearTelefono(){
            /**
             * Sección de valores update
             * 
             */
          Arrays::mostrarArray($this->eventos);
          $eventos = json_decode("{".$this->eventos."}");
          $tipoTelefono="normal";
          if(property_exists($eventos->{'telefono'}, 'tipo')){
              $tipoTelefono = $eventos->{'telefono'}->{'tipo'};
          }
          $valorCodigo="";$valorTelf="";$valorExtension="";
             if(!empty($this->value)){
                $valorCodigo=($tipoTelefono=='internacional')?substr($this->value,0,4):substr($this->value,0,3);
                $valorTelf=($tipoTelefono=='internacional')?substr($this->value,4,9):substr($this->value,3,7);
                if($this->opciones=='ext'){
                    $valorExtension=($tipoTelefono=='internacional')?substr($this->value,13):substr($this->value,10);
                }
             }
             $this->control="<div class=\"form-inline\">";
             if($tipoTelefono!='internacional'){
                $this->control.="<span class=\"text-muted\">+58</span>";
                $this->control.="<input type=\"text\" id=\"".$this->id_propiedad."-codigo\" value=\"$valorCodigo\" class=\"$this->ccsControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:55px\" name=\"".$this->name."-codigo\" maxlength=\"3\" title=\"Ingrese c&oacute;digo de &aacute;rea\" placeholder=\"cod.\">-";     
             }else{
                 $this->control.="<input type=\"text\" id=\"".$this->id_propiedad."-codigo\" value=\"$valorCodigo\" class=\"$this->ccsControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:60px\" name=\"".$this->name."-codigo\" maxlength=\"4\" title=\"Ingrese c&oacute;digo de &aacute;rea\" placeholder=\"cod.\">-";
             }
            
            
            $this->control .= "<input type=\"text\" name=\"$this->name\" value=\"$valorTelf\" id=\"$this->id_propiedad\" " . trim ( $this->atributosAdicionales ) . " ";
            $this->control .= ($this->placeholder != "") ? "placeholder=\"$this->placeholder\"" : "";
            
            
            $this->control .= ($this->value != "") ? " value=\"$this->value\" " : "";
            $this->control .= ($this->maxlength != "") ? " maxlength=\"$this->maxlength\" " : "";
            $this->control .= ($this->size != "") ? "size=\"$this->size\"" : "";                
            $this->control .= ">";
            if(!empty($this->opciones)){
                
                if($this->opciones=='ext'){
                    $this->control.="-<input type=\"text\" id=\"".$this->id_propiedad."-ext\" value=\"$valorExtension\" class=\"$this->ccsControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:60px\" name=\"".$this->name."-ext\" maxlength=\"4\" title=\"Ingrese c&oacute;digo de extenci&oacute;n\" placeholder=\"ext.\">";
                }
                
                /* No funciona validar */
               /* if($this->opciones=='cel'){
                    $this->control.="-<select id=\"".$this->id_propiedad."-cel\" class=\"$this->ccsControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:60px\" name=\"".$this->name."-cel\">
                    <option value=\" \">...</option>
                    <option value=\"416\">416</option>
                    <option value=\"426\">426</option>
                    <option value=\"414\">414</option>
                    <option value=\"424\">424</option>
                    <option value=\"412\">412</option>
                    </select>";
                    
                }*/
                
            }
            $this->control.="</div>";
            return $this->control;
    }

    private function crearControlIdentificacion(){
            $this->control="<div class=\"form-inline\">";
            $this->control.="<select name=\"".$this->name."-tipo-doc\" class=\"$this->ccsControlRequerida \" id=\"".$this->id_propiedad."-tipo-doc\">";
            $tipoDoc="";
            $this->armarOpciones();
            
            if(!empty($this->value)){
                $tipoDoc = $this->value[0];
                $this->value=substr($this->value, 1);
            }
            foreach ($this->opciones as $key => $value) {
                if($key==$tipoDoc){
                    $this->control.="\n\t\t\t<option value=\"$key\" selected>$value</option>";    
                }else{
                    $this->control.="\n\t\t\t<option value=\"$key\">$value</option>";    
                }
                
            }
            $this->control.="</select>";
            $this->control .= "<input type=\"text\" name=\"$this->name\" id=\"$this->id_propiedad\" " . trim ( $this->atributosAdicionales ) . " ";
            $this->control .= ($this->placeholder != "") ? "placeholder=\"$this->placeholder\"" : "";
            $this->control .= ($this->value != "") ? " value=\"$this->value\" " : "";
            $this->control .= ($this->maxlength != "") ? " maxlength=\"$this->maxlength\" " : "";
            $this->control .= ($this->size != "") ? "size=\"$this->size\"" : "";                
            $this->control .= ">";
            
            $this->control.="</div>";
            return $this->control;
        }
    /**
     * 
     * @return string
     */
    private function crearSelect() {
        $this->control = "<select id=\"$this->id_propiedad\" name=\"$this->name\" " . trim ( $this->atributosAdicionales ) . ">";
        $this->armarOpciones ();
        
        foreach ( $this->opciones as $valor => $dato ) {
            $selected = "";
            if ($this->typeForm == 2) {
                 
                if (array_key_exists($this->name,$this->valueUpdate) and $this->valueUpdate [$this->name] == $valor){
                    $selected = "selected=\"selected\"";
                }
            }
            $this->control .= "\n\t\t\t\t\t\t<option $selected value=\"$valor\">\n\t\t\t\t\t\t\t\t$dato\n\t\t\t\t\t\t</option>";
        }

        $this->control .= "\n\t\t\t\t</select>";
        return $this->control;
    }
    
    /**
     * 
     * @return string
     */
    private function crearTextArea() {
        $this->control = "<textarea name=\"$this->name\" id=\"$this->id_propiedad\" maxlength=\"$this->maxlength\"" . trim ( $this->atributosAdicionales ) . ">";
        $this->control .= $this->value;
        $this->control .= "</textarea>";
        return $this->control;
    }
    
    /**
     * 
     */
    private function crearFile() {}
    
    /**
     * 
     * @return string
     */
    private function crearRadio() {
        $this->control = "";
        $this->armarOpciones ();
        $this->control .= "\n\t\t<div>";
        $i=0;
        
        foreach ( $this->opciones as $valor => $dato ) {
            $check = "";
            if ($this->typeForm == 2 ) {
                if(array_key_exists($this->name, $this->valueUpdate)){
                    $this->valueUpdate[$this->name]=(is_numeric($this->valueUpdate[$this->name]))?(int)$this->valueUpdate[$this->name]:$this->valueUpdate[$this->name];
                    if ($this->valueUpdate[$this->name] == $valor){
                        $check = "checked=\"checked\"";
                    }
                }else{
                   # throw new Exception("No se encuentra definido un valor update del formulario", 1);
                    
                }
            }elseif($i==0){
               #     $check = "checked=\"checked\"";
            }
            
            $this->control .="\n\t\t\t<label class=\"radio-inline\">";
            $this->control .="\n\t\t\t<input type=\"radio\" name=\"$this->name\" id=\"$this->id_propiedad\" value=\"$valor\" $check>$dato";
            $this->control .="\n\t\t\t</label>";
           $i++;
        }
         $this->control .="\n\t\t</div>";
        $this->control .= "";
        return $this->control;
    }
    
    /**
     * 
     * @return string
     */
    private function crearCheck(){

        $this->control="";
        $this->armarOpciones();
        $i="";
        foreach ($this->opciones as $valor => $dato){
            $check="";
            if($this->typeForm==2){
                if(count($this->valueUpdateMultiple)>0){                
                    foreach($this->valueUpdateMultiple as $key =>$valores){
                         if(isset($valores[$this->name]) and $valores[$this->name]==$valor)
                            $check = "checked=\"checked\"";    
                    }
                }
                
            }
            $this->control.="\n\t\t\t<div class=\"checkbox\">\n\t\t\t\t<label><input type=\"checkbox\" $check name=\"$this->name[]\" value=\"$valor\" id=\"".$this->name."$i\">$dato\n\t\t\t\t</label>\n\t\t\t</div>";
            $i = ($i=="")?$i=1:$i++;
        }//final foreach
        $this->control.="";
        return $this->control;
    }//final funcion crearcheck
    
    
    /**
     * funcion que arma las opciones de un "Select".
     */
    private function armarOpciones() {
        try{
            
            
            $opciones = $this->opciones;
            
            $arrOp = array ();
            $i = 0;
            $ar = explode ( ";", $opciones );
            
            foreach( $ar as $value => $opcion ) {
                
                $esSelect = strpos(strtolower($opcion), "select" );
                $esSelectExterno = strpos(trim($opcion), "externo" );
                $esArraySesion = strpos(trim($opcion), "session" );
                
                if ($esSelect === FALSE and $esSelectExterno === FALSE and $esArraySesion === FALSE) {
                    // ntra aqui si las opciones son definidas manualmente
                    $data = explode("=", $opcion );
                
                        $arrOp[$data[0]] = $data[1];
                } elseif ($esArraySesion !== false) {
                    // ntra aqui si las opciones son pasadas por medio de una variable de session
                    $arr = explode ( "=", $opcion );
                    $var = $_SESSION [$arr [1]];
    
                    if (is_array($var)) {
                        foreach( $var as $k => $result ) {
                            $arrOp[$k] = $result;
                        }
                    }
                    
                } else {
                    
                    if ($esSelect !== FALSE) {
                            
                        $data = $this->bd->ejecutarQuery ( $opcion );
                    } elseif ($esSelectExterno !== FALSE) {
                        // entra aqui cuando las opciones son definidas por un query que pasara externamente.
                        
                        $data = $this->bd->ejecutarQuery ( $this->externo[$this->name] );
                    }
                    
                    if ($this->bd->totalRegistros > 0) {
    
                        while ( $result = $this->bd->obtenerArray ( $data ) ) {
                            $arrOp [$result [0]] = $result [1];
                        }
                    }
                }
            }
            
            $this->opciones = $arrOp;
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
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
    public static function crearBoton($value, $valores = "") {
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
    
    
    
    /**
     * Retorna un formulario para registro o modificación del campo de un formulario especifico
     *
     * Instancia la clase Formulario
     *
     * @param int $accion
     *          1, nuevo 2, modificar
     */
    public function formCampo($accion, $idCampo = "", $errores = "") {
        $form = new Formulario ( 'CamposFormulario' );
        $form->action = "#";
        $form->campoUpdate = ($accion == 1) ? "" : $idCampo;
        $form->tipoF = ($accion == 1) ? 1 : 2;
        $form->errores = $errores;
        $form->tipoBoton = "submit";
        $form->valueSubmit = ($accion == 1) ? "Registrar Configuraci&oacute;n" : "Modificar Configuraci&oacute;n";
        return $form->armarFormulario ();
    }
    
    /**
     * Guarda la configurción de un campo de formulario html
     */
    public function procesarCampo() {
        $valor = $this->salvarObjeto ( __CLASS__ );
    }
    
    /**
     * Genera un selector HTML
     *
     * @param string $selector
     *          Nombre Etiqueta HTML a crear
     * @param array $atributos
     *          Arreglo de atributos para el selector
     * @param string $content
     *          Contenido del selector
     */
    public static function crearSelectorHTMLSimple($selector, $atributos = array(), $content = "") {
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
        
        $selectorHTML .= ">$content";
        $selectorHTML .= "</$selector>";
        
        return $selectorHTML;
    }
    
    /**
     * Arma combo <option> html sin etiqueta select
     *
     * Arma lista options a partir de un arreglo, usada
     * principalmente para cargas por medio de ajax
     *
     * @param array $arrayOptions
     *          Array de opciones
     * @return string $combo String HTML de las opciones
     */

    public static function armarComboOpciones($array) {
        $options = "";
        foreach ( $array as $key => $value ) {
            foreach ( $value as $key => $value ) {
                $options .= "<option value=\"$key\">$value</option>";
            }
        }
        return $options;
    }
    
    /**
     * Modifica los atributos privados de la clase
     * @method setAtributosCampoHTML
     * @access public
     * @param  array $array Arreglo relacional con atributos a modificar
     */
    public function setAtributosCampoHTML($array){
        $metodos = get_class_vars ( __CLASS__ );
        foreach ( $metodos as $k => $valor ) {
            if (isset ( $array [$k] )) {
                $this->$k = $array [$k];
            }
        } // final foreach
    }//Fin setAtributosCampoHTML
    
    
    
}//Fin Class