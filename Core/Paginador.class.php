<?PHP 
/**
 * Clase generadora de 
 *
 * @package framework
 * @subpackage jida
 * @author  Julio Rodríguez <jirc48@gmail.com>
 */
class Paginador extends DBContainer{

    /**
     * Define el total de paginas obtenidas por la busqueda
     * @var int $totalPaginas
     */
    private $totalPaginas;
    /**
     * Define el total de registros encontrados por la busqueda
     * @var int $totalRegistros
     */
    private $totalRegistros;
    /**
     * Indica la página actual de la busqueda, la ubicacíon del paginador.
     * @var int $paginaActual
     */
    private $paginaActual;
    /**
     * Indica la consulta de la busqueda realizada con los limites indicados para el paginador
     */
    public $query;
    /**
     * Consulta real a ejecutar en base de datos sin agregar limites.
     * @var string $queryReal
     */
    private $queryReal;
    /**
     * Indica el registro inicial de la página actual
     * @var int $inicio
     */
   # private $inicio=1;
    /**
     * Indica el registro final de la página actual
     */
    private $fin;
    /**
     * Arreglo get capturado
     */
    private $parametrosGet;
    /**
     * Indica el total de páginas mostradas en el paginador
     * 
     * Por defecto muestra 9 páginas
     * @var int $paginasMostradas
     */
    private $paginasMostradas=9;
    /**
     * Define el total de filas mostradas por página, por defecto son
     * 15 registros
     * 
     * @var int $filasPorPagina
     */
    private $filasPorPagina=5;
    /**
     * Nombre Estilo para links del paginador
     * @var $cssLinkPaginas
     */
    private $cssLinkPaginas="link-paginador";
    /**
     * Nombre de clase css para Link de pagina actual en el paginador
     * @var $cssPaginaActual;
     */
    private $cssPaginaActual="link-paginador-actual";
    /**
     * Nombre de clase css para la lista del paginador
     * @var $cssListaPaginador.
     */
    private $cssListaPaginador="lista-paginador";
	/**
	 * Indica que tipo de paginador se desea
	 * puede ser "lista" o vacio
	 */
    private $tipoPaginador="";
	private $ajax = TRUE;
	
	/**|
	 * Pagina donde consulta el paginador para traer los nuevos registros
	 */
	private $paginaConsulta;
	/**
	 * Nombre del div de la vista
	 * 
	 * En caso de que $ajax sea true, el paginador tomará este div para recargar la vista
	 * @var selectorVista
	 */
	private $selectorVista;
	/**
	 * Define si el paginador llevará botones de next y prev.
	 */
	private $usoBtnsNextPrev=TRUE;
	/**
	 * Define el contenido del botón de "registros siguientes"
	 * 
	 * El botón de registros siguientes aparece cuando hay más páginas
	 * que las que numeradas en el paginador en el momento
	 * @var $contenidoNextBtn
	 * @access private
	 */
	private $contenidoNextBtn="&raquo";
	/**
	 * Define el contenido del botón de "registros previos"
	 * 
	 * El botón de registros previos aparece cuando hay más páginas previas
	 * que las que numeradas en el paginador en el momento
	 * @var $contenidoNextBtn
	 * @access private
	 */
	private $contenidoPrevBtn="&laquo";
    
    function __construct($query,$arr=""){
        
        $this->queryReal=$query;
        parent::__construct();
        if(!empty($this->queryReal)){
        	
            if(is_array($arr)){
            	
            	$this->establecerAtributos($arr, __CLASS__);
				
        	}//fin if    
        }
        $this->inicializarPagina();
        $this->obtenerConsultaPaginada();
    }//fin funcion constructora
    
      /**
     * Establece los atributos de una clase.
     * 
     * Valida si los valores pasados en el arreglo corresponden a los atributos de la clase en uso
     * y asigna el valor correspondiente, El metodo es sobreescrito para asignar valores privados
	   * necesarios para la clase del paginador.
     * 
     * @param array @arr Arreglo con valores 
     * @param instance @clase Instancia de la clase
     */
    protected function establecerAtributos($arr,$clase=""){
        $metodos=get_class_vars($clase);
        #print_r($arr);
        foreach($metodos as $k => $valor){
            
            if(isset($arr[$k])){
                $this->$k=$arr[$k];
            }

        }//final foreach
    }//final funcion establer atributos.
    
    function armarPaginador(){
        $seccionPaginador="";
          if($this->query!=""){
            $result = $this->bd->ejecutarQuery($this->queryReal);
            $this->totalRegistros = $this->bd->totalRegistros;
			$division=  $this->totalRegistros/$this->filasPorPagina;
			#echo $this->totalRegistros." ".$this->filasPorPagina."<hr>";
			#echo $division;exit;
            $this->totalPaginas=is_float($division)?ceil($division):$this->totalRegistros/$this->filasPorPagina;
            /**
			 * Se saca un numero medio sobre el total de
			 * paginas a mostrar
			 */
            $medioPaginas = ceil($this->paginasMostradas/2);
			$ultimaPaginaMostrada=(($this->paginaActual+$medioPaginas)< $this->totalPaginas)?$this->paginaActual+$medioPaginas:$this->totalPaginas;
			
			$primeraPaginaMostrada=($this->paginaActual>$medioPaginas)?$this->paginaActual-$medioPaginas:1;
            $seccionPaginador = "<div id=\"paginador\">";
            if($this->tipoPaginador=='lista')
                $seccionPaginador.="<ul class=\"$this->cssListaPaginador\">";
            //----------------------------------------
			if($this->paginaActual>1 and $this->usoBtnsNextPrev===TRUE){
				$link=$this->paginaActual-1;
				if($this->ajax===TRUE){
	            		$selector = "span";
						$content ="data-paginador=\"$link\""; 
	            	}else{
	            		$selector = "a";
						$content = "href=\"$this->paginaConsulta?pagina=$link\"";
						
	            	}
					switch($this->tipoPaginador){
						case 'lista':
							
							$seccionPaginador.="<li><$selector $content>$this->contenidoPrevBtn</$selector></li>";
							break;
						default:
							$seccionPaginador.="<$selector>$this->contenidoPrevBtn</$selector>";
							break;		
					}
					
					
					
				}
			
			/**
			 * Recorrido de las páginas
			 */
			 
            for($i=$primeraPaginaMostrada;$i<=$ultimaPaginaMostrada;$i++){
            	#El contenido de la etiqueta varia según el tipo de paginador, en caso de ser ajax se usa una etiqueta span, sino una a.
	            	if($this->ajax===TRUE){
	            		$selector = "span";
						$content ="data-paginador=\"$i\""; 
	            	}else{
	            		$selector = "a";
						$content = "href=\"$this->paginaConsulta?pagina=$i\"";
						
	            	}
					
						
		                if($i==$this->paginaActual){
		                    if($this->tipoPaginador=='lista'){
		                        $seccionPaginador.="<li class=\"$this->cssPaginaActual\" id=\"linkPages$i\" >
		                        					<span>$i</span>
		                        					</li>";								
							}else{
								
		                        $seccionPaginador.="<span class=\"$this->cssPaginaActual\" id=\"linkPages$i\" >$i</span>";
							}
		                }else{
		                    if($this->tipoPaginador=='lista'){
		                        $seccionPaginador.="<li class=\"$this->cssLinkPaginas\">
		                                                <$selector $content id=\"linkPages$i\">$i</$selector>
		                                            </li>";
		                    }else{
		                        $seccionPaginador.="<$selector $content id=\"linkPages$i\" class=\"$this->cssLinkPaginas\">$i</$selector>";
		                    }    
		                }
					
	                    
            }//fin recorrido filas
            
            if($this->paginaActual<$this->totalPaginas and $this->usoBtnsNextPrev===TRUE){
            	
				$link=$this->paginaActual+1;
				if($this->ajax===TRUE){
	            		$selector = "span";
						$content ="data-paginador=\"$link\""; 
	            	}else{
	            		$selector = "a";
						$content = "href=\"$this->paginaConsulta?pagina=$link\"";
						
	            	}
					switch($this->tipoPaginador){
						case 'lista':
							
							$seccionPaginador.="<li><$selector $content>$this->contenidoNextBtn</$selector></li>";
							break;
						default:
							$seccionPaginador.="<$selector>$this->contenidoNextBtn</$selector>";
							break;		
					}
			}
			
            //----------------------------------------
            
            $seccionPaginador.="</ul>
                              </div>";
            $seccionPaginador.=$this->funcionAjax();
        }//fin if
        return $seccionPaginador;
    }//fin funcion
    
    private function funcionAjax(){
    	$funcionAjax = "";
		if($this->ajax===TRUE){
			
			$funcionAjax.="<SCRIPT>
							function mostrarVistaPaginador(){
								$(\"#$this->selectorVista\" ).html(this.respuesta);
								
							}//fin funcion
							$( document ).ready(function(){
								
								$( \"[data-paginador]\").on('click',function(){
									datos = \"jvista=paginador&pagina=\"+encodeURIComponent($(this).data(\"paginador\"));
									//console.log(datos);
									//return false;	
									console.log('$this->paginaActual')
									console.log(\"$this->selectorVista\");
									llamadaAjax = new jd.ajax({
										metodo:\"POST\",
									    funcionCarga:mostrarVistaPaginador,
									    respuesta:\"html\",
									    parametros:datos,
									    cargando:console.log(\"cargando\"),
									    url : \"$this->paginaConsulta\"
									})	
								})	
							})
								
							";
			
			$funcionAjax.="</SCRIPT>";
			
		}
		return $funcionAjax;
    }
    /**
     * Obtiene la página actual pasada por parametro get o post
     */
    private function inicializarPagina(){
        $this->parametrosGet=$_REQUEST;
        $this->paginaActual=isset($this->parametrosGet['pagina'])?$this->parametrosGet['pagina']:1;
        #echo $this->paginaActual;
    }
    /**
     * ejecuta la consulta de la vista agregando el limite de registros
     * requeridos.
     */
    private function obtenerConsultaPaginada(){
    	$offset=($this->paginaActual<=1)?0:(($this->paginaActual-1)*$this->filasPorPagina);
       $this->query=$this->bd->addLimit($this->filasPorPagina, $offset,$this->queryReal);
       
       
    }
} // END


?>