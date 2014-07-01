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
 */

 
class MenuHTML extends DBContainer{
    
    /**
     * Define la configuración del menu por medio de un arreglo
     * @var array $configuracion
     * @access public
     */
     var $configuracion=array('ul'=>array(),"li"=>array());
    /**
     * Objeto Menu a manejar
     */
    private $menu ="";
    
    /**
     * Define el tipo de selector para una opcion de la lista
     * con submenu 
     */
    var $tagAdicionalLIpadre=FALSE;
    
    /**
     * Define el nivel de identacion
     * @var $identacion
     */
    private $identacion=2;
    /**
     * Funcion constructora de menus
     * @param int $id Clave del menu
     * @param int $tipo Determina si el menu será buscado en la tabla s_menus o en otra. 1)[por defecto] Tabla menus 2)otra 
     */
    function __construct($id="",$tipo=1){
        $this->nombreTabla="s_opciones_menu";
        $this->clavePrimaria="id_opcion";
        if($tipo==1){
            $this->menu = new Menu($id);    
        }else{
            
        }
        
        parent::__construct();
    }
    /**
     * Arma un menu a partir de una tabla distinta a s_menus
     * @param $funcion Nombre de la función del objeto del cual se obtendran las opciones
     * 
     */
    function showMenuPersonalizado($data){
       try{
           $config = $this->configuracion;
         
            if(count($data)>0){
            
                if(!array_key_exists("li", $config)){
                    $config['li']=array(0=>"");
                }
                $listaMenu = $this->armarListaMenuRecursivo($data,$config);
                return $listaMenu;    
                
            }

       } catch(Exception $e){
           Excepcion::controlExcepcion($e);
       }
        
        
    }
    /**
     * Devuelde un menu armado
     * 
     * Obtiene el menu solicitado consultando el modelo y arma una lista
     * HTML con las opciones del menu.
     * 
     * @param string $nombre Nombre del menu a consultar
     * @param array $config Arreglo Css para el menu [opcional], debe tener formato de array(ul=>array(),li=>array()) 
     * donde cada posición de los sub-arreglos son las clases a agregar por nivel. si hay mas niveles q los colocados en el arreglo los ultimos
     * niveles tomarán la misma clase que el último pasado
     */
    function showMenu(){
            $config = $this->configuracion;
            
        try{
            
            
            $menu = $this->menu;
            $opciones = $menu->obtenerOpcionesMenu();
            
            if(count($opciones)>0){
                
                if(!array_key_exists("li", $config)){
                    $config['li']=array(0=>"");
                }
                $listaMenu = $this->armarListaMenuRecursivo($opciones,$config);
                return $listaMenu;    
            }else{
                return true;
            }
            
            
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
        
    }
    /**
     * Arma un menu
     * 
     * Arma un menu en una lista, verifica si las opciones tienen submenus y los arma de forma recursiva
     * @param array $opciones Opciones del menu
     * @param array $config Arreglo Css para el menu [opcional], debe tener formato de array(ul=>array(),li=>array()) 
     * donde cada posición de los sub-arreglos son las clases a agregar por nivel. si hay mas niveles q los colocados en el arreglo los ultimos
     * niveles tomarán la misma clase que el último pasado
     * @param int $nivelPadre Nivel del menu a armar (uso recursivo)
     */
    private function armarListaMenuRecursivo($opciones,$cssMenu){
        $nivel=0;
        
        if(array_key_exists($nivel, $cssMenu['ul'])){
            $cssUl = $cssMenu['ul'][$nivel];
        }else{
            
            $cssUl ="";
            
        }
        $listaMenu="";
        foreach ($opciones as $key => $opcion) {
            if($opcion['padre']==0){
                if(array_key_exists($nivel, $cssMenu['li'])){
                    $cssli = $cssMenu['li'][$nivel];
                }else{
                    $cssli =end($cssMenu['li']);
                    
                }
                if($opcion['hijo']==1){
                    $submenu=""; 
                    $submenu = $this->armarMenuRecursivoHijos($opciones,$cssMenu,$opcion['id_opcion']);
                    if($this->tagAdicionalLIpadre!==False){
                        $this->identacion=4;
                        if(!array_key_exists('atributos', $this->tagAdicionalLIpadre)):
                            $this->tagAdicionalLIpadre['atributos']=array();
                        endif;
                        $opc = CampoHTML::crearSelectorHTMLSimple($this->tagAdicionalLIpadre['selector'],$this->tagAdicionalLIpadre['atributos'],$opcion['nombre_opcion'],3,true);
                    }else{
                        $opc = $opcion['nombre_opcion'];
                    }
                    $listaMenu.=CampoHTML::crearSelectorHTMLSimple("li",array("class"=>$cssli),$opc.$submenu,2,true);
                }else{
                    
                    //$span = CampoHTML::crearSelectorHTMLSimple("span",array(),$opcion['nombre_opcion'],4);
                    $span =$opcion['nombre_opcion'];
                    
                    $enlace = CampoHTML::crearSelectorHTMLSimple("a",array('href'=>$opcion['url_opcion']),$span,3);
                    $listaMenu.=CampoHTML::crearSelectorHTMLSimple("li",array('class'=>$cssli),$enlace,2,true);
                }
                
            }else{
                
                
                
            }
        }//fin foreach
        $listaMenu= CampoHTML::crearSelectorHTMLSimple("ul",array("class"=>"$cssUl"),$listaMenu,1,true);
        //$listaMenu.="\n\t\t</ul>";
        return $listaMenu;
    }

    private function armarMenuRecursivoHijos($opciones,$config,$padre,$nivel=1){
        
        if($padre==12){
         //Arrays::mostrarArray($opciones);
        }
         $ident = $this->identacion+$nivel+2;
         if(array_key_exists($nivel, $config['ul'])){
            $cssUl['class'] = $config['ul'][$nivel];
        }else{
            $cssUl['class'] ="";
            
        }
        $listaMenu="";
        foreach ($opciones as $key => $subopcion) {
             if(in_array($nivel, $config['ul'])){
                $cssli['class'] = $config['li'][$nivel];
            }else{
                $cssli['class'] ="";
                
            }
            if($subopcion['padre']==$padre){
                if($subopcion['hijo']==1){
                    
                    $submenus = $this->armarMenuRecursivoHijos($opciones,$config,$subopcion['id_opcion'],$nivel+1);
                    if(is_array($this->tagAdicionalLIpadre)){
                        $opc = CampoHTML::crearSelectorHTMLSimple($this->tagAdicionalLIpadre['selector'],$this->tagAdicionalLIpadre['atributos'],$subopcion['nombre_opcion'],$ident+3);
                    }else{
                        $opc = $subopcion['nombre_opcion'];
                    }
                    $listaMenu .= CampoHTML::crearSelectorHTMLSimple("li",$cssli,$opc.$submenus,$nivel+1);
                }else{
                    $span = $subopcion['nombre_opcion'];
                    $enlace = CampoHTML::crearSelectorHTMLSimple("a",array('href'=>$subopcion['url_opcion']),$span,$ident+3);
                    $listaMenu.=CampoHTML::crearSelectorHTMLSimple("li",$cssli,$enlace,$nivel+2,true);   
                }
            }
            
        }//fin foreach
        $submenu=CampoHTML::crearSelectorHTMLSimple("ul",$cssUl,$listaMenu,$this->identacion,true);
        return $submenu;
    }
}


?>