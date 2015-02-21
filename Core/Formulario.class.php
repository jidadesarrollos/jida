<?php

/**
 * Clase manejadora de Formularios dinamicos en HTML
 *
 * Basada en formularios registrados en base de datos para hacer formularios dinamicos,
 * utiliza la clase campo.
 *
 * @package framework
 * @category jida
 *           @create: 21/05/2012
 *           @update :15/10/2013
 *           @update :04/04/2014
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 1.8
 * 
 * Dependencias : 
 * BD/DBContainer
 * ModelFramework/JidaControl.class.php
 * ModelFramework/JidaFormularios.class.php
 * Core/CampoHTML.class.php
 * Helpers/Arrays
 * 
 * 
 */

class Formulario extends DBContainer {
    /**
     * Define un esquema de ubicacion de base de datos para la tabla
     * @access private
     * @var string $private
     */
    private $esquema="";
    /**
     * Determina si los valores del formulario deben ser validados o cambiados a entidades HTML
     * @var $setHtmlEntities
     */
    public $setHtmlEntities=TRUE;
    /**
     * Id del formulario
     */
    public $id_form;
    
    /**
     * Nombre del formulario
     */
    public $nombre_f;
    
    /**
     * Query que crea el formulario.
     */
    public $query_f;
    
    /**
     * Define la clave primaria del formulario creado.
     *
     * Es utilizada para traer formularios en modo update
     */
    public $clave_primaria_f;
    /**
     * Define la estructura de un formulario a renderizar por medio del metodo armarFormularioEstructura
     * La estructura se define colocando el numero de campos que se desean por culumnas, teniendo en cuenta
     * que se utiliza el sistema grid de bootstrap y q el max. de columnas es 12.
     * Si se desea emplear el mismo grid de columnas en varias filas se puede usar el simbolo "x" de modo que
     * "3x5" repetirá 5 filas de 3 columnas
     * @var string $estructura
     * @example 1;3;2x4;3;1  
     * @access public
     * @see @armarFormularioEstructura 
     */
    public $estructura;
    /**
     * Permite personalizar la division de las filas declaradas en la estructura del formulario
     * @var array $estructuraFilas
     * @access public
     */
    public $estructuraFilas =array();
    
    /**
     * Indica forma del formulario a crear.
     * 1. Insert
     * 2. Update
     */
    public $tipoF = 1;
    
    /**
     * Define si los mensajes de session son mostrados
     * 1 = SI 2 = NO
     * Para mostrar mensajes se debe usar la variable de sesion __msjForm
     * 
     * @example $_SESSION['__msjForm']="Mensaje a mostrar";
     */
    public $mostrarMensajes = TRUE;
    /**
     * Valor o id del objeto a modificar.
     */
    public $campoUpdate = "";
    /**
     * Contiene los valores de los campos de un formulario
     * en modo update, esta variable solo es llenada despues
     * de que el formulario sea llamado para su creacion
     * 
     * @var array $valoreSUpdate
     */
    public $valoresUpdate = "";
    /**
     * nombre de validación en javascript para ejecutar antes del
     * validadorJida en JS
     * @var $funcionPreviaValidadorJida
     * @access public
     */
    public $funcionPreviaValidadorJida="";
    /**
     * nombre de validación en javascript para ejecutar despues del
     * validadorJida en JS
     * @var $funcionPreviaValidadorJida
     * @access public
     */
    public $funcionAfterValidadorJida="";
    // Propiedades del formulario
    private $nameTagForm;
    
    public $targetForm="";
    private $idTagForm;
    private $metodo = "POST";
    /**
     * Define una consulta externa utilizar para un campo de seleccion determinado, 
     * el query debe ser registrado en una posición de arreglo donde el key tenga 
     * el nombre del campo; El
     * valor es pasado a la clase CampoHTML.
     * 
     * @var array $externo
     * @example $this->externo=array("nombre_campo"=>"select id,nombre from tabla");
     * @access public
     */
    var $externo=array();
    var $nombreSubmit;
    var $enctype = "application/x-www-form-urlencoded";
    var $action = "";

    
    /* VARIABLES DE LA CLASE */
    
    /**
     * Arreglo que contiene todos los campos del formulario definido.
     *
     * @var array $camposFormulario
     */
    var $camposFormulario;
    
    /**
     * obtenerValorUpdateMultiple
     * Funcion constructora
     * Inicializa el formulario, el tipo de formulario y los valores para los controles
     * por defecto
     */
    /**
     * Arreglo que guarda los errores de proceso de un formulario
     *
     * @var array $errores
     */
    var $errores = array ();
    /**
     * Define el estilo CSS que deben llevar los errores
     * 
     * @var string $estiloErrorCampo
     */
    var $cssDivErrorCampo = "div-error";
    var $cssControlForm = "";
    private $tablaCampos = "s_campos_f";
    
    private $funcionJidaJs = 'jValidador';
    /**
     * Hace referencia al valor de busqueda del formulario, puede ser un
     * valor entero, en cuyo caso el formulario será buscado por el ID del mismo
     * o un string identificador.
     * 
     * @var string $claveCampo;
     */
    private $claveFormulario;
    /**
     * Define el campo de busqueda para el formulario
     */
    private $campoBusquedaFormulario;
    /**
     * Define el nombre de la clase agregada al selector <form>
     * 
     * @var cssTagForm
     * @access public
     */
    var $cssTagForm = "";
    
    /**
     * Captura la data enviada por POST en un formulario cargado
     * 
     * @var array $dataPost
     * @access private
     */
    private $dataPost = "";
    
    /**
     * Consulta ejecutada a base de datos para traer los datos en modo update
     * 
     * @var $queryDatosUpdate;
     */
    
    var $queryDatosUpdate="";
    var $cssFieldsetForm="form-fieldset";
    
    
    var $selectorTitulo="h3";
    /**
     * Muestra el titulo del formulario
     */
    var $tituloFormulario="";
    
    
    /**
     * Indica si el boton de guardado del formulario es mostrado, por defecto se encuentra en TRUE,
     * en caso de que $botonGuardado sea colocado en FALSE, el formulario NO imprimirá la etiqueta form
     * y debe ser colocada en el archivo vista
     * @var boolean  $botonGuardado  
     */
    var $botonGuardado = TRUE;
    
    
    /**
     * Permite definir multiples botones a mostrar en el formulario, se encuentra en FALSe por defecto
     * 
     * @var array $botonesMultiples
     * 
     */
     var $botonesMultiples=FALSE;
     /**
     * Define si el boton del formulario tiene una funcion onclick asociada
     */
    var $funcionOnclick;
    /**
     * Estilo del Botón del formulario
     * @var string $classBotonForm
     */
    var $classBotonForm = "pull-right btn btn-primary";
    /**
     * String a Imprimir en el boton del Formulario
     * @var string $valueBotonForm
     */
    var $valueBotonForm;
    /**
     * Tipo del Input creado
     *
     * Por defecto es submit, puede ser especificado como "button"
     *  @var $tipoBoton
     */
    var $tipoBoton = "submit";
    /**
     * Define el id a colocar al submit de envio
     * @var $idBotonForm
     */
     var $idBotonForm ;
     
     /**
      * segmento de script javascript que realiza llamado al validadorJida.js para 
      * hacer las validaciones configuradas del lado cliente
      * @var string $validadorJidaJs
      */
    var $validadorJidaJs;
    
    /**
     * Define si la validacionJs será incluida en el formulario, por defecto es true
     * @var boolean $validacionForm
     */
     var $validacionForm=TRUE;
     
     /**
      * Determina si se deben tildar los campos obligatorios
      * @var boolean $mostrarCamposObligatorios 
      */
     private $mostrarCamposObligatorios=TRUE;
     /**
      * Selector usado para tildar los campos obligatorios
      * @var string $selectorCampoObligatorio
      */
     private $selectorCampoObligatorio="span";
     /**
      * Clase css para el $selectorCampoObligatorio 
      * @var string $cssCampoObligatorio
      */
     private $cssCampoObligatorio="control-obligatorio";
     /**
      * Contenido HTML del selector $selectorCampoObligatorio
      * @var $htmlSelectorCampoObligatorio;
      */
     private $htmlSelectorCampoObligatorio="*";
     /** 
      * @var $totalForms Numero de Formularios Instanciados
      */
     private $totalForms;
     /**
      * @var array $formularios Arreglo contenedor de los objetos tipo Formulario
      * 
      */
     private $formularios = array();
     /**
      * @var int ambito Ambito de la tabla 1 Aplicacion 2 JidaFramework
      * 
      */
     private $ambito;
     
    /**
     * Funcion constructora del formulario
     *
     * Inicializa los valores requeridos para generar un formulario, captura
     * toda la información de los campos del formulario, los datos registrados en caso
     * de ser un formulario para modificación y prepara los estados necesarios para la creación.
     *
     *
     * @param mixed $id_form
     *          Id o arreglo de ids del formulario registrado en BD ES OBLIGATORIO 
     * @param int 
     *          Indica si el formulario viene en 1)Insert o 2)Update.
     * @param int $campoUpdate
     *          Clave primaria del registro a modificar,este parametro solo es pasado si el formulario debe mostrarse en modo update.
     * @param string $ambito
     *          Indica si el formulario a crear o editar pertenece al framework o a la aplicación
     *
     */
    public function __construct($claveFormulario, $tipoForm = 1, $campoUpdate = "", $ambito=1) {
        if(is_string($claveFormulario)){
            $claveFormulario=array($claveFormulario);
        }
        
        $this->tipoF = $tipoForm;
        $this->action=$_SERVER['PHP_SELF'];
        $this->campoUpdate = $campoUpdate;
        
        if($ambito==2){
            
            $this->nombreTabla = "s_jida_formularios";
            $this->tablaCampos = "s_jida_campos_f";
        }else{
            $this->nombreTabla = "s_formularios";
            $this->tablaCampos = "s_campos_f";   
             
        }
        $this->dataPost =& $_POST;
        
            
        /**
         * Se valida el nombre de las tablas en base de datos.
         */
        if(!empty($this->esquema)){
            $this->nombreTabla=$this->esquema.".".$this->nombreTabla;
            $this->tablaCampos=$this->esquema.".".$this->tablaCampos;
        }
        
        $this->clavePrimaria = 'id_form';
        parent::__construct (__CLASS__);
        $this->registroMomentoGuardado=FALSE;
        $this->claveFormulario = $claveFormulario;
        if (is_int ( $this->claveFormulario )) {
            
            $this->campoBusquedaFormulario = "id_form";
        }else{
            
            $this->campoBusquedaFormulario = "nombre_identificador";
            
            
        }
        $this->metodo = 'POST';
        $this->obtenerDatosFormulario ();
        $this->obtenerCamposFormulario ();
        
    }
    private function obtenerDatosMultiplesForm($lista){
        $query.="";
        
        for($i=0;$i<count($lista);$i++){
            if($i>0)
                $query.=" union ";
            $query .= "select * from $this->nombreTabla where $this->campoBusquedaFormulario=$lista[$i]";
            
        }//fin for
    }
    /**
     * Obtiene los datos del formulario instanciado 
     * Busca en base de datos la clave del formulario y el query de consulta
     * @method obtenerDatosFormulario
     * 
     */
    protected function obtenerDatosFormulario() {
        
        if(is_array($this->claveFormulario)){
            $campos = "";
                
            for($i=0;$i<count($this->claveFormulario);++$i){
                if($i>0)
                    $campos.=",";
                $campos .="'".$this->claveFormulario[$i]."'";
            }
        }else{
            $campos = "'$this->claveFormulario'";
            
        }
        $query = "select * from $this->nombreTabla where $this->campoBusquedaFormulario in ($campos)";
        $formularios = $this->bd->obtenerDataCompleta( $this->bd->ejecutarQuery ( $query ) );
        $this->totalForms = $this->bd->totalRegistros;
        if($this->totalForms<1){
            throw new Exception("No se han obtenido formularios de la consulta <br/> $query", 1);
            
        }
        foreach ($formularios as $key => $formulario) {
            
            $form  = new JidaFormulario($this->ambito);
            $form->inicializarForm($formulario);
            $this->formularios[$form->nombre_identificador]=$form;
            
        }
        $this->inicializarValoresForm ();
    }
    
    
    /**
     * Obtiene los campos que conforman el formulario a crear
     *
     * Consulta la tabla de campos en base de datos y arma un arreglo con la configuración
     * de cada campo
     * @method obtenerCamposFormulario
     */
    protected function obtenerCamposFormulario() {
        if($this->totalForms==1){
            $clave = (is_array($this->claveFormulario))?$this->claveFormulario[0]:$this->claveFormulario;
            $clave = String::upperCamelCase($clave);
            $query = "select * from $this->tablaCampos where id_form=".$this->formularios[''.$clave.'']->id_form." order by orden asc";    
        }elseIf($this->totalForms>1){
            
            $claves = Arrays::obtenerKey('id_form', array_reverse($this->formularios));
            
            $query = sprintf("select * from %s where id_form in (%s) order by orden asc ",$this->tablaCampos,implode(',', $claves)); 
        }else{
            throw new Exception("No se ha obtenido el formulario solicitado", 1);
            
        }
        $result = $this->bd->ejecutarQuery($query);
        $campos = array();
         while ($value = $this->bd->obtenerArrayAsociativo($result)) {
             
                if($this->totalForms>1){
                    $campos[$value['id_form']][$value['name']] = $value;
                }else{
                    $campos[$value["name"]] = $value;
                }
         }
        
        
        if($this->totalForms>1)
            $campos = $this->ordenarCamposMultipleArray($campos);
      
        $this->camposFormulario = $campos;
    }

    private function ordenarCamposMultipleArray($campos){
        $camposOrdered=array();
        foreach ($this->claveFormulario as $key => $form) {
          
            $arrayForm = $campos[$this->formularios[$form]->id_form];
            foreach ($arrayForm as $camposForm) {
                $camposOrdered[]=$camposForm;
            }
            
        }
        return $camposOrdered;
        
    }
    
    /**
     * Define los valores y atributos para un formulario en HTML
     *
     * Crea los atributos nombre,id y tabla para el formulario.
     */
    protected function inicializarValoresForm() {
        #Debug::mostrarArray($this->formularios,false);
        if(array_key_exists(String::upperCamelCase($this->claveFormulario[0]), $this->formularios)){
            $data  = $this->formularios[String::upperCamelCase($this->claveFormulario[0])];    
        }else{
            
            throw new Exception("No existe la clave del formulario ".$this->claveFormulario[0], 1);
            
        }
        
        
        $nombreFormSinEspacios = str_replace ( " ", "", ucwords ( $data->nombre_f ) );
        $this->nameTagForm = "form" . $nombreFormSinEspacios;
        $this->idTagForm = $this->nameTagForm;
        $this->nombreSubmit = "btn" . $nombreFormSinEspacios;
        $this->idBotonForm = $this->nombreSubmit;
        
        $this->valueBotonForm = ($this->tipoF == 1) ? 'Guardar' : 'Modificar';
    }
    
    /**
     * 
     * @param string $id_form
     * @return string
     */
    public function armarFormulario($id_form = "") {
        $id_form = ($id_form == "") ? $this->id_form : $id_form;
        $camposForm = $this->armarFormularioArray ( $this->campoUpdate );
        if (isset ( $camposForm ['validacion'] )) {
            $validacion = $camposForm ['validacion'];
        }
        #$formulario = $validacion . "<form name=\"$this->nameTagForm\" method=\"$this->metodo\"  enctype=\"$this->enctype\" action=\"$this->action\" id=\"$this->idTagForm\" class=\"$this->cssTagForm\" role=\"form\">
        $formulario=$validacion;
        #";
        if(!empty($this->tituloFormulario)){
            $formulario.="\n\t<div class=\"row\">\n\t\t<div class=\"col-lg-12\">\n\t\t\t";
            $formulario.="<$this->selectorTitulo>$this->tituloFormulario</$this->selectorTitulo>";
            $formulario.="\n\t\t</div>\n\t</div>";    
        }
        
        if ($this->mostrarMensajes === TRUE and ! empty ( $_SESSION ['__msjForm'] )) {
            $formulario .= $_SESSION ['__msjForm'];
            Session::destroy('__msjForm');
        }
        
        foreach ( $camposForm as $campo ) {
            
            if (isset ( $campo ['label'] ) and ! empty ( $campo ['label'] )) {
                
                $formulario .= "<div class=\"row\">
                                <div class=\"col-lg-3\">
                                    " . $campo ['label'] . "
                                </div>
                                <div class=\"col-lg-9 form-group\">
                                    " . $campo ['control'] . "
                                </div>  
                               </div>
                                ";
            } else {
                if (isset ( $campo ['control'] )) {
                    $formulario .= "
                                <div class=\"form-group\">
                                    " . $campo ['control'] . "  
                                </div>";
                }
            }
        }
         if(isset($form['validacion'])){
                    $validacion=$form['validacion'];
                    unset($form['validacion']);
                }
        
        if($this->validacionForm===TRUE){
            $formulario .= $validacion;    
        }
        $onclick = ($this->funcionOnclick == "") ? "" : "onclick=\"$this->funcionOnclick\"";
        $formulario.=$this->crearBotonesFormulario();
        
        $attrForm=array('name'=>$this->nameTagForm,'method'=>$this->metodo,'enctype'=>$this->enctype,'action'=>$this->action,'id'=>$this->idTagForm,'class'=>$this->cssTagForm,'role'=>'form','target'=>$this->targetForm);
        $form = Selector::crear('form',$attrForm,$formulario);
        
        return $form;
    }
    
    /**
     * Funcion que arma formulario en una tabla
     *
     * útiliza la funcion armarFormularioAsArray para obtener los controles del formulario,
     * y devuelve el mismo contenido en una tabla.
     * 
     * @param $id_form int
     *          id del formulario a crear.
     */
    public function armarFormularioTable($id_form = "") {
        $id_form = ($id_form == "") ? $this->id_form : $id_form;

        $camposForm = $this->armarFormularioArray($this->campoUpdate);
        
        if (isset($camposForm['validacion'])) {
            
            $validacion = $camposForm ['validacion'];
        }
        
        $formulario = $validacion . "<form name=\"$this->nameTagForm\" method=\"$this->metodo\" 
                                    enctype=\"$this->enctype\" action=\"$this->action\" id=\"$this->idTagForm\" 
                                    class=\"$this->cssTagForm\" role=\"form\"><table>";
        
        foreach ( $camposForm as $campo ) {
            
            if (isset ( $campo ['label'] )) {
                $formulario .= "
                        <tr>
                            <td>" . $campo ['label'] . "</td>
                            <td>" . $campo ['control'] . "</td>
                        </tr>";
            }
        }
        
        $onclick = ($this->funcionOnclick == "") ? "" : "onclick=\"$this->funcionOnclick\"";
        $formulario .= "
                <tr>
                    <td colspan=\"2\" class=\"botonForm\">\n\t
                        <input $onclick type=\"$this->tipoBoton\" class=\"$this->classBotonForm\" name=\"$this->nombreSubmit\" value=\"$this->valueBotonForm\" id=\"$this->idBotonForm\">
                    </td>
                </tr>
            </table>
        </form>";
        return $formulario;
    } // final funcion
      
    /**
     * 
     * @param unknown $campo
     * @param unknown $eventoCampo
     * @param unknown $vuelta
     * @return string
     */
    private function llamadaJson($campo, $eventoCampo, $vuelta) {
        if ($vuelta == 0) $js = "\n\t\t'$campo':";
        else $js = ",\n\t\t'$campo':";
        $js .= "\n\t\t\t\t{" . $eventoCampo . "}";
        return $js;
    }
    
    /**
     * Armar arreglo Json para ValidadorJida
     *
     * Valida los valores ingresados en la propiedad "eventos" y arma un objeto javascript "validadorJida"
     * que es instanciado por medio de un document-ready de JQuery y obtiene como parametros los jsons de cada campo
     * del formulario.
     * El array armado es utilizado para usar el plug-in validadorJida en el front del formulario.
     *
     * @param string $json
     *          json guardado en la base de datos en el campo eventos
     * @return string $js cadena que será interpretada como codigo JS para instanciar el validadorJida
     */
    private function armarfuncionJs($json) {
        
        $nameBotonJs = $this->idBotonForm;
        $js = "\n\r<SCRIPT>\n\t
        $(document).ready(function(){\n\t";
        $validador = "var validador = new jd.validador(\n\t\t" . "\"" . $nameBotonJs . "\",{";
        $validador .= $json . "\n\t\t\t\t\t}";
        if(!empty($this->funcionPreviaValidadorJida)){
            $validador.=",".$this->funcionPreviaValidadorJida."\n";
        }else{
            $validador.=",null\n";
        }
        if(!empty($this->funcionAfterValidadorJida)){
            $validador.=",".$this->funcionAfterValidadorJida."\n";
        }
        
        $validador.=");";
        
        $this->validadorJidaJs = $validador;
        $js.=$validador."\r\t}";
        $js.=")\n</SCRIPT>\n";
        
        return $js;
    }
    
    /**
     * Genera un formulario registrando los campos en un arreglo
     *
     * El formulario es generado a partir de un query a base de datos, tambien genera
     * las validaciones a nivel de javascript para trabajar con el plugin "validadorJida"
     *
     * @param int $campoUpdate [opcional] Id Numerico de la clave primaria de base de datos para generar el formulario
     * en modo update
     *          
     
     */
    public function armarFormularioArray($campoUpdate = "") {
        
        if ($campoUpdate != "")    $this->campoUpdate = $campoUpdate;
        
        $formulario = array ();
        if(count($this->dataPost)>0){
            $dataUpdate[0] = $this->dataPost;    
        }else{
            $dataUpdate="";    
        }
        $vuelta = 0;
        $javascript = "";
        
        if ($this->tipoF == 2) $dataUpdate = $this->obtenerValuesForUpdate($this->id_form );
        
        foreach ( $this->camposFormulario as $posicion => $arr ) {
            $controlHTML = new CampoHTML ( $arr, $dataUpdate, $this->externo );
            if ($arr ['eventos'] != "") {
                
                if($this->funcionJidaJs=='jValidador'){
                    $data = "{".$arr['eventos']."}";
                    $controlHTML->setAtributosCampoHTML(['attrData'=>['data-validacion'=>$data]]);
                }else{
                    $javascript .= $this->llamadaJson ( $arr ['id_propiedad'], $arr ['eventos'], $vuelta );
                    $vuelta ++;    
                }
                
            } 
            
            
            if(!empty($this->cssControlForm))
                $controlHTML->setAtributosCampoHTML(['cssControlForm'=>$this->cssControlForm]);
            // i es agregado un query a la clase formulario es pasado a la clase campo en el momento de creacion del control.
            
            $control = $controlHTML->crearControl();
            $validaciones = json_decode("{".$arr['eventos']."}",true);
            /* Agregar error si existe */
            if (isset ( $this->errores [$arr ['name']] )) {
                $control.=Selector::crear('div',array('class'=>$this->cssDivErrorCampo),$this->errores [$arr ['name']]);
            }
            
            $formulario [$arr ['name']] = array (
                    "control" => $control 
            );
            /*Se intenta colocar el label si el tipo de control!=hidden*/
            if ($arr ['control'] != 1) {
                
                $label = Selector::crear('label',array('for'=>$arr['name']),$arr['label']);
                if($this->mostrarCamposObligatorios===TRUE){
                    if(is_array($validaciones) and array_key_exists('obligatorio', $validaciones)):
                        $tildeObligatorio = Selector::crear($this->selectorCampoObligatorio,array('class'=>$this->cssCampoObligatorio),$this->htmlSelectorCampoObligatorio);
                        $label=$label.$tildeObligatorio;                                
                    endif;
                }
                $formulario [$arr ['name']] ['label'] =$label;
            }
        }
        
        // ------------------------------------------
        // Se valida que existan validaciones para armar la función js que llama al validador
        $js = (! empty ( $javascript )) ? $this->armarfuncionJs ( $javascript ) : "";
        $formulario ['validacion'] = $js;
        // ------------------------------------------
        return $formulario;
    }
    /**
     * Permite borrar la data del formulario
     * @method borrarDataForm
     */
    function borrarDataForm(){
        $this->dataPost="";
        
    }
    /**
     * Funcion que obtiene la data modo update de un formulario dado.
     *
     * Busca los datos registrados del objeto a modificar
     *
     * @param $idForm int
     *          id del formulario a crear
     * @return array $dataCampos Data registrada del objeto en base de datos
     */
    private function obtenerValuesForUpdate(){
        if(is_array($this->campoUpdate)){
            $this->campoUpdate = $this->campoUpdate[0];
        }
        $multiQuery = FALSE;
        if($this->totalForms>1){
           $query = "";
           foreach ($this->formularios as $key => $form) {
                $q= sprintf("%s where %s=%s;",
                                                $form->query_f,
                                                $form->clave_primaria_f,
                                                $this->campoUpdate
                                            );
               
               $query.=$q;
           }
           $multiQuery = TRUE;
        $this->queryDatosUpdate=$query;   
        }else{
            $query = sprintf("%s where %s=%s",
                                $this->formularios[$this->claveFormulario[0]]->query_f,
                                $this->formularios[$this->claveFormulario[0]]->clave_primaria_f,
                                $this->campoUpdate
                            );
                $this->queryDatosUpdate=$query;
        }
        if($multiQuery===TRUE){
            $result = $this->bd->ejecutarQuery($query,true);
            $data = $this->bd->obtenerDataMultiQuery();
            $dataCampos=array();
            $form=0;
            // Debug::mostrarArray($data,false);
            $Multiple=FALSE;
            $vueltas = 0;
            foreach ($data as $key => $dataForm) {
                
                $totalRegistros = $dataForm['totalRegistros'];
                if($totalRegistros==1){
                    if($form>0){
                        foreach ($dataForm as $key => $value) {
                            if(is_array($value)){
                                foreach ($value as $key => $value) {
                                    $dataCampos[0][$key]=$value;    
                                }    
                            }else{
                                    $dataCampos[0][$key]=$value;
                            }
                        }
                    }else{
                        $dataCampos[0]=$dataForm[0];    
                    }
                    
                }else{
                    $Multiple=TRUE;
                    for($i=0;$i<$totalRegistros;$i++){
                        if(count($dataCampos)>=1){
                            
                            $pos =($Multiple==TRUE)?count($dataCampos)+1:count($dataCampos);
                            $dataCampos[$pos]=$dataForm[$i];    
                        }else{
                            $dataCampos[1]=$dataForm[$i];
                        }
                    }    
                }
                
                ++$form;
            }
            // exit;
        }else{
            $result = $this->bd->ejecutarQuery($query);
            $dataCampos=array();
            while($data =$this->bd->obtenerArrayAsociativo($result)){
               $dataCampos[]= $data;
            }
        }
        
        $dataCampos=array_merge($dataCampos,$this->dataPost);
        $this->valoresUpdate=$dataCampos;
        return $dataCampos;
    }
    
    /**
     * Valida un formulario.
     * @method validarFormulario
     * @param array $datos
     * @return mixed [boolean,array] True si la validacion es correcta, caso contrario arreglo con los errores
     */
    public function validarFormulario(&$datos="") {
        Session::destroy ( '__erroresForm' );
        if(empty($datos)){
            $datos =& $_POST;
        }
        
        $arrErrores = array ();
        // ----------------------------------------------
        $a = 0;
        
        foreach ( $this->camposFormulario as $key => $campo ) {
            
            if(isset($campo['name']) and array_key_exists($campo['name'], $datos) and !is_array($datos[$campo['name']])){
                $datos[$campo['name']] = trim($datos[$campo['name']]);
            }
            
            //obtengo el valor del campo ingresado en el post
            $valorCampo =& $datos [$campo ['name']];
            if (!empty($campo ['eventos'])) {
                
                $validaciones = json_decode ( '{' . $campo ['eventos'] . '}', true );
                
                if (is_array ( $validaciones )) {
                  
                    $a ++;
                    
                    $validador = new ValidadorJida ( $campo, $validaciones, $campo['opciones']);
                    
                    $resultadoValidacion = $validador->validarCampo ( $valorCampo );
                    
                    if ($resultadoValidacion['validacion'] !== true) {
                        $arrErrores [$campo ['name']] = $resultadoValidacion['validacion'];
                    }else
                    if($resultadoValidacion['validacion']===true){
                    
                        //Se connvierten los datos especiales del post
                        if(!is_array($resultadoValidacion['campo'])){
                            if($this->setHtmlEntities===TRUE)
                                $datos[$campo['name']]= htmlspecialchars($resultadoValidacion['campo']);
                            else
                                $datos[$campo['name']]= $resultadoValidacion['campo'];
                        }else{
                            $datos[$campo['name']]= $resultadoValidacion['campo'];
                        }
                        
                    }
                }

            }else{
                if(!is_array($valorCampo)){
                    if($this->setHtmlEntities===TRUE){
                        $datos[$campo['name']]= htmlspecialchars($valorCampo,ENT_QUOTES);
                    }else{
                        $datos[$campo['name']]= $valorCampo;    
                    }
                }else{
                     $datos[$campo['name']]= $valorCampo;
                }
            }
        }//final foreach
        if (count ( $arrErrores ) > 0) {
            $this->errores = $arrErrores;
            Session::set ( '__erroresForm', $this->errores );
            Session::set ( '__dataPostForm', $datos );
            $_SESSION ['__dataPostForm'] ['id_form'] = $this->claveFormulario;
            return $arrErrores;
        } else {
            return TRUE;
        }
    }
    /**
     * Devuelve la estructura de uno o multiples formularios
     * @method getEstructura
     * @param int $totalCampos Numero Total de campos del formulario
     * @return string Estructura del formularios o multiples formularios integrada
     */
    private function getEstructura($totalCampos){
        $estructura = "";
        $i=0;
        foreach ($this->formularios as $key => $formulario) {
            if($i>0)
                $estructura.=";";
            if(empty($formulario->estructura)){
                $estructura.=$totalCampos ."x1";
            }else{
                $estructura .= $formulario->estructura;    
            }
            
            ++$i;
        }
        
        return $estructura;
    }
    /**
     * Arma un formulario a partir de la estructura programada
     * @method armarFormularioEstructura
     * @param array $titulos Matriz para manejo de titulos. si es pasada, crea un fieldset por cada configuracíon. el key es tomado como el 
     * numero del campo en el cual comienza el fieldset, el value debe ser un array con dos key "limite" para indicar el numero de campos 
     * que deben ser abarcados y "titulo" para colocar un legend 
     * 
     * @param boolean $label Permite ocultar los labels si es pasado como false, por defecto es true
     * @example $titulos = array(0=>array('limite'=>10,'titulo'=>'Titulo del fieldset'))
     */
    function armarFormularioEstructura($titulos=array(),$label=TRUE){
        
        $camposForm = $this->armarFormularioArray ( $this->campoUpdate );   
        
        
        
        $validacion ="";
        $form = $this->armarFormularioArray();
        
        if(isset($form['validacion'])){
            $validacion=$form['validacion'];
            unset($form['validacion']);
        }
        $estructura = $this->getEstructura(count($form));
        $formularioRepeticiones = explode(";", $estructura);
        $totalColumnas=count($formularioRepeticiones);
        $formulario="";
        
        $totalDivisiones = 0;
        
        $formulario="";
        if($this->validacionForm===TRUE){
            $formulario .= $validacion;    
        }
                
        if(!empty($this->tituloFormulario)){
            $formulario.="\n\t<div class=\"row\">\n\t\t<div class=\"col-lg-12\">\n\t\t\t";
            $formulario.="<$this->selectorTitulo>$this->tituloFormulario</$this->selectorTitulo>";
            $formulario.="\n\t\t</div>\n\t</div>";    
        }
        if ($this->mostrarMensajes === TRUE and ! empty ( $_SESSION ['__msjForm'] )) {
            $formulario .= $_SESSION ['__msjForm'];
            unset ( $_SESSION ['__msjForm'] );
        } 
        $formKeys = array_keys($form);
        $limiteCampos=0;
        $camposInFieldset=0;
        $contador=0;
        foreach ($formularioRepeticiones as $key => $value) {
                
            if(is_numeric($value)){
                $repeticiones = 1;
                $columnas = $value;
            }else{
                $array = explode("x",strtolower($value));
                if(count($array)>1){
                    $repeticiones = $array[1];
                    $totalColumnas=$totalColumnas+$repeticiones-1;
                    $columnas = $array[0];    
                }else{
                    throw new Exception("No se encuentra definida la estructura en $value correctamente", 210);
                    
                }
            }//fin validacion value numerico
            
            for($i=1;$i<=$repeticiones;$i++){
                if(array_key_exists($contador, $titulos)){
                        $fieldSet=1;
                        $limiteCampos=$titulos[$contador]['limite'];
                        $camposInFieldset=0;
                        $css = (array_key_exists('css', $titulos[$contador]))?$this->cssFieldsetForm." ".$titulos[$contador]['css']:$this->cssFieldsetForm;
                        $formulario.="\n<div class=\"row\">\n\t<div class=\"col-lg-12\">
                                \n\t<fieldset class=\"$css\" id=\"field$this->nameTagForm$contador\">\n\t\t<legend>".$titulos[$contador]['titulo']."</legend>
                                ";
                    }
                $formulario.="\n<div class=\"row\">";
                for($e=1;$e<=$columnas;$e++){
                    
                    if(array_key_exists($contador,$this->estructuraFilas)){
                        
                    }
                    /**
                     * Se calcula el numero de layout para la columna, la misma
                     * no puede ser superior a 12 (Basado en bootstrap3)
                     */
                    $col = (12/(int)$columnas); 
                    $formulario.="\n\t\t\t<div class=\"col-lg-$col\">\n\t\t\t\t<div class=\"form-group\">";
                    if(isset($form[$formKeys[$contador]]['label']) and $label===TRUE){
                        $formulario.="\n\t\t\t\t".$form[$formKeys[$contador]]['label'];
                    }
                    if(isset($form[$formKeys[$contador]]['control'])){
                        //Cierre de columna
                        $formulario.="\n\t\t\t\t\t".$form[$formKeys[$contador]]['control'];
                        $formulario.="\n\t\t\t\t</div>\n\t\t\t</div>";
                                    $contador++;
                                    $camposInFieldset++;    
                    }else{
                        //echo "<hr>No existe $contador".$formKeys[$contador]."<hr>";
                    }   
                    
                }
                //Cierre de fila
                $formulario.="\n\t\t</div>";
                if($limiteCampos>0){
                    
                    if($camposInFieldset == $limiteCampos){
                        #echo "$camposInFieldset == $limiteCampos";
                        $totalCamposInFieldset = 0;
                        $camposInFieldset=0;
                        $formulario.="\n\n\t\t</fieldset>\n\t</div>\n</div>";
                    }
                }   
            }
            $totalDivisiones++;
            
        }
        $onclick = ($this->funcionOnclick == "") ? "" : "onclick=\"$this->funcionOnclick\"";
        if($this->botonGuardado or $this->botonesMultiples){
            $formulario.=$this->crearBotonesFormulario();
            $attrForm=array('name'=>$this->nameTagForm,'method'=>$this->metodo,'enctype'=>$this->enctype,'action'=>$this->action,'id'=>$this->idTagForm,'class'=>$this->cssTagForm,'role'=>'form','target'=>$this->targetForm);
            $formulario=Selector::crear('form',$attrForm,$formulario);
        }

        return $formulario;
    }

    /**
     * Genera el o los botones del formulario
     * @method crearBotonesFormulario
     * 
     */
    private function crearBotonesFormulario(){
        $botones="";
        if(is_array($this->botonesMultiples)){
            
            foreach(array_reverse($this->botonesMultiples) as $key => $valores):
                if(!is_array($valores)){$key=$valores;$valores=array();}
                 $valoresXDefecto = array (  'type' => 'submit',);
                 $arrAtributos = (is_array ( $valores )) ? array_merge ( $valoresXDefecto, $valores ) : $valoresXDefecto;
                if(!array_key_exists('class', $arrAtributos)) 
                    $arrAtributos['class']= $this->classBotonForm;
                $botones.=Selector::crearInput($key,$arrAtributos);
            endforeach;
        }else
        if($this->botonGuardado===TRUE){
            $atributosInput=['class'=>$this->classBotonForm,
                            'type'=>$this->tipoBoton,
                            'name'=>$this->nombreSubmit,
                            'id'=>$this->idBotonForm];
            if($this->funcionJidaJs=='jValidador'){
                $atributosInput['data-jida']='validador';
            }
            if(!empty($this->funcionOnclick))$atributosInput['onclick']=$this->funcionOnclick;
            $btn = Selector::crearInput($this->valueBotonForm,$atributosInput);
            $botones=Selector::crear('section',array('class'=>'row'),Selector::crear('div',array('class'=>'col-md-12 col-xs-12'),$btn));
            
            
        }
        
            
        return $botones;
    }
    function setParametrosFormulario($configuracion){
        $this->establecerAtributos($configuracion, __CLASS__);
    }
    
    /**
     * Crea un mensaje a mostrar en un grid u objeto Tipo Vista
     * 
     * Define valores para las variables de sesion __msjVista e __idVista
     * @method msjVista
     * @param string $type Tipo de mensaje, puede ser: success,error,alert,info
     * @param string $msj Contenido del mensaje
     * @param mixed $redirect Por defecto es false, si se desea redireccionar se pasa la url
     */
    static function msj($type,$msj,$redirect=false){
        $msj = Mensajes::crear($type, $msj);
        Session::set('__msjForm',$msj);
        
        if($redirect){
            redireccionar($redirect);
        }
    }
} // fin clase formulario

?>