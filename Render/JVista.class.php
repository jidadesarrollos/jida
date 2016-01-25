<?php
/**
 * Clase para manejo de Vistas Dinamicas
 * 
 */
 class JVista{
 	
	//Contenido Vista============================================
	private $dataVista;
	var $ordenamientos=TRUE;
	private $nroFilas=10;
	private $paginasMostradas=9;
	private $totalPaginas;
	private $titulos=[];
	private $titulosKey=[];
	private $contenedorAcciones;
	private $contenedorPaginador;	
	private $accionesFila=FALSE;
	private $listadoFiltros;		
	var $buscador = FALSE;
	private $ejecucion;
	private $titulo;
	/**
	 * @var mixed acciones Permite definir acciones para toda la vista.
	 */
	private $acciones=FALSE;
	#==============================================================
	# Opciones de funcio/nabilidad
	#==============================================================
	/**
	 * Define si las filas llevaran algún control
	 * @var mixed $controlFila 1. Radio, 2. checkbox, 3. campo oculto 
	 */
	var $controlFila=1;
	private $parametroPagina = "pagina";
	var $funcionNoRegistros;
	var $mensajeNoRegistros="No se han conseguido Registros";
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
		'section'=>[],
		'titulo' =>[
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
	private $configArticleVista=[];
	private $configSeccionForm=[];
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
	function __construct($ejecucion,$params=[],$titulo=""){
		$this->ejecucion = $ejecucion;	
		$dataConsulta = explode(".", $ejecucion);
		$this->tabla = new TablaSelector();
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
		if(isset($_REQUEST[$this->parametroPagina]) and is_numeric($_REQUEST[$this->parametroPagina])){
			$this->paginaActual = $_REQUEST[$this->parametroPagina];
		}else{
			
		}
		$this->establecerValoresDefault();
		#$this->checkGlobals();
		
		
	}
	/**
	 * Verifica la estructura de la url manejada para la funcionalidad de la vista
	 */
	private function validarPaginaConsulta(){
		if(!empty(Session::get('URL_ACTUAL')))
			$this->paginaConsulta = (Session::get('URL_ACTUAL')[0]=="/")?Session::get('URL_ACTUAL'):"/".Session::get('urlActual');
			
		if(isset($_GET['busqueda']) and !strpos($this->paginaConsulta,'busqueda')){
			$this->paginaConsulta.="/busqueda/".$_GET['busqueda']."/";
		}
	}
	private function establecerValoresDefault(){
		$nombre = explode(".", $this->ejecucion)[0];
		$this->idVista = String::lowerCamelCase($nombre);
		$this->configArticleVista['id'] = String::lowerCamelCase('Vista '.$nombre);
		$this->configTabla['id']=String::lowerCamelCase('data '.$nombre);
		
		
	}
	private function realizarConsulta(){
		$dataConsulta = explode(".",$this->ejecucion);
		
		if(class_exists($dataConsulta[0])){
			$this->objeto = new $dataConsulta[0];
			if(count($dataConsulta)>1){
				
				if(method_exists($dataConsulta[0], $dataConsulta[1])){
					$this->obtInformacionObjeto($dataConsulta[1]);
				}else throw new Exception("No existe el metodo pasado", 1);
			
			}else{
				$this->obtInformacionObjeto();
			}
		}else{
			throw new Exception("No existe el objeto pasado", 2);
		}
		
	}
	/**
	 * Obtiene la información a renderizar desde un objeto dado
	 * 
	 * Esta funcion se implementa al no ser pasado un metodo especifico, y trata
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
		if(isset($_GET['busqueda'])){
	
			$filtros = [];
			foreach ($this->buscador as $key => $filtro) {
				$filtros[$filtro]=$_GET['busqueda'];
				
			}
			$this->objeto->like($filtros,'or');
			
		}

		$keysFiltro = array_keys($this->filtros);
		foreach ($keysFiltro as $key => $value) {
			if(array_key_exists($value, $_GET)){
				
				$this->objeto->filtro([$value=>$_GET[$value]]);
			}
		}
		$this->totalRegistros = count($this->objeto->obt());
		$this->registros = $this->objeto->limit($this->nroFilas,$offset)->obt();
		
		
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
	function obtenerVista(){
		$this->realizarConsulta();
		$seccionVista = new Selector('article',$this->configArticleVista);
		$vista="";
			$vista.= $this->checkTitulo();
			$vista.=$this->checkMensajes();
			$vista.=$this->renderFiltros();
			$vista .= $this->procesarFormBusqueda();
			
			
		if($this->totalRegistros){
			$this->tabla->attr($this->configTabla);
			if(count($this->titulos)>0){
				 $this->crearTitulos();
			}
			
			
			$this->procesarAccionesFila();
			$this->procesarControlFila();
			$vista .= $this->tabla->generar();
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
		if(Session::get('__msjVista')){
			
			$msj = Session::get('__msjVista');
			if(is_array($msj) and array_key_exists('idVista', $msj) and $msj['idVista']==$this->idVista){
				Session::destroy('__msjVista');
				return $msj['msj'];
				
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
					if($control==2){
						
						
						$inputTitle = new Selector('input',
							[	"type"			=>$types[$control],
								'id'			=>'obtTotalCol',
								'data-jvista'	=>'seleccionarTodas',
								'value'=>""
							]);
						$columnasTitulo[0]->innerHTML($inputTitle->render());
					}elseif($control==1){
						
					}
					$input = new Selector('input',
					["type"=>$types[$control],'id'=>'radio'.$selector->innerHTML(),'value'=>$selector->innerHTML()]);
					$selector->innerHTML($input->render());
					
				}				
									
			},$this->controlFila);
		}	
	}
	private function procesarFormBusqueda(){
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
				<div class="col-md-6 col-md-6">
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
						$accionFila->attr($config);
						$accionFila->attr('href',
							str_replace('{clave}', 
							$colIni->innerHTML(), 
							$accionFila->attr('href'))
						);
						$contenido .= $accionFila->render();
						unset($accionFila);
					}
				
					return $contenido;
				}else{
					throw new Exception("Las acciones pasadas a la fila no son validas", 1);
					
				}
			},$this->accionesFila);
			#Debug::string("a");	
		}
	}
	private function crearTitulos(){
		$this->tabla->crearTHead($this->titulos);
		if($this->ordenamientos){
			$columnasTitulos = $this->tabla->tHead()->Fila->columnas();
	
			for($i=0;$i<count($columnasTitulos);++$i){
				if($this->controlFila and $i==0) continue;
				$columnasTitulos[$i]->ejecutarFuncion(function(Selector $col,$indice,$titulos,$pagina){
					
					$params = ['href'=>$pagina."/ordenar/".$titulos[$indice-1]];
					if(isset($_REQUEST[$this->parametroPagina])){
						$params['href'] =$params['href'] ."/pagina/".$_REQUEST[$this->parametroPagina]; 
					}
					$col->envolver('a',$params);
				},$i,$this->titulosKey,$this->paginaConsulta);
			}
			
		}
		
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
		$this->totalPaginas = is_float($this->totalRegistros)?ceil($division):$this->totalRegistros/$this->nroFilas;
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
					'class'	=>$this->configPaginador['classPaginaActual']])
					->contenido->attr(['href'	=>"$this->paginaConsulta/pagina/$i/"])
					//->data(['paginador'=>$i,'page'=>$this->paginaConsulta])
					;
			}else{
				$item->attr([
					'class'	=>$this->configPaginador['classLink']])
					->contenido->attr(['href'	=>"$this->paginaConsulta/pagina/$i/",])
					#->data(['paginador'=>$i,'page'=>$this->paginaConsulta])
					;
			}
			
		}
		return $this->paginador->render(); 
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
	 * 
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
	 * 
	 */
	function addFiltros($filtros){
		
		if(is_array($filtros)){
			$this->filtros = $filtros;
			
			$seccionFiltro = new Selector('section',$this->configFiltros['section']);
			
			$listaFiltros = new ListaSelector(count($this->filtros),$this->configFiltros['listaFiltros']);
			foreach ($filtros as $campoFiltro => $item) {
				$tituloFiltro = new Selector('h4');
				$tituloFiltro->innerHTML($item['titulo']);
				
				$listaItems = new ListaSelector(count($item['items']));
				$listaItems->attr($this->configFiltros['listaItemsFiltro']);
				
				foreach($item['items'] as $idFiltro => $itemFiltro){
					
					$link = new Selector('a',['href'=>$this->urlFiltro([$campoFiltro=>$idFiltro])]);
					
					$link->innerHTML($itemFiltro);
					$item = $listaItems->addItem($link->render());
					if(array_key_exists($campoFiltro, $_GET) and $_GET[$campoFiltro]==$idFiltro){
						
						$item->addClass('active');	
					}
				}
				 
				$listaFiltros->addItem($tituloFiltro->render().$listaItems->render());
				
			}//fin foreach
			$this->listaFiltros = $seccionFiltro->innerHTML($listaFiltros->render());
			
		}
		
	}
	
	function renderFiltros(){

		if(count($this->filtros)>0){
			return $this->listaFiltros->render();
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
			return $this->paginaConsulta.$querystring;
		}else{
			throw new Exception("No se han pasado bien los parametros para la url", 1);
			
		}
	}
	
	function procesarNoRegistros(){
		if(!empty($this->funcionNoRegistros)){
			return call_user_func_array($this->funcionNoRegistros, [$this]);
		}else{
			
			return Selector::crear('div.col-md-12',null,Mensajes::crear('alert',$this->mensajeNoRegistros));
		}
	}
	
	function obtTotalRegistros(){
		return $this->totalRegistros;
	}
	
	function obtConsulta(){
		$this->objeto->imprimir();
	}
	static function msj($msj,$idVista,$tipo,$redireccion=""){
		Session::set('__msjVista',['msj'=>Mensajes::crear($tipo, $msj),'id'=>$idVista]);
		if(!empty($redireccion)) redireccionar($redireccion);
		
	}
	
 }//fin clase