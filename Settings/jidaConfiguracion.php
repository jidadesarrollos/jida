<?PHP 
/**
 * Archivo con configuracion por defecto para que la aplicación arranque, todos los valores
 * que se encuentran en este archivo pueden ser sobreescritos o reemplazados
 * 
 * Se recomienda que el reemplazo se realice en un archivo externo a la carpeta framework
 * @see Aplicacion\Config\AppConfig
 * @author Julio Rodriguez 
 * @version 1.0
 * @package Framework
 * @category Setting
 */
if(!array_key_exists('_CSS', $GLOBALS))
/**
 * Arreglo globla para manejo de css en las vistas
 */
  
$GLOBALS['_CSS']=[];
if(!array_key_exists('_JS', $GLOBALS))
/**
 * Arreglo global para manejo de JS en las vistas
 */
$GLOBALS['_JS']=[];

if(!array_key_exists('configPaginador', $GLOBALS)){
	/**
	 * Arreglo de configuracion para paginadores
	 * @global configPaginador
	 */
	$GLOBALS['configPaginador'] = [
	    'cssLinkPaginas'        =>  "",
	    'cssPaginaActual'       =>  "active",
	    'cssListaPaginador'     =>  "pagination",
	    'tipoPaginador'         =>  "lista",
	    'filasPorPagina'        =>  10
	];	
}

if(!array_key_exists('PaginadorJida', $GLOBALS)){
	/**
	 * Arreglo de configuracion para paginadores
	 * @global array PaginadorJida
	 
	 */
	$GLOBALS['PaginadorJida'] =[
	    'cssLinkPaginas'        =>  "",
	    'cssPaginaActual'       =>  "active",
	    'cssListaPaginador'     =>  "pagination",
	    'tipoPaginador'         =>  "lista",
	    'filasPorPagina'        =>  20
	];
}
if(!array_key_exists('configVista', $GLOBALS)){
	/**
	 * Arreglo de configuracion para las Vistas creadas
	 * con la clase Vista
	 * @see Vista
	 * @global array configVista
	 */
	$GLOBALS['configVista']=[
	    'cssSectionBusqueda'	=>	'row form-inline',
	    'cssFormBusqueda'		=>	'navbar-form navbar-right',
	    'cssDivBusqueda'		=>	'col-md-12 txt-derecha',
	    #'cssDivInputTextBusqueda'=>'col-xs-4',
	    'cssInputTextBusqueda'	=>	'form-control input-busqueda',
	    'cssDivInputBtnBusqueda'=>	'',
	    'cssBotonBusqueda'		=>	'btn btn-adm',
	    'cssBtnAccion' 			=> 	'btn btn-primary',
	    'cssTable'				=>	'table animate2 fadeInRight',
	    'cssTituloVista'		=>	'animate1 rotateInDownLeft',
	    'cssFilaAcciones'		=>	'text-right',
	    'paginador'				=>	$GLOBALS['configPaginador'],
	    ];
}