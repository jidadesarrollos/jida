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
    var $ayuda="";
    
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
    private $cssControlRequerida = "form-control";
    private $cssControlForm = "";
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
    
    private $valueUpdate = array ([]);
    private $valueUpdateMultiple=array();
    protected $attrData = array();
    /**
     * Constructor
     * 
     * @param string $arr
     * @param string $arrValue
     * @param string $campoExterno
     * @example new CampoHTML($numeroTabla,$arrayDatos);
     * @example new CampoHTML($arrayDatos,$arrayValues,$campoExterno);
     */
    public function __construct($arr = "", $arrValue = "", $campoExterno = "") {
        
        $totalParametros = func_num_args();
        $this->externo = $campoExterno;
        $this->registroMomentoGuardado=FALSE;
        /**
         * Entra aqui si solo son pasados 2 parametros
         * 1. tipo de tabla : 1) Campos Aplicacion 2) campos Forms framework
         * 2. id del campo
         */
        if($totalParametros==2 and !is_array(func_get_arg(0))){
            $numeroTabla = func_get_arg(0);
            if($numeroTabla==2){
                $this->nombreTabla = "s_jida_campos_f";
                
            }else{
                $this->nombreTabla = "s_campos_f";
            }
            $this->clavePrimaria="id_campo";
            parent::__construct(__CLASS__,func_get_arg(1));
            $this->establecerAtributos($arr);
        }
        /**
         * Entra aqui si se ejecuta la clase sin instanciar objeto desde la bd.
         * 
         */
        else{
            
            $this->nombreTabla = "s_campos_f";
            parent::__construct (__CLASS__);
            if(!empty($this->esquema)){
                $this->nombreTabla= $this->esquema .".". $this->nombreTabla;
            }
            $this->clavePrimaria = "id_campo";
            $this->establecerAtributos ( $arr, __CLASS__ );
            if (is_array($arrValue)) {
            
                $this->typeForm = 2;
                if(is_array($arrValue) and count($arrValue)>0){
                    //Validar si el campo update es multi-selección.
                    
                    $this->valueUpdate=$arrValue;
                    
                    $this->valueUpdateMultiple=$arrValue;
                     
                }else{
                    
                }
            }
            
            $this->cn = $this->bd;
        }
    }
    
    /**
     * 
     */
    private function setValueProperty() {
        $existe=FALSE;
        
        foreach ($this->valueUpdate as $key => $value) {
            if($existe==FALSE){
                 
                if (isset ( $value [$this->name] )){
                    $existe=TRUE;
                    $validacion = json_decode("{".$this->eventos."}");
                    
                    if($validacion!==null and array_key_exists("decimal", $validacion) and !empty($this->valueUpdate[$this->name])){
                        
                        $this->value = number_format($value[$this->name],2,",",".");
                    }else{
                        $this->value = $value[$this->name];
         
                    }
                }
            }//fin if existe;    
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
            10=>"Fecha",
            11=>'Captcha'
            
             
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
        if(!empty($this->cssControlForm))
            $this->cssControlRequerida = $this->cssControlRequerida ." ".$this->cssControlForm;
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
        
        $class = (!empty($this->class)) ? "class=\"$this->cssControlRequerida $this->class\"" : "class=\"$this->cssControlRequerida\"";
        $this->attrTitle = (!empty($this->title)) ? " title=\"$this->title\" " : "";
        $data = ($this->data_atributo != "") ? " $this->data_atributo" : "";
        if(is_array($this->attrData) and count($this->attrData)>0){
            foreach ($this->attrData as $key => $value) {
                $data.=" $key='".$value."'";
            }
        }
        if($this->control!=6 and $this->control!=5)
            $this->atributosAdicionales = " " . $this->attrTitle . " " . $class . " " . $data . " " . $this->attrVisibilidad . " ";
        else
            $this->atributosAdicionales = " " . $this->attrTitle . " " .  $data . " " . $this->attrVisibilidad . " ";
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
			case 11:
				$this->control = $this->crearCaptcha();
                break;
            default :
                $this->control = "<input type=\"$this->type\" name=\"$this->name\" id=\"$this->id_propiedad\" " . trim ( $this->atributosAdicionales ) . " ";
                $this->control .= ($this->placeholder != "") ? "placeholder=\"$this->placeholder\"" : "";
                
                
                if(is_array($this->value)){
                    $this->control .= ($this->value != "") ? " value=\"".$this->value[0]."\" " : "";    
                }else{
                    $this->control .= ($this->value != "") ? " value=\"$this->value\" " : "";
                }
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
	/**
	 * Crea un campo CAPTCHA
	 * Hace uso de la libreria de google Recaptcha
	 * @method crearCaptcha
	 * 
	 */
 	private function crearCaptcha(){
 		if(!defined('RECAPTCHA_PUBLIC_KEY')){
 			throw new Exception("No se encuentra definida la clave publica para la libreria recaptcha, por favor valide
 			 constante RECAPTCHA_PUBLIC_KEY en archivos initConfig o appConfig", 1);
			 
 		}
 		// require_once 'Componentes/recaptcha/recaptchalib.php';
		// $publicKey=RECAPTCHA_PUBLIC_KEY;
		// return recaptcha_get_html($publicKey);
		return "<div id=\"recaptcha_div\"></div>
		<input type=\"button\" value=\"Show reCAPTCHA\" onclick=\"showRecaptcha('recaptcha_div');\"></input>";
		
 	}
    private function crearTelefono(){
            /**
             * Sección de valores update
             * 
             */
          $eventos = json_decode("{".$this->eventos."}");
          $tipoTelefono="normal";
		  $digitosCodigo=3;
          if(is_object($eventos) and property_exists($eventos, 'telefono')){
              
              if(property_exists($eventos->{'telefono'}, 'tipo')){
                  $tipoTelefono = $eventos->{'telefono'}->{'tipo'};
				  
              }
			  
			  if(property_exists($eventos->{'telefono'}, 'digitos_codigo')){
			  	$digitosCodigo=$eventos->{'telefono'}->{'digitos_codigo'};
			  }
			
          }
          $valorCodigo="";$valorTelf="";$valorExtension="";
             if(!empty($this->value)){
                $valorCodigo=($tipoTelefono=='internacional')?substr($this->value,0,4):substr($this->value,0,3);
                $valorTelf=($tipoTelefono=='internacional')?substr($this->value,4,9):substr($this->value,3,7);
                if($this->opciones=='ext'){
                    $valorExtension=($tipoTelefono=='internacional')?substr($this->value,13):substr($this->value,10);
                }
             }
             $this->control="<div class=\"control-multiple\" id=\"box".$this->id_propiedad."\">";
             if($tipoTelefono!='internacional'){
                #$this->control.="<div class=\"text-muted ctrl-number\">(+58) </div>";
                $this->control.="<input data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" type=\"text\" id=\"".$this->id_propiedad."-codigo\" value=\"$valorCodigo\" class=\"ctrl-number-code $this->cssControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:55px\" name=\"".$this->name."-codigo\" maxlength=\"$digitosCodigo\" title=\"\" placeholder=\"\">";     
             }else{
                 $this->control.="<input type=\"text\" id=\"".$this->id_propiedad."-codigo\" value=\"$valorCodigo\" class=\"ctrl-number-code $this->cssControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:60px\" name=\"".$this->name."-codigo\" maxlength=\"$digitosCodigo\" title=\"Ingrese c&oacute;digo de &aacute;rea\" placeholder=\"cod.\" data-jidacontrol=\"numerico\">";
             }
			            
            
            $this->control .= "<input type=\"text\" name=\"$this->name\" value=\"$valorTelf\" id=\"$this->id_propiedad\" " . trim ( $this->atributosAdicionales ) . " data-jidacontrol=\"numerico\"";
            $this->control .= ($this->placeholder != "") ? "placeholder=\"$this->placeholder\"" : "";
            
            
            $this->control .= ($this->value != "") ? " value=\"$this->value\" " : "";
            $this->control .= ($this->maxlength != "") ? " maxlength=\"$this->maxlength\" " : "";
            $this->control .= ($this->size != "") ? "size=\"$this->size\"" : "";                
            $this->control .= ">";
            if(!empty($this->opciones)){
                
                if($this->opciones=='ext'){
                    $this->control.="-<input type=\"text\" id=\"".$this->id_propiedad."-ext\" value=\"$valorExtension\" class=\"ctrl-number-ext $this->cssControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:60px\" name=\"".$this->name."-ext\" maxlength=\"4\" title=\"Ingrese c&oacute;digo de extenci&oacute;n\" placeholder=\"ext.\">";
                }
                
                /* No funciona validar */
               /* if($this->opciones=='cel'){
                    $this->control.="-<select id=\"".$this->id_propiedad."-cel\" class=\"$this->cssControlRequerida\" data-jidacontrol=\"numerico\" style=\"width:60px\" name=\"".$this->name."-cel\">
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
            $this->control="<div class=\"control-multiple\">";
            $this->control.="<select name=\"".$this->name."-tipo-doc\" class=\"$this->cssControlRequerida ctrl-documentacion \" id=\"".$this->id_propiedad."-tipo-doc\">";
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
            $this->control .= "<input type=\"text\" data-jidacontrol=\"numerico\" name=\"$this->name\" id=\"$this->id_propiedad\" " . trim ( $this->atributosAdicionales ) . " ";
            $this->control .= ($this->placeholder != "") ? "placeholder=\"$this->placeholder\"" : "";
            $this->control .= ($this->value != "") ? " value=\"$this->value\" " : "";
            $this->control .= ($this->maxlength != "") ? " maxlength=\"$this->maxlength\" " : " maxlength=\"9\" ";
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
                
                if (is_array($this->valueUpdate) and array_key_exists(0,$this->valueUpdate) and is_array($this->valueUpdate[0]) and array_key_exists($this->name,$this->valueUpdate[0]) and $this->valueUpdate[0] [$this->name] == $valor){
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
        $this->control = "<textarea name=\"$this->name\" id=\"$this->id_propiedad\" maxlength=\"$this->maxlength\" " . trim ( $this->atributosAdicionales ) . ">";
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
        
        $i=0;
        $a=0;
        foreach ( $this->opciones as $valor => $dato ) {
            $this->control .= "\n\t\t<div class=\"radio radio-inline\">";
            $check = "";
            if ($this->typeForm == 2 ) {
                
                if(is_array($this->valueUpdate) and array_key_exists(0, $this->valueUpdate) and is_array($this->valueUpdate[0]) and array_key_exists($this->name, $this->valueUpdate[0])){
                        
                    #$this->valueUpdate[$this->name]==(is_numeric($this->valueUpdate[$this->name]))?(int)$this->valueUpdate[$this->name]:$this->valueUpdate[$this->name];
                    if ($this->valueUpdate[0][$this->name] == $valor){
                        $check = "checked=\"checked\" ";
                    }
                    
                }else{
                 #  throw new Exception("No se encuentra definido un valor update del formulario", 1);
                    
                }
            }
            $data = "";
            
            if(is_array($this->attrData) and count($this->data_atributo)>0){
                 $i=0;
                foreach ($this->attrData as $key => $value) {
                    if($i<0) $data.=" ";
                    $data.="$key='$value'";
                    ++$i;
                }
            } if($i==0) $check.=" $this->atributosAdicionales ";
            if($a<1){
                $this->control .="\n\t\t\t<input type=\"radio\" name=\"$this->name\" id=\"$this->id_propiedad\" $data value=\"$valor\" ".trim($check).">";
                $this->control .="\n\t\t\t<label for=\"$this->id_propiedad\">$dato";
            }else{
                $this->control .="\n\t\t\t<input type=\"radio\" name=\"$this->name\" id=\"$this->id_propiedad-$a\" $data value=\"$valor\" ".trim($check).">";
                $this->control .="\n\t\t\t<label for=\"$this->id_propiedad-$a\">$dato";
            }
            
            $this->control .="\n\t\t\t</label>";
            $this->control .="\n\t\t</div>";
           $i++;++$a;
        }
         
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
        $i=0;
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
            if($i==0){
                $this->control.="\n\t\t\t<div class=\"checkbox\">\n\t\t\t\t
                <input type=\"checkbox\" $check name=\"$this->name[]\" value=\"$valor\" id=\"".$this->name."\"  $this->atributosAdicionales  >
                <label for=\"".$this->name."\" >$dato\n\t\t\t\t</label>\n\t\t\t</div>";
            }else{
                $this->control.="\n\t\t\t<div class=\"checkbox\">\n\t\t\t\t
                <input type=\"checkbox\" $check name=\"$this->name[]\" value=\"$valor\" id=\"".$this->name."-$i\" ><label for=\"".$this->name."-$i\">$dato\n\t\t\t\t</label>\n\t\t\t</div>";
            }
            
            ++$i;
        }//final foreach
        $this->control.="";
        return $this->control;
    }//final funcion crearcheck
    
    
    /**
     * funcion que arma las opciones de un "Select".
     */
    private function armarOpciones() {
            
        $opciones = $this->opciones;
        /**
         * Arreglo que registra las opciones a mostrar en el campo de selecíon a crear
         * @var $arrop
         */
        $arrOp = array ();
        $i = 0;
        $ar = explode ( ";", $opciones );
        /**
         * Primera separación por ; pues se pueden manejar multiples formatos para llenar el array
         */
        foreach( $ar as $value => $opcion ) {
            
            $esSelect = strpos(strtolower($opcion), "select" );
            $esSelectExterno = strpos(trim($opcion), "externo" );
			if(is_array($this->externo) and array_key_exists($this->name, $this->externo)){
				$esSelectExterno=TRUE;
			}
            $esArraySesion = strpos(trim($opcion), "session" );
            $esJson= strpos(trim($opcion), "json" );
            if ($esSelect === FALSE and $esSelectExterno === FALSE and $esArraySesion === FALSE) {
                // entra aqui si las opciones son definidas manualmente
                $data = explode("=", $opcion );
                if(count($data)>1)
                 $arrOp[$data[0]] = $data[1];
            } elseif ($esArraySesion !== false) {
                // entra aqui si las opciones son pasadas por medio de una variable de session
                $arr = explode ( "=", $opcion );
                $var = $_SESSION [$arr [1]];

                if (is_array($var)) {
                    foreach( $var as $k => $result ) {
                        $arrOp[$k] = $result;
                    }
                }
                
            }elseif($esJson===TRUE){
                throw new Exception("No se encuentra disponible el uso de archivos json", 1);
                
            }else {
                if ($esSelectExterno !== FALSE) {
                    // entra aqui cuando las opciones son definidas por un query o arreglo externo
                    /**
                     * En caso de que sea un arreglo
                     */
                     
                    if(is_array($this->externo[$this->name])){
                        $arrOp =$this->externo[$this->name];
                    }else{
                        /**
                         * Caso de que sea un query
                         */
                        $data = $this->bd->ejecutarQuery ( $this->externo[$this->name] );
                        if ($this->bd->totalRegistros > 0):
                            while ( $result = $this->bd->obtenerArray ( $data ) ):
                                $arrOp [$result [0]] = $result [1];
                            endwhile;
                        endif;
                    }
                    
                }else
                if ($esSelect !== FALSE) {
                        
                    
                    $data = $this->bd->ejecutarQuery ( $opcion );
                    if ($this->bd->totalRegistros > 0):
                        while ( $result = $this->bd->obtenerArray ( $data ) ):
                            $arrOp [$result [0]] = $result [1];
                        endwhile;
                    endif;
                }//fin if;
            }
        }
        
        $this->opciones = $arrOp;
    
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
     * @deprecated @see Selector::crearInput
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
     * Guarda la configurción de un campo de formulario html
     */
    public function procesarCampo($data="") {
        
        $valor = $this->salvar($data);
        
        return $valor;
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