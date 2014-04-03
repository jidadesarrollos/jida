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
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 1.2
 * 
 */

class Formulario extends DBContainer {
    
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
    
    public $estructura;
    
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
    // Propiedades del formulario
    private $nameTagForm;
    private $idTagForm;
    private $metodo = "POST";
    /**
     * Define una consulta externa utilizar para un campo de seleccion determinado, 
     * el query debe ser registrado en una posición de arreglo donde el key tenga el nombre del campo; El
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
    /**
     * Define si el boton del formulario tiene una funcion onclick asociada
     */
    var $funcionOnclick;
    /**
     * Estilo del Botón del formulario
     */
    var $classSubmit = "btn btn-primary";
    /**
     * String a Imprimir en el boton del Formulario
     */
    var $valueSubmit;
    /**
     * Tipo del Input creado
     *
     * Por defecto es submit, puede ser especificado como "button"
     */
    var $tipoBoton = "submit";
    /**
     * Define el id a colocar al submit de envio
     */
    private $idSubmit;
    
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
    var $estiloErrorCampo = "error-formcampo";
    private $tablaCampos = "s_campos_f";
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
     * Define si el formulario sera impreso dentro de una etiqueta <form> o no.
     * @var boolean $selectorForm
     * @access public
     * @default TRUE
     */
    var $selectorForm=TRUE;
    /**
     * Muestra el titulo del formulario
     */
    var $tituloFormulario="";
    /**
     * Funcion constructora del formulario
     *
     * Inicializa los valores requeridos para generar un formulario, captura
     * toda la información de los campos del formulario, los datos registrados en caso
     * de ser un formulario para modificación y prepara los estados necesarios para la creación.
     *
     *
     * @param int $id_form
     *          Id del formulario registrado en BD ES OBLIGATORIO
     * @param int $tipoForm
     *          Indica si el formulario viene en 1)Insert o 2)Update.
     * @param int $campoUpdate
     *          Clave primaria del registro a modificar,este parametro solo es pasado si el formulario debe mostrarse en modo update.
     * @param string $metodo
     *          Indica el metodo a usar en el formulario, por defecto es post.
     */
    public function __construct($claveFormulario, $tipoForm = 1, $campoUpdate = "", $metodo = "post") {
        $this->tipoF = $tipoForm;
        $this->action=$_SERVER['PHP_SELF'];
        $this->campoUpdate = $campoUpdate;
        $this->nombreTabla = "s_formularios";
        
        $this->clavePrimaria = 'id_form';
        parent::__construct ();
        
        if (is_int ( $claveFormulario )) {
            $this->campoBusquedaFormulario = "id_form";
            $this->claveFormulario = $claveFormulario;
        } else {
            $this->campoBusquedaFormulario = "nombre_identificador";
            $this->claveFormulario = "'$claveFormulario'";
        }
        
        $this->metodo = $metodo;
        
        $this->obtenerDatosFormulario ();
        $this->obtenerCamposFormulario ();
        
        $this->checkCache ();

    }
    
    private function checkCache() {
        
        if (isset ( $_SESSION ['__nombreForm'] ))
            $this->dataCache = $_SESSION ['__nombreForm'];
        
        if (isset ( $_SESSION ['__nombreForm'] ) and $_SESSION ['__nombreForm'] == $this->claveFormulario) {
            
            $this->errores = Session::get ( '__erroresForm' );
            if (isset ( $this->campoUpdate ) and $this->campoBusquedaFormulario != "" and ! isset ( $_SESSION ['__idCampoUpdate'] )) {
                Session::set ( '__idCampoUpdate', $this->campoUpdate );
            } else if (isset ( $_SESSION ['__idCampoUpdate'] ) and ! empty ( $_SESSION ['__idCampoUpdate'] )) {
                if ($this->campoUpdate != $_SESSION ['__idCampoUpdate']) {
                    
                    Session::destroy ( '__dataPostForm' );
                    Session::destroy ( '__erroresForm' );
                }
            }
            if (isset ( $_SESSION ['__dataPostForm'] ['id_form'] ) and $_SESSION ['__dataPostForm'] ['id_form'] == $this->id_form) {
                
                $dataPostCache = Session::get ( '__dataPostForm' );
                $this->dataPost = $dataPostCache;
                Session::destroy ( '__dataPostForm' );
            }

        } else {
            
            Session::set ( '__nombreForm', $this->claveFormulario );
            Session::destroy ( '__dataPostForm' );
        }
        Session::destroy ( '__dataPostForm' );
    }
    /**
     * Obtiene los datos del formulario instanciado
     */
    protected function obtenerDatosFormulario() {
        $query = "select * from $this->nombreTabla where $this->campoBusquedaFormulario=$this->claveFormulario";
        $formulario = $this->bd->obtenerArrayAsociativo ( $this->bd->ejecutarQuery ( $query ) );
        
        $this->establecerAtributos ( $formulario, __CLASS__ );
        
        $this->inicializarValoresForm ();
    }
    
    /**
     * Obtiene los campos que conforman el formulario a crear
     *
     * Consulta la tabla de campos en base de datos y arma un arreglo con la configuración
     * de cada campo
     */
    protected function obtenerCamposFormulario() {
        $query = "select * from $this->tablaCampos where id_form=$this->id_form order by orden asc";
        
        $this->camposFormulario = $this->bd->obtenerDataCompleta ( $query );
    }
    
    /**
     * Define los valores y atributos para un formulario en HTML
     *
     * Crea los atributos nombre,id y tabla para el formulario.
     */
    protected function inicializarValoresForm() {
        $nombreFormSinEspacios = str_replace ( " ", "", ucwords ( $this->nombre_f ) );
        $this->nameTagForm = "form" . $nombreFormSinEspacios;
        $this->idTagForm = $this->nameTagForm;
        $this->nombreSubmit = "btn" . $nombreFormSinEspacios;
        $this->idSubmit = $this->nombreSubmit;
        
        $this->valueSubmit = ($this->tipoF == 1) ? 'Guardar' : 'Modificar';
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
        $formulario = $validacion . "<form name=\"$this->nameTagForm\" method=\"$this->metodo\"  enctype=\"$this->enctype\" action=\"$this->action\" id=\"$this->idTagForm\" class=\"$this->cssTagForm\" role=\"form\">
        ";
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

        $onclick = ($this->funcionOnclick == "") ? "" : "onclick=\"$this->funcionOnclick\"";
        $formulario .= "\n\t<div class=\"row\">\n\t\t<div class=\"col-lg-12\">\n\t\t\t";
        $formulario.="\n\t\t\t\t<input $onclick type=\"$this->tipoBoton\" class=\"$this->classSubmit pull-right\"name=\"$this->nombreSubmit\" value=\"$this->valueSubmit\" id=\"$this->idSubmit\">";
        $formulario.="\n\t\t\t</div>\n\t\t</div>\n\t</form>";
        return $formulario;
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
                        <input $onclick type=\"$this->tipoBoton\" class=\"$this->classSubmit\" name=\"$this->nombreSubmit\" value=\"$this->valueSubmit\" id=\"$this->idSubmit\">
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
        if ($vuelta == 0) {
            $js = "\n\t\t'$campo':";
        } else {
            $js = ",\n\t\t'$campo':";
        }
        /*
        if(strrpos("mensaje",$eventoCampo)!==FALSE){
            echo "ak<hr>";
                $js .= "\n\t\t\t\t" . $eventoCampo . "";
        }else{
         */
            $js .= "\n\t\t\t\t{" . $eventoCampo . "}";
        #}
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
        $nameBotonJs = $this->idSubmit;
        $js = "\n\r<SCRIPT>\n\t
        
        $(document).ready(function(){\n\t
        
        var validador = new jd.validador(\n\t\t" . "\"" . $nameBotonJs . "\",{";
        $js .= $json . "\n\t\t\t\t\t}
        );
        \r\t})\n</SCRIPT>\n";
        return $js;
    }
    
    /**
     * Armar formularios a partir de quer$id_formy
     *
     * Genera un array asociativo con los controles requeridos.
     *
     * @param int $id_form
     *          => id del formulario a buscar en j_formulario
     * @param int $type
     *          => 1. Insert, 2. Update
     * @param array $campoUpdate
     *          => clave primaria del campo a buscar para traer el formulario en modo update
     */
    public function armarFormularioArray($campoUpdate = "") {
        if ($campoUpdate != "")
            $this->campoUpdate = $campoUpdate;
        
        $formulario = array ();
        $dataUpdate = "";
        $vuelta = 0;
        $javascript = "";
        
        if ($this->tipoF == 2) {
            $dataUpdate = $this->obtenerValuesForUpdate($this->id_form );
        }
        
        if (is_array($this->dataPost) and count($this->dataPost > 0) and $this->dataCache == $this->claveFormulario) {
            $dataUpdate = $this->dataPost;
        }

        foreach ( $this->camposFormulario as $posicion => $arr ) {
            
            if ($arr ['eventos'] != "") {
                $javascript .= $this->llamadaJson ( $arr ['id_propiedad'], $arr ['eventos'], $vuelta );
                $vuelta ++;
            } 
            
            $controlHTML = new CampoHTML ( $arr, $dataUpdate, $this->externo );
            // i es agregado un query a la clase formulario es pasado a la clase campo en el momento de creacion del control.
            
            $control = $controlHTML->crearControl ();
            
            /* Agregar error si existe */
            if (isset ( $this->errores [$arr ['name']] )) {
                $control .= "<DIV class=\"$this->estiloErrorCampo\">" . $this->errores [$arr ['name']] . "</div>";
            }
            
            $formulario [$arr ['name']] = array (
                    "control" => $control 
            );
            if ($arr ['control'] != 1) {
                $label = "<label for=\"$arr[name]\">$arr[label]</label>";
                $formulario [$arr ['name']] ['label'] = $label;
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
        $query= $this->query_f." where $this->clave_primaria_f=$this->campoUpdate";
        $result = $this->bd->ejecutarQuery($query);
        $dataCampos=array();
        while($data =$this->bd->obtenerArrayAsociativo($result)){
           $dataCampos[]= $data;
        }
        
        
        #$dataCampos = $this->bd->obtenerArrayAsociativo($this->bd->ejecutarQuery($query));
        
        return $dataCampos;
    }
    
    /**
     * Valida un formulario.
     */
    public function validarFormulario(&$datos) {
        Session::destroy ( '__erroresForm' );
        if (! isset ( $datos ['readonly'] ))
            $datos ['readonly'] = FALSE;
        if (! isset ( $datos ['disable'] ))
            $datos ['disable'] = FALSE;
        
        $arrErrores = array ();
        // ----------------------------------------------
        $a = 0;
        foreach ( $this->camposFormulario as $key => $campo ) {
            
            if(!is_array($datos[$campo['name']])){
                $datos[$campo['name']] = trim($datos[$campo['name']]);
            }
            $valorCampo = $datos [$campo ['name']];
            
            if ($campo ['eventos'] != "") {
                $validaciones = json_decode ( '{' . $campo ['eventos'] . '}', true );
                
                if (is_array ( $validaciones )) {
                    $a ++;
                    $validador = new ValidadorJida ( $campo, $validaciones, $campo['opciones']);
                    $resultadoValidacion = $validador->validarCampo ( $valorCampo );
                    if ($resultadoValidacion !== true) {
                        $arrErrores [$campo ['name']] = $resultadoValidacion;
                    }
                } else {
                    // cho "<hr>$datos[eventos] ------------ $campo[name] No funciona.<hr>";
                }
            }
        }
        
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


    function armarFormularioEstructura($titulos=array(),$label=TRUE){
        
        $camposForm = $this->armarFormularioArray ( $this->campoUpdate );   
        $estructura = $this->estructura;
        
        
        $formularioisiones = explode(";", $estructura);
        $formulario="";
        
        $totalDivisiones = 0;
        $validacion ="";
        $form = $this->armarFormularioArray();
        #Arrays::mostrarArray($form);
        if(isset($form['validacion'])){
            $validacion=$form['validacion'];
            unset($form['validacion']);
        }
        if($this->selectorForm===TRUE){        
            $formulario ="<form name=\"$this->nameTagForm\" method=\"$this->metodo\"  enctype=\"$this->enctype\" action=\"$this->action\" id=\"$this->idTagForm\" class=\"$this->cssTagForm\" role=\"form\">
        
            ";
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
        foreach ($formularioisiones as $key => $value) {
            
            if(is_numeric($value)){
                
                $repeticiones = 1;
                $columnas = $value;
            }else{
                
                $array = explode("x",strtolower($value));
                
                $repeticiones = $array[1];
                $columnas = $array[0];
                
                #Arrays::mostrarArray($array);
            }
            
            
            for($i=1;$i<=$repeticiones;$i++){
                if(array_key_exists($contador, $titulos)){
                        $fieldSet=1;
                        $limiteCampos=$titulos[$contador]['limite'];
                        $camposInFieldset=0;
                        $css = (array_key_exists('css',$titulos[$contador]))?$this->cssFieldsetForm." ".$titulos[$contador]['css']:$this->cssFieldsetForm;
                        $id =  (array_key_exists('id',$titulos[$contador]))?$titulos[$contador]['id']:"field".$this->nameTagForm."$contador";
                        $formulario.="\n<div class=\"row\">\n\t<div class=\"col-lg-12\">
                                \n\t<fieldset id=\"$id\" class=\"$css\">\n\t\t<legend>".$titulos[$contador]['titulo']."</legend>
                                ";
                    }
                $formulario.="\n<div class=\"row\">";
                for($e=1;$e<=$columnas;$e++){
                    
                    
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
                        echo "<hr>No existe $contador".$formKeys[$contador]."<hr>";
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
                    }else{
                    #   echo "$camposInFieldset == $limiteCampos<hr>";
                    #   $camposInFieldset++;
                    }
                }   
            }
            $totalDivisiones++;
            
        }
        $onclick = ($this->funcionOnclick == "") ? "" : "onclick=\"$this->funcionOnclick\"";
        $formulario .= "<div class=\"row\">";
        $formulario.="<div class=\"col-md-12\"><input $onclick type=\"$this->tipoBoton\" class=\"$this->classSubmit pull-right\"
                        name=\"$this->nombreSubmit\" value=\"$this->valueSubmit\" id=\"$this->idSubmit\">";
        if($this->selectorForm===TRUE){
            $formulario.="</form>";
        }
                                    
        $formulario.="</div></div>".$validacion;
        return $formulario;
        
    }
    function setParametrosFormulario($configuracion){
        $this->establecerAtributos($configuracion, __CLASS__);
    }
} // fin clase formulario

?>