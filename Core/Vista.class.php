<?PHP
/**
 * Genera Vistas Dinamicas en HTML
 * 
 * Permite configurar un Grid (Vista) personalizado, basado en un query a una
 * tabla o vista de base de datos, da opciones para configurar botones para la vista,
 * estilo y manejo de mensajes.
 * 
 * 
 * @package Framework
 * @category Core
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @version 1.2  
 */


class Vista extends DBContainer{

    /**
     * Define consulta a la base de datos a partir de la cual
     * se generará la vista o grid
     * @var $query
     */
    private $query;
    /**
     * Instancia de objeto Tabla para crear Vista
     * @var object $tabla Objeto tipo Table
     * 
     */
    var $tabla;
    /**
     * Arreglo asociativo para almacenar sentencias adicionales para el query
     * @var array $sentenciasQuery
     * 
    */
    var $tituloColumnaOpciones="";
    var $sentenciasQuery=array();
    private $vista;
    private $titulos;
    private $resultQuery;
    
    
    
    /* Control de botones */
    /**
     * Define si el grid llevará un botón para nuevos registros
     * @var boolean $botonNuevo
     * 
     * @see $botones
     */ 
    var $botonNuevo=FALSE;
    /**
     * Define si el grid llevará un botón de modificar un registro
     * 
     * @var boolean $botonModificar
     * @see $botonesboton
     */ 
    var $botonModificar=FALSE; 
    
    
    /**
     * Define si el grid llevará un botón de eliminar un registro
     * @var boolean $botonEliminar
     *
     * @see $botones
     */ 
    var $botonEliminar=FALSE;
    /**
     * 
     * 
     * Arreglo asociativo que permite definir las acciones que llevará el grid y las caracteristicas
     * de los mismos, las cuales son definidas por medio de un arreglo asociado al titulo del botón.
     * 
     * El nombre ingresado representa el html a mostrar en el enlace en un input.
     * 
     * Por cada accion agregada debe pasarse un arreglo con los parametros, siendo obligatorio el valor
     * href dentro del parametro, el cual indica hacia donde se dirige la accion; 
     * @var array botonEliminar
     * 
     * @example array("Nuevo","Modificar"=>array('type'=>'button'))
     */
    var $acciones=FALSE;
    /**
     * Indica si la vista lleva un campo de seleccion
     * (Radio o checkbox)
     */
    var $controlFila = TRUE;
    /**
     * Define el tipo de control que puede tener la vista o grid.
     * 
     * El tipo de control es 1 por defecto.
     * Los tipos de control pueden ser:
     * <ul>
     *  <li>Radio</li>
     *  <li>Checkbox</li>
     * </ul>
     *
     * @var string $tipoControl
     */
    var $tipoControl=1;
    /* ESTILO */
    /**
     * Define estilo css para div de mensaje de error
     */
    var $cssMensajeError;
    
    /**
     * Define un estilo para las columnas especificadas
     * 
     * Recibe un arreglo con nombre de la columna y los distintos
     * estilos que puede tener según los valores de la misma
     * 
     * @example $cssPorFila = array("estatus"=>array("1"=>"estiloUno","2"=>"estilo2"))
     * @var array $cssPorFila
     */
    var $cssPorFila;
    
    /* propiedades de la vista o grid.*/
    /**
     * Define el nombre de la vista
     */
    private $nombreVista;
    /**
     * Define el Id del formulario de la vista
     */
    private $idFormVista;
    /**
     * Define el nombre del formulario de la vista
     * @var string $nombreFormVista
     */
    private $nombreFormVista;
    
    /**
     * Define el estilo css del formulario de busqueda
     * @var $cssFormBusqueda
     * @access private
     */
    private $cssFormBusqueda="navbar-form navbar-right";
    /**
     * Instancia del objeto paginador
     * @var object $paginador
     */
    
    private $objetoPaginador=false;
    /**
     * Uso paginador
     * @var mixed $configPaginador
     */
    private $paginador=false;
    /**
     * Define el Id del div de la vista.
     */
    private $idDivVista;
    /**
     * Indica el ordenamiento posible por una columna
     * @var int orderBy 
     */
    private $orderBy=1;
    
    /**
     * URL a la que irán las peticiones hechas por medio del formulario de la vista
     * 
     * @var string $actionForm;
     */
     var $actionForm="#";
     /**
      * Define el metodo del formulario del grid. Es definido POST por defecto
      * 
      * @var string $methodForm
      */
    var $methodForm="POST";
    /**
     * Define si se agrega una columna de opciones
     * 
     * Las opciones deben ser pasadas por medio de la siguiente estructura
     * de array:
     * 
     * array('claveNumerica'=>elementoOpcion)
     * 
     * Donde elementoOpción debe ser otro arreglo con la siguiente estructura:
     * array("SelectorHTML"=>array(atributos,html))
     * <ul>
     * <li>EL parametro <strong> atributo </strong>referencia a todos los atributos que pueda contener
     * el selector y deben ser pasados por medio de un arreglo de estructura
     * clave=>valor donde clase será el atributo y valor el contenido del atributo
     * </li>
     * <li>El parametro <strong>content </strong>hace referencia a contenido escrito que se desee colocar dentro
     * de las etiquetas de apertura y cierre del selector creado
     * </li>
     * <li>El parametro <strong>HTML</strong> permite crear otro selector html dentro del selector creado, puede recibir
     * un arreglo con la misma estructura
     * array("SelectorHTML"=>array(atributos,content,html)).
     * </li>
     * 
     *  
     */
    var $filaOpciones;
    /**
     * Define el Mensaje a mostrar en caso de que no se consigan registros
     */
     var $mensajeError;
    /**
     * Indica la columna q le dará valor al control
     */
     var $nroControl=0;
    /**
     * Instancia del objeto paginador en caso de que la vista lo utilice
     * @var object $paginador;
     */
 
    /**
     * Define si la vista lleva un campo de busqueda.
     * @var boolean $seccionBusqueda
     */
    var $seccionBusqueda = FALSE;
    /**
     * Arreglo que define las opciones para el breadcrumb
     * Por defecto se encuentra inicializado en false para que no aparezca
     * @var $opcionesBreadCrumb
     * 
     * array("selector"=>"a",data,etiqueta)
     */
    var $opcionesBreadCrumb=FALSE; 
     /**
     * Define estilo para selector  <section> de la vista
     * @var string $cssTagSection
     */ 
    var $cssSection = "seccionVista";
    /**
     * Define estilo de clase para selector <table> de la vista
     * @var string $cssTable;
     */
    var $cssTable="vista";
    
    /**
     * Define estilo de clase css para fila de titulos
     * @var string $cssFilaTitulos
     */
    var $cssFilaTitulos="opciones-row-titulo";
    /**
     * Define el estilo de clase css para la fila con botones de la vista
     * @var string $cssFilaAcciones;
     */
     var $cssFilaAcciones="botonForm";
    /*
     * Define el estilo css para la sección busqueda
     * 
     * @var string $seccionBusqueda
     * @access private
     */
    private $cssSectionBusqueda="row";
    /**
     * Define el estilo para el div correspondiente a la columna de la busqueda, por defecto es
     * col-md-12
     */
    private $cssColBusqueda="col-md-12";
    /**
     * Define estilo para el <div> De la sección de busqueda de la vista
     * @var $cssDivBusqueda
     */
    var $cssDivBusqueda="busqueda-vista";
    /**
     * Define estilo para el <input> Text de la busqueda
     * @var $cssInputTextBusqueda
     */
    var $cssInputTextBusqueda="busqueda-input";
     /**
      * Define clase de estilo css para el botón de busqueda
      * @var $cssBotonBusqueda
      */
    var $cssBotonBusqueda="boton-busqueda";
    
    /**
     * Define estilo para el div que envuelve el input de busqueda
     * @var string $cssDivInputTextBusqueda
     * @access private
     */
    private $cssDivInputTextBusqueda;
    
    /**
     * Define estilo para el div que envuelve el botón de busqueda
     * @var string $cssDivInputBtnBusqueda
     * @access private
     */
    private $cssDivInputBtnBusqueda;
    /**
     * Define estilo css para el titulo, en caso de que la vista lo tenga
     * @var string $cssTitulo
     */
    private  $cssTituloVista="";
    /**
     * Define estilo css para fila de opciones 
     * @var cssFilaOpciones
     * @access private
     */
    /**
     * Define estilo css para fila de opciones 
     * @var cssFilaOpciones
     * @access private
     */
    private $cssFilaOpciones="opciones-row";
    /**
     * Define estilo para la lista <ol> del
     * breadcrumb agregado a la vista
     * @var string $cssBreadcrumb
     * @access private
     */
    
    private $cssBreadcrumb="breadcrumb";
    
    
    /**
     * Define estilo para botones de accion por defecto
     * @var string $cssBtnAccion;
     * @access private
     */
    private $cssBtnAccion="btn";
    /**
     * Define titulo a mostrar en la vista
     * @var string $tituloVista
     */
    var  $tituloVista;
    /**
     * Define selector HTML a usar en el titulo
     * @var string $selectorTitulo
     */
    private $selectorTitulo="h1";
    var $valueBotonBusqueda="Buscar";
    /**
     * Define el tipo de botón para la sección de busqueda
     */
    var $typeBotonBusqueda="submit";
    /**
     * Define nombre del boton de la seccion de busqueda
     * 
     * El nombre es definido en la clase para uso de la busqueda via ajax, en caso de cambiarse
     * debe tenerse en cuenta
     */
    private $nombreBotonBusqueda="";
    /**
     * Define el nombre del campo de texto para la busqueda de la vista
     * @var $nombreInputTextBusqueda
     */
    private $nombreInputTextBusqueda;
    /**
     * Define el contenido que puede tener el boton de la sección
     * de busqueda
     * @var $htmlButtonBusqueda
     */
    private $htmlButtonBusqueda="Buscar";
    /**
     * Define los campos posibles de busqueda para la vista
     * @var array $camposBusqueda
     */
     var $camposBusqueda=array();
     
     /**
      * Total de registros de la consulta de la vista
      * @var $totalRegistros;
      */
     var $totalRegistros;
     
     
     var $columnasOcultas="";
     
     private $nombreVistaSinEspacios="";
    private $data;
       /**
     * Metodo constructor para clase vista
     * @param string $query Consulta SQL a ejecutar para crear el grid
     * @param array $arregloConfiguracion Puede ser pasado el arreglo de configuración de la vista completo el cual debe tener un key
     * 'paginador' para la configuración del mismo, caso contrario será tomado como el arreglo de configuración del paginador
     * @param string $nombreVista Nombre de la vista
     * @param mixed $global Arreglo o nombre del key del arreglo global para la configuración de la vista 
     */
    function __construct($query,$arregloConfiguracion=false,$nombreVista="Vista",$global=null){
        
        $totalParametros=func_num_args();
        $totalParametros = func_num_args();
        $this->query=$query;
        
        if(!is_null($global) and is_string($global)){
            $arrayConfiguracion=$GLOBALS[$global];
        }elseif(is_array($global)){
            $arrayConfiguracion=$global;    
        }
        
        parent::__construct(__CLASS__);
        
        $this->bd->ejecutarQuery($this->query);
        $this->totalRegistros = $this->bd->totalRegistros;
        $this->nombreVista = $nombreVista;
        /**
         * Si $arregloConfiguracion no tiene un key paginador es porque se ha pasado el arreglo de
         * configuración del Paginador
         */
        if(!array_key_exists('paginador', $arregloConfiguracion)):
            $this->paginador=$arregloConfiguracion;
        else:
            $this->establecerAtributos($arregloConfiguracion);
        
        endif;
        
        $this->inicializarValoresVista();
        $this->addPaginador();
        /*Ejecución del query para la vista*/
        $this->data = $this->bd->obtenerDataCompleta($this->query);
        /*Creación de objeto tabla*/
        
    }
    /**
     * Agrega paginador a la vista
     * 
     * Modifica la consulta SQL de la vista para que funcione en base a la configuración del Paginador agregado
     * @method agregarPaginador 
     */    
    private function addPaginador(){
        if(!is_array($this->paginador)){
            $this->paginador=array();
        }
        $ar = explode("/",$_SERVER['REQUEST_URI']);
        if(in_array('pagina', $ar)){
            $pos = array_search('pagina', $ar);
            unset($ar[$pos+1]);unset($ar[$pos]);
            
        }
        $this->paginador['paginaConsulta']=implode("/", $ar);
        $this->paginador['selectorVista']=$this->idDivVista;
        $this->paginador['nombreVista']=$this->nombreVistaSinEspacios;
        $this->objetoPaginador = new Paginador($this->query,$this->paginador,$this->sentenciasQuery);
        $this->query=$this->objetoPaginador->query;
    }
   
    /**
     * Define los valores identificadores de la vista
     * 
     * Crea los nombres para los atributos html genericos identificadores para la vista como
     * el id del DIV padre, nombre y id del formulario vista.
     * @method inicializarValoresVista
     */
    private function inicializarValoresVista(){
        $nombreVistaSinEspacios = str_replace(" ", "",$this->nombreVista);
        $this->nombreVistaSinEspacios=$nombreVistaSinEspacios;
        $this->nombreFormVista="form".ucwords($nombreVistaSinEspacios);
        $this->tituloVista =(!empty($this->tituloVista))?$this->tituloVista:$this->nombreVista;
        $this->mensajeError=(!empty($this->mensajeError))? $this->mensajeError: "No hay registro de $this->tituloVista";
        $this->idDivVista="div".ucwords($nombreVistaSinEspacios);
        $this->idFormVista="vista".ucwords($nombreVistaSinEspacios);
        $this->nombreBotonBusqueda="btnBusq".ucwords($nombreVistaSinEspacios);
        $this->nombreInputTextBusqueda="txtBusq".ucwords($nombreVistaSinEspacios);
        $this->mensajeError=(!empty($this->mensajeError))?$this->mensajeError:"No hay registros <a href=\"$_SERVER[PHP_SELF]\">volver</a>";;
    }
    
  
    /**
     * Crea los titulos para la vista o grid
     * @method obtenerTitulos
     * @access private
     * @return string $titulos
     
     */
    private function obtenerTitulos() {
        
        $i=0;
        $titulos=array();
        while($i< $this->bd->totalField($this->resultQuery)){
            if($i==0 and $this->controlFila==TRUE){
                if($this->tipoControl==2){
                    $titulos[$i]=Selector::crearInput(null,array('data-jvista'=>"seleccionarTodas",'name'=>"obtTotalCol","id"=>'obtTotalColm','type'=>'checkbox'));
                    
                }else{
                    if($this->tipoControl==3){
                        $titulos[$i]="";    
                    }else{  
                        $titulos[$i]="";
                    }
                    
                }
            }else{
                $titulos[$i]=$this->bd->obtenerNombreCampo($this->resultQuery, $i);
            }//fin if
            $i++;
        }
        return $titulos;
    }//final funcion
    
    /**
     * Devuelve la vista Armada
     * 
     * Verifica si la vista esta siendo creada por primera vez o como respuesta
     * de la interacción del usuario con las funcionalidades basadas en ajax
     * de la misma.
     * 
     */
    function obtenerVista(){
        if(isset($_POST) and !empty($_POST)){
            return $this->procesarAccion($_POST);
        }else{
            $data = array('id'=>$this->idDivVista,'data-sitio'=>"$_SERVER[REQUEST_URI]","class"=>$this->cssSection);
            $vista = Selector::crear('SECTION',$data,$this->crearVista());
            return $vista;
        }
    }//fin funcion
    /** 
     * Define los valores principales para el titulo de la vista
     * 
     * @var string $tituloVista Titulo a mostrartse en la vista
     * @var string $cssTitulo @see $cssTituloVista
     * @var string $selector @see $selectorTituloVista
     * @return void
     */
    function setTituloVista($tituloVista,$cssTitulo,$selector="h3"){
        $this->tituloVista = $tituloVista;
        $this->cssTituloVista = $cssTitulo;
        $this->selectorTitulo = $selector;
    }
    /**
     * Genera una vista o grid HTML a partir de un query
     *
     * @return string $titulos
     * @author  Julio Rodriguez <jirodriguez@sundecop.gob.ve>
     */
     protected function crearVista(){
        $vista="";

            
        if(!empty($this->tituloVista)){
            $vista.=Selector::crear($this->selectorTitulo,array('class'=>$this->cssTituloVista),$this->tituloVista);
        }
        
        
        if($this->totalRegistros>0){
        
            $this->tabla = new Table($this->data,$this->obtenerTitulos());
            $this->agregarOpcionesFila();
            $this->addControlFila();
            /* Obtener Acciones de la vista*/
            $acciones = $this->obtenerAccionesVista($this->bd->totalField($this->resultQuery));            
            $this->tabla->class=$this->cssTable;
            //Se agrega la sección de busqueda
            if($this->seccionBusqueda===TRUE){
                $vista.=$this->agregarSeccionBusqueda();
            }
            //Se valida si existe un mensaje a mostrar
            if(Session::get('__msjVista')):
                if( Session::get('__idVista') and strtolower($this->nombreVistaSinEspacios)==strtolower(Session::get('__idVista')) or
                    (isset($_SESSION['__idVista']) and strtolower($this->idDivVista)== strtolower(Session::get('__idVista')))
                   ){
                       
                     $vista.=Session::get('__msjVista');   
                     Session::destroy('__idVista');
                    }
            endif;
            $vista.=$this->tabla->getTabla();
            $vista = Selector::crear('div',array('class'=>'col-md-12'),$vista);
            $vista = Selector::crear('article',array('id'=>'art'.$this->nombreVistaSinEspacios,'class'=>'row'),$vista);
            
            
            if($this->paginador!==FALSE)
                $vista.= $this->objetoPaginador->armarPaginador();
            
            $vista .= "
                <script>
                $( document ).ready(function(){
                    vista = new jd.vista(\"$this->idDivVista\",$this->tipoControl);
                    vista.armarVista();
                })
                </script>
                
            </section>
            ";
            return $vista;
        }else{
            return $vista.Selector::crear('div',array('class'=>$this->cssMensajeError),$this->mensajeError);
        }
        
            
         
    }//fin funcion crearVista
    

 
    /**
     * Agrega los botones para las acciones especificadas a la vista
     * @method obtenerAccionesVista
     * @access private
     * @return string $botones
     */
    private function obtenerAccionesVista($totalColumnas){
        
        if($this->filaOpciones==TRUE){
            $totalColumnas=$totalColumnas+1;
        }
        $botones="";
        $i=0;
        
        if(is_array($this->acciones)){
            
            $acciones="";
            foreach($this->acciones as $accion =>$atributos){
                
                if(is_array($atributos)){
                    
                    //Nombre a colocar en el enlace
                    $atributos['content'] = $accion;
                    //Definicion de selector
                    $selector = "a";
                    if(!isset($atributos['class']))$atributos['class']=$this->cssBtnAccion;
                    if(!isset($atributos['data-multiple'])){
                        $atributos['data-multiple']='false';
                        
                    }
                    $campo = CampoHTML::crearSelectorHTMLSimple($selector,$atributos);
                }else{
                    $campo = CampoHTML::crearSelectorHTMLSimple($atributos);    
                }
                
               $acciones.=$campo;
            }
            
            $totalFilas=$this->tabla->getTotalFilas();
            $this->tabla->tr[$totalFilas]=new Selector('TR');
            $this->tabla->tr[$totalFilas]->class=$this->cssFilaAcciones;
            $filaAcciones =& $this->tabla->tr[$totalFilas];
            $columnaAcciones = new Selector('TD');
            $columnaAcciones->contenido=$acciones;
            $columnaAcciones->attr['colspan']=$totalColumnas;
            $filaAcciones->td[0]=$columnaAcciones;
            $filaAcciones->contenido=$filaAcciones->td[0]->getSelector();            
        }//final if acciones
                
    }//fin funcion obtenerBotonesVista
    /**
     * Agrega opciones a cada fila del grid las opciones que se deseen agregar.
     * 
     * Crea una nueva columna agregando el contenido HTML pasado por medio del
     * atributo "dataOpcionFila"
     * @see @var $dataOpcionFila
     * @param string $campo Cadena con botones adicionales agregados.
     */
     private function agregarOpcionesFila($campo=""){
         $tabla =& $this->tabla;
         $totalCols = $this->tabla->getTotalColumnas();
         
         if($this->filaOpciones==TRUE):
            $tabla->thead->tr->th[$totalCols]=new Selector('TH');
            $tabla->thead->tr->th[$totalCols]->class=$this->cssFilaOpciones;
            $tabla->thead->tr->th[$totalCols]->contenido=$this->tituloColumnaOpciones;
             
            for($i=0;$i<$this->tabla->getTotalFilas();$i++){
                
                $campo = $tabla->tr[$i]->td[0]->contenido;
                $opciones = $this->setOpcionesFila($campo);
                $tabla->tr[$i]->td[$totalCols]= new Selector('TD');
                $tabla->tr[$i]->td[$totalCols]->class=$this->cssFilaOpciones;
                $tabla->tr[$i]->td[$totalCols]->contenido=$opciones;
                
            }//fin for
         endif;
         
     }

    private function setOpcionesFila($campo){
        $opciones = Selector::crearInput('hidden',array('type'=>'hidden','name'=>'clave','value'=>$campo));
        $arrayExample=array ('atributos' => array (),'html' => false);
        //Se recorren los indices
        foreach ( $this->filaOpciones as $key => $dataSelector ) {
                // -------------------------------------------------------------
                //se obtiene el selector y recorren las propiedades
                foreach ( $dataSelector as $selector => $props ) {
                    // -------------------------------------------------------------
                    
                    $data = array_merge ( $arrayExample, $props );
                    $html = "";
                    //Se valida si el HTML es otro selector a crear
                    if (is_array ( $data ['html'] )) {
                        foreach ( $data ['html'] as $key => $value ) {
                            // Verificar si se ha pasado la palabra {clave} para uso del id de la vista
                            // y hacer el reemplazo por el id actual de la columna
                            $dataHtml = array_merge ( $arrayExample, $value );
                            $implode = implode ( ',', $dataHtml ['atributos'] );
                            $implode = str_replace ( '{clave}', "$campo", $implode );
                            $dataHtml ['atributos'] = array_combine ( array_keys ( $dataHtml ['atributos'] ), explode ( ",", $implode ) );
                            $content = (! is_array ( $dataHtml ['html'] )) ? $dataHtml ['html'] : "";
                            $html .= Selector::crear ( $key, $dataHtml ['atributos'], $content );
                        }
                    }
                    
                    if (is_array ( $data ['atributos'] )) {
                        $implode = implode('||', $data['atributos']);
                        
                        $implode = str_replace ( '{clave}', "$campo", $implode );
                    }else{
                        echo "no es un array";Exit;
                    }
                    
                    $data ['atributos'] = array_combine ( array_keys ( $data ['atributos'] ), explode("||",$implode));
                    $content = (! is_array ( $data ['html'] )) ? $data ['html'] : "";
                    $opciones .= Selector::crear( $selector, $data ['atributos'], $html . $content );
                    // -------------------------------------------------------------
                } // final primer foreach
            } // final segundo foreach
            
        return $opciones;
    }
     
    // private function agregarOpcionesFila($campo){
//         
        // $opciones="<td class=\"$this->cssFilaOpciones \">";
        // $opciones.="<input type=\"hidden\" name='clave' value=\"$campo\"/>";
        // $arrayExample = array('atributos'=>array(),'html'=>false);
//         
        // foreach ($this->filaOpciones as $key => $dataSelector) {
            // #--------------------------------------------------------------
            // foreach($dataSelector as $selector => $props){
                // #--------------------------------------------------------------
//                 
//                 
                // $data = array_merge($arrayExample,$props);
                // $html="";
                // //Arrays::verArray($data);
                // if(is_array($data['html'])){
                    // foreach($data['html'] as $key => $value){
                        // //Verificar si se ha pasado la palabra {clave} para uso del id de la vista
                        // //y hacer el reemplazo por el id actual de la columna
                        // $dataHtml=array_merge($arrayExample,$value);
                        // $implode = implode(',', $dataHtml['atributos']);
                        // $implode = str_replace('{clave}', "$campo", $implode);
                        // $dataHtml['atributos'] = array_combine(array_keys($dataHtml['atributos']), explode(",",$implode));
                        // $content = (!is_array($dataHtml['html']))?$dataHtml['html']:"";                              
                        // $html.=CampoHTML::crearSelectorHTMLSimple($key,$dataHtml['atributos'],$content);
                    // }
                // }
//                 
                // if(is_array($data['atributos'])){
                    // $implode = implode('||', $data['atributos']);
//                     
                    // $implode = str_replace('{clave}', "$campo", $implode);
                // }
//                 
                // $data['atributos'] = array_combine(array_keys($data['atributos']), explode("||",$implode));
                // $content = (!is_array($data['html']))?$data['html']:"";
                // $opciones.=CampoHTML::crearSelectorHTMLSimple($selector, $data['atributos'],$html.$content);
                // #--------------------------------------------------------------
            // }//final primer foreach
            // #--------------------------------------------------------------
        // }//final segundo foreach
        // $opciones.="</td>";
        // return $opciones;    
//     
//         
    // }#Fin funcion opciones Fila
    /**
     * Crea el control de la fila
     */
    private function controlFila($valorC,$tipocontrol){
        $control=array(1=>'radio',2=>'checkbox',3=>'txt');
        $nombreControl = ($tipocontrol==1)?"seleccionar":"seleccionar[]";
        if($tipocontrol==3){
            
        $columnaControl = Selector::crear('TH',array('style'=>'display:none'),$valorC); 
        }else{
            $input = CampoHTML::crearBoton($valorC,array('type'=>$control[$tipocontrol],'name'=>$nombreControl));
            $columnaControl=Selector::crear('TH',array('style'=>'width:30px'),$input);
        }
        return $columnaControl;
            
    }
    /**
     * Agrega un control a la fila
     * 
     * Los controles pueden ser 1) Radio 2) Checkbox 3)Input oculto
     * 
     */
    private function addControlFila(){
        $control = array (1 => 'radio',2 => 'checkbox',3 => 'txt');
        
        switch ($this->tipoControl) {
            case 1:
            case 2:
                $control = ($this->tipoControl==1)?'radio':'checkbox';
                $nombreControl = ($this->tipoControl == 1) ? "seleccionar" : "seleccionar[]";
             #   Arrays::mostrarArray($this->tabla);Exit;
                for($i=0;$i<$this->tabla->getTotalFilas();$i++){
                    
                    $col =& $this->tabla->tr[$i]->td[0];
                    
                    $col->contenido=Selector::crearInput($col->contenido,
                                            array(  'name'=>$nombreControl,
                                                    'type'=>$control,
                                                    $col->contenido
                                                    )
                                            );
                }//fin for
                 
                break;
            case 3:
                
                $this->tabla->setColumna(0, 'style', 'display:none');
                break;
            
        }
    }
    
    
    
    //---------------------------------------------------
    /**
     * Arma la vista a partir de una solicitud ajax.
     * 
     * La funcion debe ser llamada por medio de solicitudes ajax y el parametro post "jvista"
     * @method procesarAccion
     */
    protected function procesarAccion($post){
        $vistaArmada="";
    
        if($post){
            
            if(isset($post['jvista'])){
                
                switch($post['jvista']){
                    case 'paginador':
                        
                        $vistaArmada = $this->crearVista();
                            
                        break;
                    case 'orden':
                        $this->agregarOrderConsulta($post['numeroCampo'],$post['order']);
                        #echo $this->query;exit;
                        $vistaArmada=$this->crearVista();
                        break;
                    case 'busqueda':
                        $vistaArmada = $this->buscadorVista($post[$this->nombreBotonBusqueda]);
                }
                respuestaAjax($vistaArmada);
            }elseif(isset($post[$this->nombreBotonBusqueda])){
                    $vistaArmada = $this->buscadorVista($post[$this->nombreInputTextBusqueda]);
                    return $vistaArmada;
            }else{
                $vistaArmada = $this->crearVista();
                
            }//fin if
        }else{
            
            throw new Exception("Debe existir un post para validar la accion de la vista", 1);
            
        }
    }//fin funcion procesarAccion
    /**
     * Ajusta query de la vista para filtrar por la busqueda solicitada
     * @method buscadorVista;
     */
    private function buscadorVista($busqueda){
        
        $band = 0;
        if(!array_key_exists('where',$this->sentenciasQuery)){
            
            $this->sentenciasQuery['where']="where ";
        }else{
            $band=1;
            $this->sentenciasQuery['where']="(".$this->sentenciasQuery['where'].") and (";
        }
        if(is_array($this->camposBusqueda)){
            if(count($this->camposBusqueda)>0){
                $i=0;
                foreach ($this->camposBusqueda as $key => $campo) {
                    if($i>0)
                        $this->sentenciasQuery['where'].=" or ";
                    $this->sentenciasQuery['where'].=" $campo like '%$busqueda%' ";
                    $i++;
                }
            }else{
                throw new Exception("No se han definido campos de busqueda para la vista $this->nombreVista", 1);
                
            }
            if($band==1){
                $this->sentenciasQuery['where'].=")";
            }
        }else{
            throw new Exception("El atributo camposBusqueda no está definido como arreglo", 1);
            
        }
        $this->query = $this->query;
        return $this->crearVista();
    }
    /**
     * Agrega sección de busqueda a la vista
     * @method agregarSeccionBusqueda
     */
    private function agregarSeccionBusqueda(){
        $seccionBusqueda="";
        if($this->seccionBusqueda===TRUE){
              $seccionBusqueda.="
                
                 <section id=\"seccionBusqueda".$this->nombreVistaSinEspacios."\" class=\"$this->cssSectionBusqueda\">
                    <article class=\"$this->cssColBusqueda\">
                        <div class=\"$this->cssDivBusqueda\">
                          <form name=\"busq".$this->nombreFormVista ."\" id=\"busq".$this->nombreFormVista ."\" method=\"POST\" class=\"$this->cssFormBusqueda\"  role=\"search\">
                              <div class=\"form-group\">
                                  <input type=\"text\" name=\"$this->nombreInputTextBusqueda\" id=\"$this->nombreInputTextBusqueda\" class=\"$this->cssInputTextBusqueda\">
                                  <button 
                                    type=\"$this->typeBotonBusqueda\" 
                                    name=\"$this->nombreBotonBusqueda\" id=\"$this->nombreBotonBusqueda\" value=\"$this->valueBotonBusqueda\" 
                                    class=\"$this->cssBotonBusqueda\" data-jvista=\"busqueda\">
                                    $this->htmlButtonBusqueda
                                   </button>
                              </div>
                          </form>
                        </div>
                    </article>
                 </section>
                
              ";
                
        }
        return $seccionBusqueda;
        
    }//fin seccionBusqueda
    /**
     * Agrega un orden a la consulta de la vista
     * @method agregarOrderConsulta
     * @param int $numeroCampo Indice del campo por el cual  realizar el order
     * @param int $orden 1 asc 2 desc
     */
    private function agregarOrderConsulta($numeroCampo,$orden){
        $orderBy =($orden==1)?"asc":"desc";
        $this->orderBy=($orden==1)?2:1;
        $this->sentenciasQuery['order'] = " $numeroCampo $orderBy ";
        

    }
    
    /**
     * Agrega estilo a una columna especificada
     */
    private function setEstiloColumna(){
        
    }
    /**
     * Retorna el atributo ID de la etiqueta FOrm del formulario
     */
    function getIdFormVista(){
        return $this->idFormVista;
    }
    /**
     * Modifica el funcionamiento del paginador
     * 
     * Permite modificar la forma en que será presentado el paginador,
     * los estilos css para la lista o spam, para el enlace actual y los otros enlaces
     * 
     * Recibe un arreglo con los parametros a modificar, si algun valor del arreglo no es correcto, será ignorado.
     * 
     * Opciones posibles de modificación:
     * <ul>
     *  <li>tipoPaginador</li>
     *  <li>cssListaPaginador</li>
     *  <li>cssPaginaActual</li>
     *  <li>cssLinkPaginas</li>
     *  <li>Ajax</li>
     * </ul>
     * @param array Parametros Arreglo asociativo con los valores que se desean modificar
     */
    function setPaginador($valores){
        $this->paginador->establecerAtributos($valores);
        
    }
    /**
     * Ajusta los atributos personalizables de una vista
     * 
     * Permite modificar los 
     */
    function setParametrosVista($arr){
         $metodos=get_class_vars(__CLASS__);
        foreach($metodos as $k => $valor){
            
            if(isset($arr[$k])){
                $this->$k=$arr[$k];
            }

        }//final foreach
    }
    /**
     * Agrega un Breadcrumb a la vista a partir de un arreglo
     * 
     * Utiliza estilo css del bootstrap-breadcrumb por defecto, puede ser modificado a partir del
     * arreglo pasado a la funcion setParametrosVista, pasando un key "cssBreadcrumb".
     * 
     * @param array opcionesBreadcumb
     * @return string breadcumb
     * 
     * 
     */
    private function agregarBreadCrumb(){

            if(is_array($this->opcionesBreadCrumb)){
                if(array_key_exists("selector",$this->opcionesBreadCrumb)){
                    $selector = $this->opcionesBreadCrumb['selector'];
                    unset($this->opcionesBreadCrumb['selector']);
                }
                $breadcrumb="\n<section class=\"row\">";
                $breadcrumb.="\n\t\t<div class=\"col-lg-12\">";
                $breadcrumb.="\n\t\t\t<ol class=\"$this->cssBreadcrumb\">";
                    
                foreach($this->opcionesBreadCrumb as $campo => $valor){
                    
              
                    $breadcrumb.="\n\t\t\t<li>";
                    if(isset($selector))
                        $breadcrumb.="\n\t\t\t\t<a href=\"$valor[enlace]\">";
                        $breadcrumb.="\n\t\t\t\t\t<span data-id=\"$valor[enlace]\">$valor[nombreLink]</span>";
                    if(isset($selector))
                        $breadcrumb.="\n\t\t\t\t</a>";
                        $breadcrumb.="\n\t\t\t</li>\n";     
                }
                $breadcrumb.="\n\t\t\t</ol>\n\t\t</div>\n</section>";
                return $breadcrumb;
            }//fin if
    }//final funcion
    
}//final clase
?>