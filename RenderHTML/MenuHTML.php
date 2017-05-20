<?PHP
/**
 * Clase Modelo para Manejo de Menus en HTML
 *
 * Obtiene un menu de base de datos y maneja opciones para impresiones en HTML.
 *
 *
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category Core
 * @version 0.1 5-3-2012
 * @deprecated 1.4
 */

namespace Jida\RenderHTML;
use Jida\BD as BD;
use Jida\Modelos\Viejos\Menu 	as Menu;
use Jida\Helpers 				as Helpers;
use Jida\Render\Selector 		as Selector;

class MenuHTML extends BD\DBContainer{

    /**
     * Define la configuración del menu por medio de un arreglo
     * @var array $configuracion
     * @access public
     */
     var $configuracion=array('ul'=>array(),"li"=>array());
    /**
     * Objeto Menu a manejar
     */
    private $menu ="show";

    /**
     * Define el tipo de selector para una opcion de la lista
     * con submenu
     */
    var $tagAdicionalLIpadre=array('selector'=>'a','atributos'=>array('href'=>"#"));
    /**
     * Define el estilo para un li seleccionado o abierto por selección de un hijo
     * @var $cssLiSeleccionado
     */
    var $cssLiSeleccionado='selected';
    /**
     * Atributos en común que se vayan a agregar a una lista de subopciones
     * @var array $atributosULParent;
     */
    var $atributosLIParent = array('data-liparent'=>'true');
    /**
     * Define el estilo para un ul hijo abierto en caso de se encuentre seleccionada una subopción
     * @var $cssUlChildOpen
     */
    var $cssUlChildOpen='';
    /**
     * Define el nivel de identacion
     * @var $identacion
     */
    private $identacion=2;
    /**
     * Opciones del menu
     * @var array $opciones
     */
    private $opciones;
	/**
	 * Define una url base
	 * @var string $_urlBase
	 * @access private
	 * 
	 */
	private $_urlBase= '';
	private $_urlActual='';
    /**
     * Funcion constructora de menus
     * @param int $id Clave del menu
     * @param int $tipo Determina si el menu será buscado en la tabla s_menus o en otra. 1)[por defecto] Tabla menus 2)otra
     */

    function __construct($id="",$tipo=1){
        $this->nombreTabla="s_opciones_menu";
        $this->clavePrimaria="id_opcion";
		if(defined('URL_BASE'))
			$this->_urlBase = URL_BASE;
		
        if($tipo==1){
            $this->menu = new Menu($id);
        }else{

        }
        parent::__construct();
        $menu = $this->menu;


    }
    /**
     * Arma un menu a partir de una tabla distinta a s_menus
     * @param $funcion Nombre de la función del objeto del cual se obtendran las opciones
     *
     */
    function showMenuPersonalizado($data){
           //$this->opciones = $this->menu->obtenerOpcionesMenu();
           $config = $this->configuracion;

            if(count($data)>0){

                if(!array_key_exists("li", $config)){
                    $config['li']=array(0=>"");
                }
                $listaMenu = $this->armarListaMenuRecursivo($data,$config);
                return $listaMenu;
            }
    }
    /**
     * Devuelde un menu armado
     *
     * Obtiene el menu solicitado consultando el modelo y arma una lista
     * HTML con las opciones del menu.
     * @method showMenu
     * @param string $nombre Nombre del menu a consultar
     * @param array $config Arreglo Css para el menu [opcional], debe tener formato de array(ul=>array(),li=>array())
     * donde cada posición de los sub-arreglos son las clases a agregar por nivel. si hay mas niveles q los colocados en el arreglo los ultimos
     * niveles tomarán la misma clase que el último pasado
     */
    function showMenu($urlActual=""){
    	if(!empty($urlActual))
			$this->_urlActual = $urlActual;
		
        $config = $this->configuracion;
        $this->opciones = $this->menu->obtenerOpcionesMenu();

        $opciones=& $this->opciones;

        if(count($opciones)>0){

            if(!array_key_exists("li", $config)){
                $config['li']=array(0=>array());
            }
            $listaMenu = $this->armarListaMenuRecursivo($opciones,$config);
            return $listaMenu;
        }else{
            return true;
        }
    }

    function setPerfilesAcceso($perfiles){
        $this->menu->setPerfilesAccesoMenu($perfiles);
    }
    /**
     * Arma un menu
     *
     * Arma un menu en una lista, verifica si las opciones tienen submenus y los arma de forma recursiva
     * @param array $opciones Opciones del menu
     * @param array $config Arreglo Css para el menu [opcional], debe tener formato de array(ul=>array(),li=>array())
     * donde cada posición de los sub-arreglos son las clases a agregar por nivel. si hay mas niveles q los colocados en el arreglo los ultimos
     * niveles tomarán la misma clase que el último pasado
     */
    private function armarListaMenuRecursivo($opciones,$config){
        $nivel=0;

        if(array_key_exists($nivel, $config['ul'])){

            $atributosUL = $config['ul'][$nivel];
            if(!is_array($atributosUL))
                $atributosUL = ['class'=>$atributosUL];
        }else{
            $atributosUL =array();
        }

        $listaMenu="";

        foreach ($opciones as $key => $opcion) {
            if($opcion['padre']==0){
                if(array_key_exists($nivel, $config['li'])){
                    $atributos = $config['li'][$nivel];
                }else{
                    $atributos =end($config['li']);

                }
                //Se valida si en $config se paso una clase css o un arreglo de atributos
                if(!is_array($atributos)){
                    $atributos=['class'=>$atributos];
                }
                $icono="";
                 if(!empty($opcion['icono'])):

                    if($opcion['selector_icono']==2){
                        $icono = Selector::crear("img",['src'=>$opcion['icono']]);
                    }else{
                        $icono = Selector::crear("span",['class'=>$opcion['icono']]);
                    }
                endif;
                if($opcion['hijo']==1 or $opcion['hijo']=='t'){
                    $atributos = array_merge($atributos,$this->atributosLIParent);
                    $submenu="";
                    $submenu = $this->armarMenuRecursivoHijos($opciones,$config,$opcion['id_opcion_menu']);
                    if($submenu['open']===TRUE){
                        $atributos['class'] =$atributos['class'] ." ". $this->cssLiSeleccionado;
                    }
                    //Se agrega separador para lis padres si existe;

                    if(array_key_exists('caret', $config['li'][$nivel]))
                        $atributos['class']=$atributos['class']." ".$config['li']['caret'];
					if(array_key_exists('padre', $config['li'][$nivel]))
						$atributos['class'] = $atributos['class']." ".$config['li']['padre'];
                    if($this->tagAdicionalLIpadre!==False){
                        $this->identacion=4;
                        if(!array_key_exists('atributos', $this->tagAdicionalLIpadre)):
                            $this->tagAdicionalLIpadre['atributos']=array();
                        endif;
						
						$atributoslink = $this->tagAdicionalLIpadre['atributos'];
						
                        $opc = Selector::crear(
                        $this->tagAdicionalLIpadre['selector'],$atributoslink,$icono.
                        Selector::crear('span',['class'=>'inner-text'],$opcion['opcion_menu']
						)
                        ,3,true);
                    }else{
                        $opc = $icono.Selector::crear('span',['class'=>'inner-text'],$opcion['opcion_menu']);
                    }
					$atributos= array_merge($atributos,['id'=>'item-'.Helpers\Cadenas::guionCase($opcion['opcion_menu'])]);
                    $listaMenu.=Selector::crear("li",$atributos,$opc.$submenu['html'],2,true);
                }else{

                    $span =Selector::crear('span',['class'=>'inner-text'],$opcion['opcion_menu']);
					
                    $enlace = Selector::crear("a",array('href'=>$this->_urlBase . $opcion['url_opcion']),$icono.$span,3);
					$atributos= array_merge($atributos,['id'=>'item-'.Helpers\Cadenas::guionCase($opcion['opcion_menu'])]);
					
					if($this->_urlBase.$opcion['url_opcion'] == '/'.$this->_urlActual)
						$atributos['class'] = $this->cssLiSeleccionado;
		
                    $listaMenu.=Selector::crear("li",$atributos,$enlace,2,true);
                }

            }else{

            }
        }//fin foreach
        $listaMenu= Selector::crear("ul",$atributosUL,$listaMenu,1,true);

        return $listaMenu;
    }
    /**
     * Crea la lista de un submenu perteneciente a un menu principal
     * @method armarMenuRecursivoHijos
     */
    private function armarMenuRecursivoHijos($opciones,$config,$padre,$nivel=1){
        $ulOpen=FALSE;
        if($padre==12){
         //Arrays::mostrarArray($opciones);
        }
         $ident = $this->identacion+$nivel+2;
         if(array_key_exists($nivel, $config['ul'])){

			 if(is_array($config['ul'][$nivel]) and array_key_exists('class', $config['ul'][$nivel]))
			 	$cssUl['class'] = $config['ul'][$nivel]['class'];
			 else {
				$cssUl['class'] = $config['ul'][$nivel];
			 }

        }else{
            $cssUl['class'] ="";

        }


        $listaMenu="";
        if(array_key_exists($nivel, $config['li'])){
        	if(is_array($config['li'][$nivel]) and array_key_exists('class', $config['li'][$nivel]))
            	$configCSS = $config['li'][$nivel]['class'];
			else {
				$configCSS = $config['li'][$nivel];
			}
        }else{
            $configCSS ="";
        }
		
        foreach ($opciones as $key => $subopcion) {

			$cssli['class'] = $configCSS;
            $icono="";
            if(!empty($subopcion['icono'])):

                if($subopcion['selector_icono']==2){

                    $icono = Selector::crear("img",array('src'=>$subopcion['icono']));
                }else{
                    $icono = Selector::crear("span",array('class'=>$subopcion['icono']));
                }
            endif;
            if($subopcion['padre']==$padre){
            	
                if($subopcion['hijo']==1){
                	
                   $cssli = array_merge($cssli,$this->atributosLIParent);
                    $submenus = $this->armarMenuRecursivoHijos($opciones,$config,$subopcion['id_opcion_menu'],$nivel+1);
					

                    //Se agrega separador para lis padres si existe;
                    if(array_key_exists('caret', $config['li']))
                        $cssli['class']=$cssli['class']." ".$this->configuracion['li']['caret'];
                    if(array_key_exists($nivel, $config['li']) and is_array($config['li'][$nivel]) and array_key_exists('padre', $config['li'][$nivel])){
                        $cssli['class']=$cssli['class']." ".$this->configuracion['li'][$nivel]['padre'];
                    }
                    if(is_array($this->tagAdicionalLIpadre)){
                        $opc = Selector::crear($this->tagAdicionalLIpadre['selector'],$this->tagAdicionalLIpadre['atributos'],$icono.$subopcion['opcion_menu'],$ident+3);
                    }else{
                        $opc = $icono.Selector::crear('span',['class'=>'inner-text'],$subopcion['opcion_menu']);
                    }
                    $ulOpen=$submenus['open'];
                    $listaMenu .= Selector::crear("li",$cssli,$opc.$submenus['html'],$nivel+1);
                }else{
                    /**
                     * Entra aqui si es un link o enlace del menu
                     */
                    $span = Selector::crear('span',['class'=>'inner-text'],$subopcion['opcion_menu']);
                    if($subopcion['url_opcion']==$_SERVER['REQUEST_URI']){
                        $ulOpen=TRUE;
                        $cssli['class'] = $cssli['class']." ".$this->cssLiSeleccionado;
                    }
					
                    $enlace = Selector::crear("a",array('href'=>$this->_urlBase . $subopcion['url_opcion']),$icono.$span,$ident+3);
                    $listaMenu.=Selector::crear("li",$cssli,$enlace,$nivel+2,true);
                }
            }
        }//fin foreach
        if($ulOpen)
            $cssUl['class'] = $cssUl['class']." ".$this->cssUlChildOpen;

		if(array_key_exists($nivel,$config['ul']) and  is_array($config['ul'][$nivel]) and array_key_exists('selectorPadre', $config['ul'][$nivel])){

			$submenu = Selector::crear(
				$config['ul'][$nivel]['selectorPadre'],null,
				Selector::crear("ul",$cssUl,$listaMenu,$this->identacion,true));

		}else{
			$submenu=Selector::crear("ul",$cssUl,$listaMenu,$this->identacion,true);
		}

        return array('html'=>$submenu,'open'=>$ulOpen);
    }
}
