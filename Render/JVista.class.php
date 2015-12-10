<?php
/**
 * Clase para manejo de Vistas Dinamicas
 * 
 */
 class JVista extends DataModel{
 	
	
	private $dataVista;
	private $acciones=[];
	
	
	private $nroFilas=15;
	private $paginasMostradas=9;
	private $totalPaginas;
	/**
	 * @var object $tabla Objeto TablaSelector
	 */
	var $tabla;
	private $campos = [];
	private $titulos=[];
		
	private $attTabla = [
		'class' => 'table'
	
	];
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
	function __construct($ejecucion,$params=[]){
		$dataConsulta = explode(".", $ejecucion);
		if(!empty(Session::get('urlActual')))
			$this->paginaConsulta = (Session::get('urlActual')[0]=="/")?Session::get('urlActual'):"/".Session::get('urlActual');
		if(count($params)>0){
			if(array_key_exists('campos', $params)){
				$this->campos = $params['campos'];
			}
			if(array_key_exists('titulos', $params)){
				$this->titulos = $params['titulos'];
			}
		}
		
		if(count($dataConsulta)>1){
			$object = new $$dataConsulta[0]();
			if(method_exists($object, $dataConsulta[1])){
				$this->dataVista = $object->$$dataConsulta[1];	
			}else
				throw new Exception("No existe el metodo pasado", 1);
				
			
		}else{
			
			if(class_exists($dataConsulta[0])){
				$this->objeto = new $dataConsulta[0];
				
				$this->obtInformacionObjeto();
			}else{
				throw new Exception("No existe el objeto pasado", 2);
				
			}
				
		}
		
		$this->paginador = new ListaSelector();
	}
	/**
	 * Obtiene la información a renderizar desde un objeto dado
	 * 
	 * Esta funcion se implementa al no ser pasado un metodo especifico, y trata
	 * de obtener los registros haciendo uso de la funcionalidad del DataModel
	 * @method obtInformacionObjeto
	 * 
	 */
	private function obtInformacionObjeto(){
		$offset=($this->paginaActual<=1)?0:(($this->paginaActual-1)*$this->nroFilas);
		$this->totalRegistros = $this->objeto->totalRegistros()['total'];
		
		if(count($this->campos)<1){
			$this->registros = $this->objeto->consulta()->limit($this->nroFilas,$offset)->obt();	
		}else{
			$this->registros=$this->objeto->consulta($this->campos)
									->limit($this->nroFilas,$offset)
									->obt();
		}
		
		$this->tabla = new TablaSelector($this->registros);	
	}
	/**
	 * Obtiene la información a renderizar desde el metodo de un objeto dado
	 * 
	 * @method obtInformacionMetodo
	 */
	private function obtInformacionMetodo(){
		
	}
	
	function obtenerVista(){
		$this->tabla->attr($this->attTabla);
		if(count($this->titulos)>0) $this->tabla->tHead($this->titulos);
		$vista = $this->tabla->generar();
		$vista.= $this->crearPaginador();
		
		return $vista;
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
					->data(['paginador'=>$i,'page'=>$this->paginaConsulta]);
			}else{
				$item->attr([
					'class'	=>$this->configPaginador['classLink']])
					->contenido->attr(['href'	=>"$this->paginaConsulta?pagina/$i/",])
					->data(['paginador'=>$i,'page'=>$this->paginaConsulta]);
			}
			
		}
		return $this->paginador->renderizar(); 
		//----------------------------------------------------------
	}
	
	
	
		
	
 }//fin clase