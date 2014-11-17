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

    function __construct(){
        $this->layout="jadmin.tpl.php";
        $this->url="/jadmin/menus/";
        $jctrl = new JidaControl();
        $tablas = $jctrl->obtenerTablasBD();        
    }
    
    function index() {
        $query = "select id_menu,nombre_menu \"Nombre Menu\" from s_menus";
        $this -> vista = 'menus';
        
        $dataArray = array();
        $vistaMenu = new Vista($query, $GLOBALS['configPaginador'], 'Menus');
        $vistaMenu->setParametrosVista($GLOBALS['configVista']);
        
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
            $this->data = $this->formMenu();
    }

    /**
     * Registra o modifica un menú
     * @access public
     * @method setMenu
     */
    function setMenu() {
        
        $post =& $_POST;
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
        $form = new Formulario('ProcesarMenus', $tipoForm, $seleccion,2);
        $form -> action = '/jadmin/menus/set-menu/';
        $dataArray['tituloForm'] = ($tipoForm == 1) ? 'Registrar Menu' : 'Modificar Menu';
        $dataArray['formMenu'] = $form -> armarFormulario();
        return  $dataArray;
    }

    function eliminarMenu() {
        
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
    }//fin funcion

    function opciones() {
        
        $this->vista = "opcionesMenu";
        if (isset($_GET['menu'])) {
            $menu = new Menu($_GET['menu']);
            $idMenu = $_GET['menu'];
            $dataArray['vistaOpciones'] = $this -> vistaOpciones($idMenu);
        } else {
            throw new Exception("No ha seleccionado menu para ver opciones", 1);

        }
        $this->data=  $dataArray;
    }//fin función

    /**
     * Funcion controladora de gestion de opciones de un menu
     *
     */
    function procesarOpciones() {         
        if(isset($_GET['menu'])){
            $post =&  $_POST;
            
            
            $tipoForm=1;
            $campoUpdate=(isset($_GET['opcion']) and $this->getEntero($_GET['opcion'])>0)?$_GET['opcion']:"";
            
            $idMenu  =$_GET['menu'];
            $idOpcion="";
            $menu = new Menu($idMenu);
            
            $dataArray['titulo'] = "Registro de Opción para menu $menu->nombre_menu";
            $padre=0;
            if(isset($_GET['padre']))
                $post['padre']=$this->getEntero($_GET['padre']);
            if(isset($_GET['opcion']) and $this->getEntero($_GET['opcion'])){
                $idOpcion=$_GET['opcion'];
                $tipoForm=2;
                $dataArray['titulo'] = "Modificar Opción de menu $menu->nombre_menu";
            }
            $formulario = new Formulario('ProcesarOpcionMenu',$tipoForm,$campoUpdate,2);
            $formulario->externo['padre']="select id_opcion,nombre_opcion from s_opciones_menu where id_menu = $idMenu";
            $formulario->action=$this->url.'procesar-opciones/menu/' . $menu -> id_menu . "/";
            
            if(isset($_GET['padre']) and $this->getEntero($_GET['padre'])>0){
                $opcionPadre = new OpcionMenu($_GET['padre']);
                $dataArray['subtitulo'] = "Subopci&oacute;n de $opcionPadre->nombre_opcion";
            }
            if(isset($_POST['btnProcesarOpcionMenu'])){
                $validacion = $formulario->validarFormulario();
                if($validacion===TRUE){
                    $post['id_menu'] = $idMenu;
                    $opcionMenu = new OpcionMenu($idOpcion);
                    $guardado = $opcionMenu->setOpcion($post);
                    if($guardado['ejecutado']==1){
                        
                        Vista::msj('opciones', 'sucess', $msj,$this->url.'opciones/menu/'.$idMenu.'/padre/'.$post['padre']);
                    }else{
                        Formulario::msj('error', "No se ha podido registrar la opci&oacute;n, por favor vuelva a intentarlo");    
                    }
                    
                }else{
                    Formulario::msj('error', "No se ha podido registrar la opci&oacute;n, por favor vuelva a intentarlo");
                }
                
            }
            
            
            $dataArray['formOpcion'] = $formulario->armarFormulario();
            $this->data = $dataArray;    
         }else{
            throw new Exception("No se ha seleccionado menu para agregar opciones", 1);
        }
        
    }//fin funciones

    function eliminarOpcion(){
        
        if(isset($_GET['menu']) and isset($_GET['opcion'])){
            $idmenu = $this->getEntero($_GET['menu']);
            $idOpcion = $this->getEntero($_GET['opcion']);
            $Opcion = new OpcionMenu($idOpcion);
            $Opcion->eliminarOpcion();
            Session::set('__idVista','opciones');
            Session::set('__msjVista',Mensajes::mensajeInformativo("La opci&oacute;n <strong> $Opcion->nombre_opcion </strong> ha sido eliminada"));
            redireccionar('/jadmin/menus/opciones/menu/'.$idmenu);
        }
    }//fin funcion
    
    /**
     * Crea una vista con las opciones de un menu
     * @method vistaOpciones
     * @access public
     */
    private function vistaOpciones($idMenu = "") {
        
        if (!empty($idMenu)) {
            $this -> id_menu = $idMenu;
        }else{
            throw new Exception("Se debe seleccionar un menu", 1);   
        }
        $query = "select a.id_opcion,a.nombre_opcion as \"Nombre\",a.url_opcion as \"Url\",a.hijo,a.orden,
                   c.estatus
                from s_opciones_menu a 
                join s_estatus c on (a.id_estatus=c.id_estatus) 
                where a.id_menu=$this->id_menu";
        $urlForm = $this->url."procesar-opciones/menu/".$idMenu."/";
        if(isset($_GET['padre']) and $this->getEntero($_GET['padre'])){
            
            $query.=" and padre=".$_GET['padre']."";
            $omObject= new OpcionMenu();
            $opcionesMenu = $omObject->getOpcionesByMenu($idMenu);
            $arbolObject = new Arbol($opcionesMenu);
            
            $arbolObject->estructurarArbolById('id_opcion'); 
            $arbol = $arbolObject->obtenerArbol($_GET['padre']);
            $dataBC = array();
            $dataBC['selector']="a";
            $dataBC[0]['nombreLink']="Categorias";
            $dataBC[0]['enlace']=$this->url;
            $i=1;
            foreach(array_reverse($arbol) as $key =>$value){
                $dataBC[$i]['nombreLink']=$value['nombre_opcion'];
                $dataBC[$i]['enlace'] = $this->url."padre/".trim(str_replace(" ", "-", $value['nombre_opcion']))."/";
                $i++;
            }
        }else{
            $query.=" and padre=0";
            $dataBC=FALSE;  
        }
        
        
        $vista = new Vista($query, $GLOBALS['configPaginador'], 'Opciones');
        $vista->opcionesBreadCrumb=$dataBC;
        
        $vista->filaOpciones=array(0=>array('a'=>array(
                                            'atributos'=>array( 'class'=>'btn',
                                                                'title'=>'ver subcategorias',
                                                                'href'=>$this->url."opciones/menu/$idMenu/padre/{clave}"
                                                                ),
                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-eye-open'))))),
                                    1=>array('a'=>array(
                                                            'atributos'=>array( 'class'=>'btn',
                                                                                'title'=>'Agregar subcategoria',
                                                                                'href'=>$urlForm."padre/{clave}",
                                                                                #'data-jvista'=>'modal'
                                                                                ),
                                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-plus'))))),
                                    );
        if(isset($_GET['padre']) and $this->getEntero($_GET['padre']))
            $urlForm = $urlForm."padre/".$_GET['padre'];          
        $vista ->acciones=array('Nuevo'=>array('href'=>$urlForm,'class'=>'btn'),
                                'Modificar'=>array('href'=>$urlForm,
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
        $vista->mensajeError="No hay opciones <a href=\"$urlForm\" class=\"btn\">Registar Opci&oacute;n</a>";
        $dataArray['vista'] = $vista -> obtenerVista();
		
        return $dataArray['vista'];

    }
    
 
} // END
?>