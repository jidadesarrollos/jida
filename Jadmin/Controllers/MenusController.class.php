<?PHP
/**
 * Controlador de Menus
 *
 * @package Framework
 * @subpackage Jadmin
 * @author  Julio Rodriguez <jirc48@gmail.com>
 * @version 0.1 13/01/2014
 */
class MenusController extends Controller {

    /**
     *
     */
    var $menu;

    /**
     *
     */

    function __construct(){
        try{
            $this->header="jadminDefault/header.php";
            $this->footer="jadminDefault/footer.php";
              $jctrl = new JidaControl();
            $tablas = $jctrl->obtenerTablasBD();
                
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
        
        
    }
    function index() {
        $query = "select * from s_menus";
        $this -> vista = 'menus';
        $dataArray = array();
        $vistaMenu = new Vista($query, true, 'Menus');
        $vistaMenu -> setParametrosVista($GLOBALS['configVista']);
        $vistaMenu -> acciones = array('nuevo'=>array('href'=>'/jadmin/menus/procesar-menu/'));
        $vistaMenu -> tipoControl = 2;
        
        
        $vistaMenu -> filaOpciones = array('0' => array
                                                    ('a' => array('atributos' =>array(
                                                                    'class'=>'btn',
                                                                    'href'=>'/jadmin/menus/opciones/menu/{clave}/'),
                                                                    'html' =>array('span' =>array('atributos' => 
                                                                                        array('class' => 'glyphicon glyphicon-folder-open')
                                                                                    )))),
                                             '1' => array('a' =>array('atributos' =>array( 
                                                                                'href'=>'/jadmin/menus/eliminar-menu/menu/{clave}', 
                                                                                'class'=> 'btn',
                                                                                ),
                                                                'html' => array('span' =>array(
                                                                                        'atributos' =>array(
                                                                                         'class' => 'glyphicon glyphicon-minus-sign'
                                                                                        ))))),
                                               '2' => array('a' =>array('atributos' =>array( 
                                                                                'href'=>'/jadmin/menus/procesar-menu/menu/{clave}', 
                                                                                'class'=> 'btn',
                                                                                ),
                                                                'html' => array('span' =>array(
                                                                                    'atributos' =>array(
                                                                                            'class' => 'glyphicon glyphicon-pencil'
                                                                                             )))))
                                                );
                                                
        $vistaMenu -> actionForm = "/jadmin/menus/procesarMenu/";
        $dataArray['vistaMenu'] = $vistaMenu -> obtenerVista();
        $this->data = $dataArray;
    }

    function procesarMenu() {
        try{
            
            $this->data = $this->formMenu();    
        }catch(Exception $e){
            Excepcion::controlExcepcionUser($e);
        }
    }

    /**
     * Registra o modifica un menú
     * @access public
     * @method setMenu
     */
    function setMenu() {
        try{
            $post = $_POST;
            $form = new Formulario('ProcesarMenus', 1);
            $validacion = $form->validarFormulario($post);
            if(!is_array($validacion) and $validacion==TRUE){
                $idMenu = isset($post['id_menu']) ? $post['id_menu'] : '';
                $classMenu = new Menu($idMenu);
                
                $valor = $classMenu->procesarMenu($post);
                if(isset($valor['result']['ejecutado']) and $valor['result']['ejecutado']==1){
                    $msj = Mensajes::mensajeSuceso('Menu <strong>'.$valor['accion'].'</strong> exitosamente');
                    Session::set('__msjVista',$msj);
                    Session::set('__idVista','menus');
                    redireccionar('/jadmin/menus/');
                }else{
                    
                    $msj= Mensajes::mensajeError($valor);
                    
                    Session::set('__msjForm',$msj);
                    
                    Session::set('__dataPostForm',$post);
                    redireccionar('/jadmin/menus/procesar-menu/');    
                }
                
            }else{
                
                $msj= Mensajes::mensajeError('No se ha podido procesar el menu');
                Session::set('__msjForm',$msj);
                Session::set('__DataPostForm',$post);
                redireccionar('/jadmin/procesar-menu/');
            }
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
    }//final funcion

    /**
     * Devuelve el formulario para registro y modificación de menues.
     */
    private function formMenu() {
        $get = $_GET;
        $tipoForm = 1;
        $seleccion = "";
        if(isset($get['menu'])){
            $tipoForm=2;
            $seleccion = $get['menu'];    
        }
        $dataArray = array();
        $form = new Formulario('ProcesarMenus', $tipoForm, $seleccion);
        $form -> action = '/jadmin/menus/set-menu/';
        $dataArray['tituloForm'] = ($tipoForm == 1) ? 'Registrar Menu' : 'Modificar Menu';
        $dataArray['formMenu'] = $form -> armarFormulario();
        return  $dataArray;
    }

    function eliminarMenu() {
        try {
            if (isset($_GET['menu'])) {
                $seleccion = $_GET['menu'];
                if(!is_array($seleccion)){
                    $seleccion = $this->getEntero($seleccion);
                }
                $cMenu = new Menu();
                
                if ($cMenu -> eliminarMenu($seleccion)) {
                    $_SESSION['__msjVista'] = Mensajes::mensajeSuceso("Menu <strong>$cMenu->nombre_menu</strong> Eliminado");
                    Session::set('__idVista', 'menus');
                    header('location:/jadmin/menus/');
                }
            } else {
                throw new Exception("Debe seleccionar un menu", 1);
            }

        } catch(Exception $e) {
            Excepcion::controlExcepcion($e);
        }
    }

    function opciones() {
        try {
            $this->vista = "opcionesMenu";
            if (isset($_GET['menu'])) {
                $menu = new Menu($_GET['menu']);
                $idMenu = $_GET['menu'];
                $dataArray['vistaOpciones'] = $this -> vistaOpciones($idMenu);
            } else {
                throw new Exception("No ha seleccionado menu para ver opciones", 1);

            }

        } catch(Exception $e) {
            
           Excepcion::controlExcepcion($e);
        }
        $this->data=  $dataArray;
    }

    /**
     * Funcion controladora de gestion de opciones de un menu
     *
     */
    function procesarOpciones() {         
        try {
            $post = $_POST;
            if(isset($_GET['menu'])){
                $idMenu  =$_GET['menu'];
            }else{
                throw new Exception("No se ha seleccionado menu para agregar opciones", 1);
            }
            $idOpcion="";
            if(isset($_GET['opcion'])){
                $idOpcion=$_GET['opcion'];
            }
            $this->data = $this->formularioOpcion($idMenu,$idOpcion);
   
        } catch(Exception $e) {
            Excepcion::controlExcepcion($e);
        }

    }//fin funciones
    /**
     * Crea un formulario para procesar una opción
     * 
     * @access private
     * @method formularioOpcion
     * @param array $post
     */
    private function formularioOpcion($idMenu,$mod=""){
        
        try{
            $menu = new Menu($idMenu);
            $dataArray['titulo'] = "Registro de Opción para menu $menu->nombre_menu";
            $tipoForm=1;
            $id="";
            if(isset($mod) and !empty($mod)){
                    $tipoForm=2;
                    $dataArray['titulo'] = "Modificar Opción de menu $menu->nombre_menu";
                    $id=$mod;        
            }
            $formulario = new Formulario('ProcesarOpcionMenu',$tipoForm,$id);
            $formulario->externo['padre']="select id_opcion,nombre_opcion from s_opciones_menu where id_menu = $idMenu";
            $formulario->action='/jadmin/menus/set-opcion/menu/' . $menu -> id_menu . "/";
            $dataArray['formOpcion'] = $formulario->armarFormulario();
            return $dataArray;    
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }     
        
    }
    function setOpcion() {
        
        try{
            if(isset($_GET['menu'])){
                $post = $_POST;
                $idMenu = $_GET['menu'];
                $opMenu = new OpcionMenu();
                $form = new Formulario('ProcesarOpcionMenu',1);
                $form->externo['padre']="select id_opcion,nombre_opcion from s_opciones_menu where id_menu = $idMenu";

                $validacion=$form->validarFormulario($post);
                if($validacion===TRUE){
                    $post['id_menu'] = $idMenu;
                    $guardado = $opMenu->setOpcion($post);
                    
                    if($guardado['ejecutado']==1){
                        Session::set('__msjVista',Mensajes::mensajeSuceso("Se ha registrado la opci&oacute;n <strong>$opMenu->nombre_opcion</strong>"));
                        Session::set('__idVista','opciones');
                        redireccionar('/jadmin/menus/opciones/menu/'.$idMenu.'/');
                        
                    }
                }else{
                    Session::set('__msjForm',Mensajes::mensajeError("No se ha podio registrar la opci&oacute;n"));
                    redireccionar('/jadmin/menus/procesar-opciones/menu/'.$idMenu);    
                }; 
                
                $this->data = $dataArray;  
            }else{
               Session::set('__msjVista', Mensajes::mensajeError("Debe seleccionar un menu para procesar opciones"));
               Session::set('__idVista','menus');
               redireccionar('/jadmin/menus/');
                
            }    
        }catch(Exception $e){
            controlExcepcion($e->getMessage());
        }
        
    }
    function eliminarOpcion(){
        try{
            if(isset($_GET['menu']) and isset($_GET['opcion'])){
                $idmenu = $this->getEntero($_GET['menu']);
                $idOpcion = $this->getEntero($_GET['opcion']);
                $Opcion = new OpcionMenu($idOpcion);
                $Opcion->eliminarOpcion();
                Session::set('__idVista','opciones');
                Session::set('__msjVista',Mensajes::mensajeInformativo("La opci&oacute;n <strong> $Opcion->nombre_opcion </strong> ha sido eliminada"));
                redireccionar('/jadmin/menus/opciones/menu/'.$idmenu);
            }
        }catch(Exception $e){
            Excepcion::controlExcepcion($e);
        }
    }
    /**
     * Crea una vista con las opciones de un menu
     * @method vistaOpciones
     * @access public
     */
    private function vistaOpciones($idMenu = "") {
        try {
            if (!empty($idMenu)) {
                $this -> id_menu = $idMenu;
            }else{
                throw new Exception("Se debe seleccionar un menu", 1);
                
            }
            $query = "select a.id_opcion,a.nombre_opcion as \"Nombre\",a.url_opcion as \"Url\",b.nombre_opcion,a.hijo
                     
                    from s_opciones_menu a 
                    left join s_opciones_menu b on (a.padre=b.id_opcion) where
                    a.id_menu=$this->id_menu";
            
            $vista = new Vista($query, $GLOBALS['configPaginador'], 'Opciones');
            // $vista->seccionBusqueda=TRUE;
            // $vista->camposBusqueda=array('padre','nombre');
            $vista ->acciones=array('Nuevo'=>array('href'=>'/jadmin/menus/procesar-opciones/menu/'.$this->id_menu."/",'class'=>'btn'),
                                    'Modificar'=>array('href'=>'/jadmin/menus/procesar-opciones/menu/'.$this->id_menu."/",
                                                        'data-jvista'=>'seleccion','class'=>'btn',
                                                        'data-jkey'=>'opcion'
                                                        ,),
                                    'Eliminar'=>array('href'=>'/jadmin/menus/eliminar-opcion/menu/'.$this->id_menu."/",
                                                        'data-jvista'=>'seleccion','class'=>'btn',
                                                        'data-jkey'=>'opcion'
                                                        )                                                        
                                    );
            $vista -> tipoControl = 1;
            $vista->setParametrosVista($GLOBALS['configVista']);
            $vista->mensajeError="No hay opciones <a href=\"/jadmin/menus/procesar-opciones/menu/".$this->id_menu."\" class=\"btn\">Registar Opci&oacute;n</a>";
            $dataArray['vista'] = $vista -> obtenerVista();
			
            return $dataArray['vista'];
        } catch(Exception $e) {
            controlExcepcion($e -> getMessage(), $e -> getCode());
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
    function showMenu($nombre,$config=array()){
            
        try{
            $jctrl = new JidaControl();
            $tablas = $jctrl->obtenerTablasBD();
            if(count($tablas)>0){
                
                $menu = new Menu($nombre);
                $this->modelo=$menu;
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
            
            $cssUl =end($cssMenu['ul']);
            
        }
        $listaMenu="\n\t\t<ul class=\"".$cssUl."\" id=\"".$this->modelo->nombre_menu."\">";
        foreach ($opciones as $key => $opcion) {
            if($opcion['padre']==0){
                if(in_array($nivel, $cssMenu['ul'])){
                    $cssli = $cssMenu['li'][$nivel];
                }else{
                    $cssli =end($cssMenu['li']);
                    
                }
                if($opcion['hijo']==1){
                    $subMenu = $this->armarMenuRecursivoHijos($opciones,$cssMenu,$opcion['id_opcion']);
                    $listaMenu.="\n\t\t\t<li class=\"$cssli\"><span>$opcion[nombre_opcion]</span>".$subMenu;
                    $listaMenu.="\n\t\t\t</li>";
                }else{
                    $listaMenu.="\n\t\t\t<li class=\"$cssli\">";
                    $listaMenu.="\n\t\t\t\t<a href=\"$opcion[url_opcion]\"><span>$opcion[nombre_opcion]</span></a>";
                    $listaMenu.="\n\t\t\t\t</li>";
                }
                
            }else{
                
                
                
            }
        }//fin foreach
        $listaMenu.="\n\t\t</ul>";
        return $listaMenu;
    }

    private function armarMenuRecursivoHijos($opciones,$config,$padre,$nivel=1){
         
         if(array_key_exists($nivel, $config['ul'])){
             
            $cssUl = $config['ul'][$nivel];
        }else{
            $cssUl =end($config['ul']);
            
        }
        $submenu = "\n\t\t\t\t<ul class=\"$cssUl\" id=\"".$this->modelo->nombre_menu."-$nivel\">";
        foreach ($opciones as $key => $subopcion) {
             if(in_array($nivel, $config['ul'])){
                $cssli = $config['li'][$nivel];
            }else{
                $cssli =end($config['li']);
                
            }
            if($subopcion['padre']==$padre){
                if($subopcion['hijo']==1){
                    if(array_key_exists('selectorPadre', $config))
                    $subMenu="";
                    $subMenu = $this->armarMenuRecursivoHijos($opciones,$cssMenu,$subopcion['id_opcion'],$nivel+1);
                    $submenu.="\n\t\t\t\t\t<li class=\"$cssli\"><span>$subopcion[nombre_opcion]</span>".$subMenu;
                    $submenu.="\n\t\t\t\t\t</li>";
                }else{
                    $submenu.="\n\t\t\t\t\t<li class=\"$cssli\">";
                    $submenu.="\n\t\t\t\t\t\t<a href=\"$subopcion[url_opcion]\"><span>$subopcion[nombre_opcion]</span></a>";
                    $submenu.="\n\t\t\t\t\t</li>";
                }
            }
            
        }//fin foreach
        $submenu.="\n\t\t\t\t</ul>";
        return $submenu;
    }
} // END
?>