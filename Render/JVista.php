<?php
/**
 * Clase para manejo de Vistas Dinamicas
 *
 */

namespace Jida\Render;
use Jida\Helpers as Helpers;
class JVista{

	//Contenido Vista============================================
	private $dataVista;
	var $ordenamientos=TRUE;
	var $buscador = FALSE;

	private $nroFilas=10;
	private $paginasMostradas=9;
	private $totalPaginas;
	private $titulos=[];
	private $titulosKey=[];
	private $contenedorAcciones;
	private $contenedorPaginador;
	private $accionesFila=FALSE;
	private $listadoFiltros;
	/**
	 * Permite definir los campos de ordenamiento para cada titulo
	 */
	var $camposOrder=[];
	/**
	 * Define la configuracion para la fila de opciones de la vista
	 * @var $configFilaOpciones
	 */
	private $configFilaOpciones=[
		"html"	=>'Opciones',
		"attr"	=>[
			'class' =>'fila-opciones'
		]

	];
	private $ejecucion;
	private $_ordenamientos =['asc','desc'];

	/**
	 * Tipo de ordenamiento, asc o desc
	 * @property $_tipoOrdenamiento
	 * @default asc
	 */
	private $_tipoOrdenamiento='asc';
	/**
	 * Nombre del campo con el cual se solicita ordenar la consulta
	 *
	 */
	private $_campoOrdenar;
	private $titulo;
	/**
	 * Funcion pasada por el usuario a ejecutar sobre la data obtenida
	 * @var function $funcionData
	 */
	private $funcionData;
    /**
     * @var array $clausulas Arreglo de clausulas agregadas a la consulta a base de datos implementada por el objeto
     */
    private $clausulas;
	/**
	 * @var mixed acciones Permite definir acciones para toda la vista.
	 */
	private $acciones=FALSE;
	#==============================================================
	# Opciones de funcionabilidad
	#==============================================================
	/**
	 * Define si las filas llevaran algún control
	 * @var mixed $controlFila 1. Radio, 2. checkbox, 3. campo oculto
	 */
	var $controlFila=3;
	var $funcionNoRegistros;
	private $parametroPagina = "pagina";
	private $usaBD = true;
	/**
	 * Parametros pasados como querystring y que son manipulados por el objeto
	 * @var array $querySTring
	 */
	private $queryString=[];
	/**
	 * Define si debe analizarse la URL
	 *
	 * Si esta en true se tratara la url de conformidad con la estructura de urls usada por JidaFramework
	 * @var boolean $analizaURL default true
	 * @link /estructura-urls/
	 */
	var $analizaURL = TRUE;

	/**
	 * @var string mensajeNoRegistros Mensaje a mostrar si no se consigues registros
	 */
	private $mensajeNoRegistros="No se han conseguido Registros";
	private $htmlPersonalizado=FALSE;
	/**
	 * @var array $filtros Permite definir los objetos de filtro
	 */
	private $filtros=[];

	#==============================================================
	# Configuracion de la clase
	#
	# Configuración de renderización de la clase
	#==============================================================
	/**
	 * @var object $tabla Objeto TablaSelector
	 */
	private $tabla;
	private $campos = [];
	private $nameInputLinea='seleccionar';
	private $configTabla = [
		'class' => 'table'
	];

	private $configAcciones=[
		'class' 		=> 'btn btn-vista',
		'data-accion'	=> 'true',



	];
	private $configAccionesFila=[
		'class'	=>'btn',
		'data-placement'=>'top',
		'data-toggle'=>'tooltip',


	];

	private $configContenedorAcciones=[
		'class'=>'col-md-offset-6 col-md-6 col-xs-12 text-right'
	];
	private $configTitulo=[
		'section'=>['class'=>'col-md-12'],
		'titulo' =>[
			'class'	=>'vista-titulo',
			'selector'=>'h1'
		],
	];

	private $configFiltros=[
		'section'=>[
			'class'=>'col-md-6 hidden-xs'
		],
		'listaFiltros'=>[
			'class'=> 'list-filtros',
		],
		'listaItemsFiltro'=>[
			'class'=>'nav nav-pills'
		]
	];

	private $noRegistros="";
	private $configArticleVista=['data-jida'=>'vista','class'=>'jvista'];
	private $configSeccionForm=[
		'col'=>[
			'class'=>'col-md-6 col-md-offset-6 np-r col-xs-12 seccion-busqueda',
		]
	];
	private $configSeccionFiltros=[];



	#=====================================================================
	/**
	 * @var array $registros Data obtenida de la consulta a base de datos
	 *
	 */
	private $registros;

	/**
	 * @var int $totalRegistros Numero total de registros obtenidos
	 *
	 */
	private $totalRegistros;

	//PAGINADOR=====================================================
	/**
	 * @var object $paginador  objeto ListaSelector como paginador
	 */
	private $paginador;
	private $paginaActual=1;
	private $configPaginador=[
		'tpl'=>'<div class="col-md-12 col-sm-12 col-xs-12">{{:paginador}}</div>',
		'classLink'				=>"link-paginador",
		'classPaginaActual'		=>"active",
		'classListaPaginador'	=>"pagination",
		'classContenedor'		=>'content-paginador'
	];
	/**
	 * @var int $paginaConsulta PAgina donde consulta el paginador para traer nuevos registros
	 */
	private $paginaConsulta;
	/**
	 * var object $objeto Objeto implementado
	 */
	private $objeto;
	private $urlActual;
	private $idVista;
	private $data;
	/**
	 * Contructor de Jvista
	 *
	 * @param string $ejecucion Objeto y metodo sobre el cual se obtendrá la información a usar en la vista El valor retornado por el objeto debe ser el objeto
	 *
	 * @param array $params Arreglo de parametros de configuracion, por ejemplo titulos
	 * @param string $titulo Titulo de la vista
	 *
	 */
	function __construct($ejecucion,$params=[],$titulo=""){

		if(is_array($ejecucion)){
			$this->usaBD = FALSE;
			$this->tabla = new TablaSelector($ejecucion);
			$this->data = $ejecucion;
		}else{
			$this->ejecucion = $ejecucion;
			$dataConsulta = explode(".", $ejecucion);
			$this->tabla = new TablaSelector();
		}


		if(!empty($titulo)) $this->titulo=$titulo;

		$this->paginador = new ListaSelector();

		$this->validarPaginaConsulta();

		if(count($params)>0){
			if(array_key_exists('campos', $params)){
				$this->campos = $params['campos'];
			}
			if(array_key_exists('titulos', $params)){
				$this->titulos = $params['titulos'];
			}
		}

		if(array_key_exists($this->parametroPagina, $this->queryString))
			$this->paginaActual = $this->queryString[$this->parametroPagina];

		$this->establecerValoresDefault();
		$this->checkConfig();
		#$this->checkGlobals();


	}
	/**
	 * Verifica la estructura de la url manejada para la funcionalidad de la vista
	 *
	 * Para el funcionamiento de este metodo el objeto debe usarse dentro del Framework JIDA.
	 * @method validarPaginaConsulta
	 */
	private function validarPaginaConsulta(){

		if($this->analizaURL)
		{
			$urlActual = Helpers\Sesion::get('URL_ACTUAL_COMPLETA');

		}

		$this->paginaConsulta = JD('URL_COMPLETA');
		$query = JD('QueryString');
		$this->queryString = (empty($query))?[]:$query;

	}
	private function establecerValoresDefault(){
		if(empty($this->titulo)){
			$nombre = explode(".", $this->ejecucion)[0];
		}else{
			$nombre=$this->titulo;
		}

		$this->idVista = Helpers\Cadenas::lowerCamelCase($nombre);
		$this->configArticleVista['id'] = Helpers\Cadenas::lowerCamelCase('Vista '.$nombre);
		$this->configTabla['id'] = Helpers\Cadenas::lowerCamelCase('data '.$nombre);


	}
	private function realizarConsulta(){
		$dataConsulta = explode(".",$this->ejecucion);

		if(class_exists($dataConsulta[0])){

			$this->objeto = new $dataConsulta[0];
			if(count($dataConsulta)>1){

				if(method_exists($dataConsulta[0], $dataConsulta[1])){
					$this->obtInformacionObjeto($dataConsulta[1]);
				}else throw new \Exception("No existe el metodo pasado", 1);

			}else{
				$this->obtInformacionObjeto();
			}
		}else{
			throw new \Exception("No existe el objeto pasado: ".$dataConsulta[0], 2);
		}

	}
	/**
	 * Procesa la informacion para una vista a partir de un arreglo
	 * @method procesarArrayData
	 * @param array $data
	 *
	 */
	private function procesarArrayData($data){
		$this->totalRegistros = count($data);
		$this->registros = $data;
		$this->titulosKey = $this->titulos;

	}
	/**
	 * Obtiene la información a renderizar desde un objeto dado
	 *
	 * @internal Esta funcion se implementa al no ser pasado un metodo especifico, y trata
	 * de obtener los registros haciendo uso de la funcionalidad del DataModel
	 * @method obtInformacionObjeto
	 *
	 */
	private function obtInformacionObjeto($metodo=false){

		$offset=($this->paginaActual<=1)?0:(($this->paginaActual-1)*$this->nroFilas);

		if($metodo){
			$this->objeto->$metodo();
		}else{

			if(count($this->campos)<1){
				$this->campos = array_keys($this->objeto->obtenerPropiedades());
				$this->objeto->consulta();
			}else{
				$this->objeto->consulta($this->campos);
			}
		}

        if(count($this->clausulas)>0){
            foreach ($this->clausulas as $key => $arrParams) {
                foreach ($arrParams as $clausula => $param) {

                    if(is_array($param))
                        call_user_func_array([$this->objeto,$clausula], $param);
                    else
                        $this->objeto->{$clausula}($param);
                }
            }
        }

		if(isset($_GET['busqueda'])){

			$filtros = [];
			foreach ($this->buscador as $key => $filtro) {
				$filtros[$filtro]=$_GET['busqueda'];

			}
			$this->objeto->like($filtros,'or');

		}
		if(isset($_GET['ordenar'])){
			$campoOrden = (array_key_exists($_GET['ordenar'], $this->camposOrder))?$this->camposOrder[$_GET['ordenar']]:$_GET['ordenar'];
			$this->_campoOrdenar = $campoOrden;
			if(isset($_GET['tipo_orden']) and in_array($_GET['tipo_orden'],$this->_ordenamientos)){
				$this->_tipoOrdenamiento = $_GET['tipo_orden'];
			}

			$this->objeto->order($campoOrden,$this->_tipoOrdenamiento);
		}
		$keysFiltro = array_keys($this->filtros);
		foreach ($keysFiltro as $key => $value) {
			if(array_key_exists($value, $_GET)){

				$this->objeto->filtro([$value=>$_GET[$value]]);
			}
		}
		$this->totalRegistros = count($this->objeto->obt());
		$this->registros = $this->objeto->limit($this->nroFilas,$offset)->obt();
		/**
		 * Se llama a la funcion pasada por el usuario
		 */
		if(!empty($this->funcionData)){
			$funcionData=$this->funcionData;
			$this->registros=$funcionData($this->registros,$this);
		}
		$this->obtenerNombreCampos();

		$this->tabla->inicializarTabla($this->registros);
	}
	/**
	 * obtiene los nombres de los campos consultados a base de datos
	 * @method obtenerNombreCampos
	 */
	private function obtenerNombreCampos(){
		$i=0;
		while($i< $this->objeto->bd->totalField($this->objeto->bd->result)){
			$this->titulosKey[]= $this->objeto->bd->obtenerNombreCampo($this->objeto->bd->result, $i);
			$i++;
		}

	}
 	/**
     * ejecuta la consulta de la vista agregando el limite de registros
     * requeridos.
     * @method obtenerConsultaPaginada
     */
    private function obtConsultaPaginada(){
    	$offset=($this->paginaActual<=1)?0:(($this->paginaActual-1)*$this->filasPorPagina);
       $this->query=$this->bd->addLimit($this->filasPorPagina, $offset,$this->queryReal);


    }
	/**
	 * Retorna la vista renderizada
	 * @method obtenerVista
	 * @param function $function Funcion a ejecutar sobre la data obtenida de base de datos
	 *
	 */
	function obtenerVista($function=""){
		if(!empty($function)){

			$this->funcionData=$function;
		}

		if($this->usaBD){
			$this->realizarConsulta();
		}else{
			$this->procesarArrayData($this->data);
		}

		$seccionVista = new Selector('article',$this->configArticleVista);
		$vista="";
			$vista.= $this->checkTitulo();
			$vista.=$this->checkMensajes();
			$vista.=$this->renderFiltros();
			$vista .= $this->procesarFormBusqueda();


		if($this->totalRegistros){

			$this->tabla->attr(array_merge($this->configTabla,$this->tabla->attr));

			if(count($this->titulos)>0){
				 $this->crearTitulos();
			}


			$this->procesarAccionesFila();
			$this->procesarControlFila();
			$vista .= Selector::crear('div.col-md-12',[],$this->tabla->generar());
			if(count($this->acciones)>0){
				$vista.=$this->procesarAcciones();
			}
			$vista.= $this->crearPaginador();


			$seccionVista->innerHTML($vista);

		}else{

			$seccionVista->innerHTML($vista.$this->procesarNoRegistros());
		}
		return $seccionVista->render();

	}

	private function checkMensajes(){
		if(Helpers\Sesion::get('__msjVista')){

			$msj = Helpers\Sesion::get('__msjVista');
			if(is_array($msj) and array_key_exists('id', $msj) and $msj['id']==$this->idVista){
				Helpers\Sesion::destroy('__msjVista');
				return Selector::crear('div.col-md-12',null,$msj['msj']);

			}
		}
		return "";
	}

	/**
	 * Renderiza el titulo de la vista
	 * @method checkTitulo
	 */
	private function checkTitulo(){
		if(!empty($this->titulo)){
			$attrSeccion = (array_key_exists('section', $this->configTitulo))?$this->configTitulo['section']:[];
			$seccionTitulo = new Selector('seccion',$attrSeccion);
			$titulo = new Selector($this->configTitulo['titulo']['selector']);
			$titulo->attr('class',$this->configTitulo['titulo']['class']);
			$titulo->innerHTML($this->titulo);
			$seccionTitulo->innerHTML($titulo->render());
			return $seccionTitulo->render();


		}
	}

	private function procesarControlFila(){

		if($this->controlFila){

			$this->tabla->funcionColumna(0,function(Selector $selector,$control=1){


				$types =[1=>'radio','2'=>'checkbox',3=>"hidden"];

				if($this->tabla->tHead() instanceof Selector){
					$columnasTitulo = $this->tabla->tHead()->Fila->columnas();
					if($control!=3){
						$inputTitle = new Selector('input',
							[	"type"			=>$types[$control],
								'id'			=>'obtTotalCol',
								'data-jvista'	=>'seleccionarTodas',
								'name'			=>'seleccionar',
								'value'=>""
							]);
						$columnasTitulo[0]->innerHTML($inputTitle->render());
					}else{

						$input = new Selector('input',[
							"type"	=>$types[$control],
							'id'	=>'radio'.$selector->innerHTML(),
							'value'	=>$selector->innerHTML(),
							'name'	=>$this->nameInputLinea,
						]);
						$selector->attr('style','display:none');
						$selector->innerHTML($input->render());
					}



				}

			},$this->controlFila);
		}
	}
	private function procesarFormBusqueda(){
		if($this->totalRegistros<1) return "";
		if(is_array($this->buscador)){
			$div = new Selector('section');
			$valorBusqueda="";
			if(isset($_GET['busqueda'])){ $valorBusqueda = $_GET['busqueda'];
				$url = $this->urlFiltro(['busqueda'=>$_GET['busqueda']]);
			}else{
				$url = $this->urlFiltro();
			}
			$inner = '
			<form action="'.$url.'" method="get">
				<div class="'.$this->configSeccionForm['col']['class'].'">
					<div class="input-group">
						<input type="search" class="form-control jvista-search" name="busqueda" value="'.$valorBusqueda.'"/>
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit">Buscar!</button>
							</span>
					</div>
				</div>
			</form>';

			return $inner;
		}

	}
	/**
	 * Verifica si se agregaron acciones a una fila
	 * @method procesarAccionesFila
	 */
	private function procesarAccionesFila(){

		if(is_array($this->accionesFila) and count($this->accionesFila)>0){

			$this->tabla->insertarColumna(function($ele,$acciones,$fila){
				$contenido="";
				if(is_array($acciones))
				{
					$keys = array_keys($fila->columnas);

					#Debug::string($keys[0]);

					$colIni = $fila->columnas[$keys[0]];

					foreach ($acciones as $key => $accion) {
						$accionFila = clone $accion;

						$config = $this->configAccionesFila;

						foreach ($accionFila->attr as $clave => $valor){
							$accionFila->attr($config);
							$accionFila->attr($clave,
								str_replace('{clave}',
								$colIni->innerHTML(),
								$accionFila->attr($clave))
							);
						}

					/*
						$accionFila->attr($config);
						$accionFila->attr('href',
							str_replace('{clave}',
							$colIni->innerHTML(),
							$accionFila->attr('href'))
						);
					*/

						$contenido .= $accionFila->render();
						unset($accionFila);
					}

					return $contenido;
				}else{
					throw new \Exception("Las acciones pasadas a la fila no son validas", 1);

				}
			},$this->accionesFila);
			#Debug::string("a");
		}
	}
	/**
	 * Crea los titulos de la tabla
	 * @internal Verifica si la vista tiene habilitado ordenamientos, el tipo de control
	 * y hace el renderizado correspondiente
	 * @method crearTitulos
	 */
	private function crearTitulos(){

		$tieneOpciones = ($this->accionesFila)?TRUE:FALSE;
		if($tieneOpciones)
		{
			array_push($this->titulos,$this->configFilaOpciones['html']);
		}
		if($this->controlFila==3)
			array_unshift($this->titulos,"");
		$this->tabla->crearTHead($this->titulos);
		$columnasTitulos = $this->tabla->tHead()->Fila->columnas();
		$totalLinks = count($columnasTitulos);
		if($tieneOpciones){

			$this->tabla->tHead()->Fila
									->columna($totalLinks-1)
									->attr($this->configFilaOpciones['attr']);
		}
		if($this->controlFila==3){
			$this->tabla->tHead()->Fila->columna(0)->attr('style','display:none');
		}


		if($this->ordenamientos){


			if($tieneOpciones) $columnasTitulos--;

			if($tieneOpciones) $totalLinks--;
			$camposOrden = $this->obtParametrosOrden();
			for($i=0;$i<$totalLinks;$i++){
				if($i==0 and $this->controlFila==3) continue;

				$columnasTitulos[$i]->ejecutarFuncion(
				function(Selector $col,$indice,$titulos,$pagina,$jvista){

					$indiceMenu=$indice;
					if(array_key_exists($indiceMenu,$titulos ))
					{
						$ordenamiento  = $this->_tipoOrdenamiento;
						if($titulos[$indiceMenu]==$jvista->_campoOrdenar){
							$ordenamiento =($this->_tipoOrdenamiento=='asc')?'desc':'asc';
						}
						$params = ['href'=>$this->procesarURL([
									'ordenar'		=>$titulos[$indiceMenu],
									'tipo_orden' 	=> $ordenamiento,
									'pagina'		=>$this->paginaActual
								],0)];
						$col->envolver('a',$params);
					}

				},$i,$camposOrden,$this->paginaConsulta,$this);
			}
		}//fin ordenamientos


#exit;
	}
	private function obtParametrosOrden(){
		$params = [];

		if(!$this->camposOrder) return $this->titulosKey;
		foreach ($this->titulosKey as $key => $value) {
			if(array_key_exists($value, $this->camposOrder))
			{
				$params[$key] = $this->camposOrder[$value];
			}else{
				$params[$key] = $value;
			}
		}
		#Helpers\Debug::imprimir($params,$this->titulosKey,$this->camposOrder,true);
		return $params;
	}
	function procesarAcciones(){
		$inner="";
		if(is_array($this->acciones)){
			foreach ($this->acciones as $key => $selector) {
				$inner .= $selector->render();
			}
			$this->contenedorAcciones = new Selector('div',['class'=>'contenedor-acciones']);
			$this->contenedorAcciones->attr($this->configContenedorAcciones);
			return $this->contenedorAcciones->innerHTML($inner)->render();
		}
		return $inner;


	}



	/**
	 * Genera el páginador de la vista
	 * @method crearPaginador
	 */
	private function crearPaginador(){
		$division = $this->totalRegistros/$this->nroFilas;
		$this->totalPaginas = is_float($this->totalRegistros)?ceil($division):ceil($this->totalRegistros/$this->nroFilas);
			$medio = ceil($this->paginasMostradas/2);

		$ultimaPaginaMostrada=(($this->paginaActual+$medio)< $this->totalPaginas)?$this->paginaActual+$medio:$this->totalPaginas;
		$primeraPaginaMostrada=($this->paginaActual>$medio)?$this->paginaActual-$medio:1;

		//----------------------------------------------------------
		for($i=$primeraPaginaMostrada;$i<=$ultimaPaginaMostrada;++$i){

			$link = new Selector('a');
			$this->paginador->attr('class',$this->configPaginador['classListaPaginador']);
			$item = $this->paginador->addItem($i)->envolver('a');

			if($i == $this->paginaActual){
				$item->attr([
					'class'	=>$this ->configPaginador['classPaginaActual']])
									->contenido
									->attr(['href'	=>$this->procesarURL(['pagina'=>$i])]);
			}else{
				$item->attr([
					'class'	=>$this ->configPaginador['classLink']])
									->contenido
									->attr(['href'	=>$this->procesarURL(['pagina'=>$i])]);
					#->data(['paginador'=>$i,'page'=>$this->paginaConsulta])
					;
			}

		}

		return $this->_obtTemplate($this->configPaginador['tpl'], ['paginador'=>$this->paginador->render()]);
		//----------------------------------------------------------
	}

	function accionesFila($acciones){
		if(is_array($acciones)){
			foreach ($acciones as $key => $accion) {
				$orden = $key;
				if(array_key_exists('orden', $accion)){
					$orden = $accion['orden'];unset($accion['orden']);
				}

				$attrAccion = array_merge($this->configAccionesFila,$accion,['span'=>["class"=>$accion['span']]]);

				$nuevaAccion = new AccionVistaSelector("",$attrAccion);
				$this->accionesFila[$orden]=$nuevaAccion;
			}
			return $this;
		}
	}

	/**
	 * Define acciones generales para la vista
	 *
	 * @method acciones
	 * @param $acciones
	 */
	function acciones($acciones=False){
		if(is_array($acciones)){

			foreach ($acciones as $key => $accion) {
				#Debug::mostrarArray($this->configAcciones,false);
				#Debug::mostrarArray($accion,0);
				$attrAccion = array_merge($this->configAcciones,$accion);
				#Debug::mostrarArray($attrAccion);
				$nuevaAccion = new AccionVistaSelector($key,$attrAccion);
				$this->acciones[$nuevaAccion->nombreAccion()]=$nuevaAccion;
			}

		}
		return $this;
	}
	//Geters===========================================
	/**
	 * Retorna el objeto Table Selector
	 * @internal Retorna el objeto Table que hereda de selector, para poder configurarle
	 * valores para el renderizado
	 * @method tabla;
	 * @return object Table;
	 * @see Table
	 * @link Selector
	 */
	function tabla(){
		return $this->tabla;
	}

	/**
	 * Verifica si existen arreglos GLobales de configuración para el estilo
	 *
	 * @method checkGlobals
	 */
	private function checkGlobals(){
		if(array_key_exists('configPaginador', $GLOBALS)){
			$this->configPaginador = $GLOBALS['configPaginador'];
		}
		if(array_key_exists('configVista', $GLOBALS)){
			$this->configTabla = $GLOBALS['configVista'];
		}
		if(array_key_exists('configPaginador', $GLOBALS)){

		}
	}
	/**
	 * Permite agregar filtros a la vista
	 *
	 *
	 * @method addFiltros
	 * @param array $filtros Arreglo de Filtros a agregar, el key será el titulo a mostrar
	 * y el value puede ser una matriz con valores de personalización.
	 * @example
	 *  $jvista->addFiltros([
	 * 		'Un titulo'=>[
	 * 			'valor1'=>'label 1',
	 * 			'valor2'=>'label 2',
	 * 		]
	 * 	]);
	 *
	 */
	function addFiltros($filtros){

		if(is_array($filtros)){
			$this->filtros = $filtros;

			$seccionFiltro = new Selector('section',$this->configFiltros['section']);

			$listaFiltros = new ListaSelector(count($this->filtros),$this->configFiltros['listaFiltros']);
			// $listaFiltros = new Selector('Article',['class'=>'jvista-filtros']);
			$htmlFiltros = "";
			foreach ($filtros as $campoFiltro => $item) {
				//Titulo filtro-------------------------------------------
				$tituloFiltro = new Selector('h4');
				$titulo = $campoFiltro;
				if(array_key_exists('titulo',$item)){
					$titulo = $item['titulo'];
					unset($item['titulo']);
				}

				$tituloFiltro->innerHTML($titulo);
				//Titulo filtro
				$listaItems = new ListaSelector(count($item));
				$listaItems->attr($this->configFiltros['listaItemsFiltro']);
				//Recorrido de items=====================
				foreach($item as $idFiltro => $itemFiltro){

					$link = new Selector('a',['href'=>$this->urlFiltro([$campoFiltro=>$idFiltro])]);

					$link->innerHTML($itemFiltro);
					$item = $listaItems->addItem($link->render());
					if(array_key_exists($campoFiltro, $_GET) and $_GET[$campoFiltro]==$idFiltro){

						$item->addClass('active');
					}
				}
			 	// $htmlFiltros.=$tituloFiltro->render().$listaItems->render();
				$listaFiltros->addItem($tituloFiltro->render().$listaItems->render());

			}//fin foreach
			$this->listaFiltros = $seccionFiltro->innerHTML($listaFiltros->render());

		}
		// Debug::string($this->listaFiltros->innerHTML(),1);

	}

	function renderFiltros(){

		if(count($this->filtros)>0){
			return Selector::crear('div.row',[],Selector::crear('div.col-md-12',[],$this->listaFiltros->render()));
		}
	}
	/**
	 * Retorna la url para un filtro de la vista
	 * @method urlFiltro
	 */
	private function urlFiltro($params=[]){
		if(is_array($params)){
			$querystring="";
			$i=0;
			foreach ($params as $key => $value) {
				if($i>0)$querystring.="&";
				if(!strpos($this->paginaConsulta, '?'))
						$querystring.="?";
					$querystring.=$key."=".$value;
				++$i;
			}
			// Debug::string($this->paginaConsulta,1);
			return $this->paginaConsulta.$querystring;
		}else{
			throw new \Exception("No se han pasado bien los parametros para la url", 1);

		}
	}

	function procesarNoRegistros(){
		if(!empty($this->funcionNoRegistros)){
			return call_user_func_array($this->funcionNoRegistros, [$this]);
		}else{
			if($this->htmlPersonalizado) return Selector::crear('div.col-md-12',null,$this->mensajeNoRegistros);
			return Selector::crear('div.col-md-12',null,Helpers\Mensajes::crear('alert',$this->mensajeNoRegistros));
		}
	}

	function obtTotalRegistros(){
		return $this->totalRegistros;
	}

	function obtConsulta(){
		$this->objeto->imprimir();
	}
	/**
	 * Crea mensajes a mostrar en la vista
	 *
	 * @param string $msj Mensaje a mostrar
	 * @param string $idVista Identificador
	 * @param string $tipoMensaje Debe corresponder a una clase de mensajes configurara
	 * @param jidaUrl $redireccion Url a la cual redireccionar
	 */
	static function msj($idVista,$tipo,$msj,$redireccion=""){
		Helpers\Sesion::set('__msjVista',['msj'=>Helpers\Mensajes::crear($tipo, $msj),'id'=>$idVista]);
		if(!empty($redireccion)) redireccionar($redireccion);

	}
	/**
	 * Permite personalizar un mensaje en caso de no haber registros
	 * @method addMensajeNoRegistro
	 * @param string $msj Mensaje a mostrar. Puede ser un string o una cadena HTML
	 * @param array $data Data adicional para el mensaje.
	 * @see self::mensajeRegistros
	 * Las opciones a pasar son : cssContendor,link,cssLink,txtLink
	 */
	function addMensajeNoRegistros($msj,$cssDiv=[]){

		$dataDefault=[
			'link'=>false,
			'cssContenedor'=>'alert alert-warning',
			'attrLink'=>[],
			'cssLink'=>'btn btn-default pull-right',
			'txtLink'=>'Agregar'

		];
		$msj= Selector::crear('div.'.$dataDefault['cssContenedor'],[],$msj);
		$dataDefault=array_merge($dataDefault,$cssDiv);
		//Helpers\Debug::imprimir($dataDefault,true);
		//foreach ($cssDiv as $key => $value){

			if($dataDefault['link']){
				$this->htmlPersonalizado=TRUE;
				$msj.=Selector::crear('a.'.$dataDefault['cssLink'],
				array_merge(['href'=>$dataDefault['link']],$dataDefault['attrLink']),
				$dataDefault['txtLink']);
			}
		//}

		$this->mensajeNoRegistros=$msj;
		#Helpers\Debug::imprimir($msj,true);
	}
    /**
     * Permite agregar clausulas a la consulta realizada por la vista
     * @method clausula
     * @param string $nombreClausula Nombre de la clausula a ejecutar
     * @param mixed $valores Tantos valores como requiera $nombreClausula
     */
    function clausula($nombreClausula,$valores){
        $argumentos = func_num_args();
        if($argumentos==2)
            $this->clausulas[$nombreClausula]=$valores;
        elseif($argumentos>2){
            $argumentos=func_get_args();
            $params=[];
            foreach ($argumentos as $key => $value) {
            	if($key!=0)
					$params[$nombreClausula][]=$value;
            }

            $this->clausulas[$nombreClausula]=$params;
        }

    }


	function funcionFila($numeroFila,$function){

	}
	/**
	 * Gestiona la url de los enlaces a usar en la
	 * @since 1.4
	 * @method procesarURL
	 * @param  array $params Parametros a agregar en el querystring del link
	 * @param  boolean $print Solo para uso de Debug, realiza impresion de valores y corta la ejecucion de codigo.
	 *
	 */
	private function procesarURL($params,$print=false){
		// if($this->paginaConsulta[strlen($this->paginaConsulta)-1]!='/')
			// $this->paginaConsulta.="/";
		if($print){
		Debug::string("==============================");
		Debug::string($this->paginaConsulta);
		Debug::mostrarArray($this->queryString,0);
		Debug::mostrarArray($params,0);
		}
		//Se setea en una variable diferente para que no interfiera con los ajustes de otras variables.
		$querystring =array_merge($this->queryString,$params);
		if($print){
			Debug::mostrarArray($querystring,0);
			Debug::string($this->paginaConsulta . '?' . http_build_query($querystring),1);

			exit;
		}

		return $this->paginaConsulta .'?'. http_build_query($querystring);
	}

	private function checkConfig($config=[]){
		if(empty($config)){
			if(array_key_exists('configJVista', $GLOBALS))
				$config = $GLOBALS['configJVista'];
		}
		if($config)
		{
			foreach ($config as $parametro => $configuracion) {
				if(property_exists($this, $parametro))
					$this->{$parametro} = $configuracion;

			}
		}
	}
	/**
	 * Permite definir valores de configuracion para la vista creada
	 * @method configuracion
	 * @since 1.4
	 * @param mixed $configuracion valor o arreglo de valores
	 * @param mixed $valor [opcional] Si $configuracion es un string $valor sera usado como asignacion
	 * para la variable de configuracion
	 */
	function configuracion($configuracion,$valor=""){
		if(is_array($configuracion)){
			foreach ($configuracion as $key => $value) {
				if(property_exists($this, $key)) $this->{$key} = $value;
			}
		}else{
			if(property_exists($this, $configuracion)) $this->{$configuracion} = $valor;
		}
	}
	/**
	 * Renderiza el contenido en plantillas predeterminadas
	 * @method _obtTemplate
	 * @param $plantilla;
	 */
	private function _obtTemplate($template,$params){
		foreach ($params as $key => $value) {
			$template = str_replace("{{:".$key."}}", $value,$template);
		}
		return $template;
	}
 }//fin clase
