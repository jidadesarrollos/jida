<?PHP
/**
 * Controlador de Menus
 *
 * @package Framework
 * @subpackage Jadmin
 * @author  Julio Rodriguez <jirc48@gmail.com>
 * @version 0.1 13/01/2014
 */
 
namespace Jida\Jadmin\Controllers;
use Exception;
use Jida\Helpers as Helpers;
use Jida\RenderHTML as RenderHTML;
use Jida\Modelos\Viejos as ModelosViejos;
use Jida\Modelos as Modelos;
class MenusController extends JController {

    function __construct(){
        $this->layout="jadmin.tpl.php";
        $this->url="/jadmin/menus/";
        parent::__construct();
        $jctrl = new Modelos\JidaControl();
        $tablas = $jctrl->obtenerTablasBD();
    }

    function index() {
        $query = "select id_menu,nombre_menu \"Nombre Menu\" from s_menus";
        $this -> vista = 'menus';

        $vistaMenu = new RenderHTML\Vista($query, $GLOBALS['configPaginador'], 'Menus');
        $vistaMenu->setParametrosVista($GLOBALS['configVista']);

        $vistaMenu -> acciones = array('nuevo'=>array('href'=>'/jadmin/menus/procesar-menu/'));
        $vistaMenu -> tipoControl = 2;
        $vistaMenu -> filaOpciones = array('0' => array
                                                    ('a' => array('atributos' =>array(
                                                                    'class'=>'btn',
                                                                    'href'=>'/jadmin/menus/opciones/menu/{clave}/'),
                                                                    'html' =>array('span' =>array('atributos' =>
                                                                                        array('class' => 'glyphicon glyphicon-folder-open',
																						'title'=>'Ver Opciones del Menu',)
                                                                                    )))),
                                             '1' => array('a' =>array('atributos' =>array(
                                                                                'href'=>'/jadmin/menus/eliminar-menu/menu/{clave}',
                                                                                'class'=> 'btn','title'=>'Eliminar Menu',
                                                                                ),
                                                                'html' => array('span' =>array(
                                                                                        'atributos' =>array(
                                                                                         'class' => 'glyphicon glyphicon-minus-sign',

                                                                                        ))))),
                                               '2' => array('a' =>array('atributos' =>array(
                                                                                'href'=>'/jadmin/menus/procesar-menu/menu/{clave}',
                                                                                'class'=> 'btn',
                                                                                ),
                                                                'html' => array('span' =>array(
                                                                                    'atributos' =>array(
                                                                                            'class' => 'glyphicon glyphicon-pencil',
                                                                                            'title'=>'Modificar Menu',
                                                                                             )))))
                                                );

        $vistaMenu -> actionForm = "/jadmin/menus/procesarMenu/";
        $this->dv->vistaMenu = $vistaMenu -> obtenerVista();

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
		$tipoForm = 1;
        $seleccion = "";
        $form = new RenderHTML\Formulario('ProcesarMenus', $tipoForm, $seleccion,2);
        $validacion = $form->validarFormulario($post);
        if(!is_array($validacion) and $validacion==TRUE){
            $idMenu = ($this->post('id_menu'))?$this->post('id_menu'):"";
            $classMenu = new ModelosViejos\Menu($idMenu);

            $valor = $classMenu->procesarMenu($post);
            if(isset($valor['result']['ejecutado']) and $valor['result']['ejecutado']==1){
                $msj = Mensajes::mensajeSuceso('Menu <strong>'.$valor['accion'].'</strong> exitosamente');
                Helpers\Sesion::set('__msjVista',$msj);
                Helpers\Sesion::set('__idVista','menus');
                redireccionar('/jadmin/menus/');
            }else{

                $msj= Mensajes::mensajeError($valor);

                Helpers\Sesion::set('__msjForm',$msj);

                Helpers\Sesion::set('__dataPostForm',$post);
                redireccionar('/jadmin/menus/procesar-menu/');
            }

        }else{

            $msj= Mensajes::mensajeError('No se ha podido procesar el menu');
            Helpers\Sesion::set('__msjForm',$msj);
            Helpers\Sesion::set('__DataPostForm',$post);
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

        $form = new RenderHTML\Formulario('ProcesarMenus', $tipoForm, $seleccion,2);
        $form -> action = '/jadmin/menus/set-menu/';
        $this->dv->tituloForm = ($tipoForm == 1) ? 'Registrar Menu' : 'Modificar Menu';
        $this->dv->formMenu = $form -> armarFormulario();

    }

    function eliminarMenu() {

        if ($this->getEntero($this->get('menu'))>0) {
            $seleccion = $this->get('menu');

			$cMenu = new Menu($seleccion);
	        if(!empty($cMenu->id_menu)){
	        	$cMenu->eliminarObjeto($cMenu->id_menu);
				RenderHTML\Vista::msj('menus','suceso', 'Menu eliminado');

	        }else{
	        	RenderHTML\Vista::msj('menus',"error","No se ha eliminado menu");
	        }

            $this->redireccionar('/jadmin/menus/');


        }else
        if(is_array($this->get('menu'))){
        	Helpers\Debug::mostrarArray($this->get('menu'));

        } else {
            throw new Exception("Debe seleccionar un menu", 1);
        }
    }//fin funcion

    function opciones() {

        $this->vista = "opcionesMenu";
        if ($this->get('menu')) {
            $menu = new ModelosViejos\Menu($this->get('menu'));
            $idMenu = $this->get('menu');
            $this->dv->vistaOpciones = $this -> vistaOpciones($idMenu);
        } else {
            throw new Exception("No ha seleccionado menu para ver opciones", 1);

        }

    }//fin función

    /**
     * Funcion controladora de gestion de opciones de un menu
     * @method procesarOpciones
     */
    function procesarOpciones() {
        if($this->get('menu')){

            $tipoForm=1;
            $campoUpdate=($this->getEntero($this->get('opcion'))>0)?$this->get('opcion'):"";

            $idMenu=$this->get('menu');
            $idOpcion="";
            $menu = new ModelosViejos\Menu($idMenu);

            $this->dv->titulo = "Registro de Opción para menu $menu->nombre_menu";

            $padre=0;
            if($this->getEntero($this->get('opcion'))){
                $idOpcion=$this->get('opcion');
                $tipoForm=2;
                $this->dv->titulo = "Modificar Opción de menu $menu->nombre_menu";
            }


            $formulario = new RenderHTML\Formulario('ProcesarOpcionMenu',$tipoForm,$campoUpdate,2);
            $formulario->externo['padre']="select id_opcion_menu,nombre_opcion from s_opciones_menu where id_menu = $idMenu";
            $formulario->action=$this->getUrl('procesarOpciones',['menu'=>$menu->id_menu]);
            if(!empty($idOpcion)){
                $formulario->action.="/opcion/".$idOpcion;
            }


            if($this->getEntero($this->get('padre'))>0){

                $post['padre']=$this->getEntero($this->get('padre'));
                $opcionPadre = new RenderHTML\OpcionMenu($this->get('padre'));
                $this->dv->subtitulo = "Subopci&oacute;n de $opcionPadre->nombre_opcion";
                $formulario->action=$this->getUrl('procesarOpciones',['menu'=>$menu->id_menu,'padre'=>$this->get('padre')]);
            }

            if($this->post('btnProcesarOpcionMenu')){
                if($formulario->validarFormulario()){

					$this->post('id_menu',$idMenu);
                    $opcionMenu = new RenderHTML\OpcionMenu($idOpcion);

                    if($opcionMenu->setOpcion($this->post())){

                        if(is_array($this->post('id_perfil'))){
                            $perfiles = array();

							$idOpcionMenu = ($opcionMenu->getResult()->idResultado()==0)?$opcionMenu->id_opcion_menu:$opcionMenu->getResult()->idResultado();
					        foreach ($this->post('id_perfil') as $key => $idPerfil) {
                                $perfiles[] = [ 'id_opcion_menu'=>$idOpcionMenu,
                                                'id_perfil'=>$idPerfil,
                                                'id_opcion_menu_perfil'=>'null'
                                                ];
                            }

                            $opcionesPerfil = new ModelosViejos\OpcionMenuPerfil();
                            $opcionesPerfil->eliminarAccesos($opcionMenu->getResult()->idResultado())->salvarTodo($perfiles);
                        }else{
                            Helpers\Debug::mostrarArray($this->obtPost('id_perfil'),0);
                            Helpers\Debug::string("No entramos");
                        }
                        RenderHTML\Vista::msj('opciones', 'sucess', $msj,$this->url.'opciones/menu/'.$idMenu.'/padre/'.$post['padre']);
                    }else{
                        RenderHTML\Formulario::msj('error', "No se ha podido registrar la opci&oacute;n, por favor vuelva a intentarlo");
                    }

                }else{
                    RenderHTML\Formulario::msj('error', "No se ha podido registrar la opci&oacute;n, por favor vuelva a intentarlo");
                }

            }


            $this->dv->formOpcion = $formulario->armarFormulario();

         }else{
            throw new Exception("No se ha seleccionado menu para agregar opciones", 1);
        }

    }//fin funciones

    function eliminarOpcion(){

        if($this->get('menu') and $this->get('opcion')){

            $idmenu = $this->getEntero($this->get('menu'));
            $idOpcion = $this->getEntero($this->get('opcion'));

            $Opcion = new RenderHTML\OpcionMenu($idOpcion);

            if($Opcion->eliminar([$idOpcion],'id_opcion_menu')){
            	RenderHTML\Vista::msj('opciones', 'info', 'La opci&oacute;n <strong> '.$Opcion->nombre_opcion.' </strong> ha sido eliminada','/jadmin/menus/opciones/menu/'.$idmenu);
            }else{
            	RenderHTML\Vista::msj('opciones', 'error', 'La acci&acute;n solicitada no es valida',$this->getUrl('opciones',['menu'=>$idmenu]));
            }


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
        $query = "select a.id_opcion_menu,a.nombre_opcion as \"Nombre\",a.url_opcion as \"Url\",a.hijo,a.orden,
                   c.estatus
                from s_opciones_menu a
                join s_estatus c on (a.id_estatus=c.id_estatus)
                where a.id_menu=$this->id_menu";
        $urlForm = $this->getUrl('procesarOpciones',['menu'=>$idMenu]);


        if($this->getEntero($this->get('padre'))){

            $query.=" and padre=".$this->get('padre')."";
            $omObject= new RenderHTML\OpcionMenu();
            $opcionesMenu = $omObject->getOpcionesByMenu($idMenu);
            $arbolObject = new \Jida\Core\Arbol($opcionesMenu);

            $arbolObject->estructurarArbolById('id_opcion_menu');
            $arbol = $arbolObject->obtenerArbol($this->get('padre'));
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


        $vista = new RenderHTML\Vista($query, $GLOBALS['configPaginador'], 'Opciones');
        $vista->opcionesBreadCrumb=$dataBC;

        $vista->filaOpciones=array(0=>array('a'=>array(
                                            'atributos'=>array( 'class'=>'btn',
                                                                'title'=>'ver subcategorias',
                                                                'href'=>$this->getUrl('opciones',['menu'=>$idMenu,'padre','{clave}'])
                                                                ),
                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-eye-open'))))),
                                    1=>array('a'=>array(
                                                            'atributos'=>array( 'class'=>'btn',
                                                                                'title'=>'Agregar subcategoria',
                                                                                'href'=>$urlForm."padre/{clave}",
                                                                                #'data-jvista'=>'modal'
                                                                                ),
                                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-plus'))))),
                                    2=>array('a'=>array(
                                                            'atributos'=>array( 'class'=>'btn',
                                                                                'title'=>'Editar',
                                                                                'href'=>$urlForm."opcion/{clave}",
                                                                                #'data-jvista'=>'modal'
                                                                                ),
                                                            'html'=>array('span'=>array('atributos'=>array('class' =>'glyphicon glyphicon-edit'))))),
                                    );
        $urlForm = $this->url."procesar-opciones/menu/".$idMenu."/";
        $opciones=['menu'=>$idMenu];
        if($this->getEntero($this->get('padre'))) $opciones['padre']=$this->get('padre');
            $urlForm = $urlForm."padre/".$this->get('padre');
        $vista ->acciones=array('Nuevo'=>array('href'=>$this->getUrl('procesarOpciones',$opciones),'class'=>'btn'),
                                'Modificar'=>array('href'=>$this->getUrl('procesarOpciones',$opciones),
                                                    'data-jvista'=>'seleccion','class'=>'btn',
                                                    'data-jkey'=>'opcion'
                                                    ,),
                                'Eliminar'=>array('href'=>$this->getUrl('eliminarOpcion',['menu'=>$this->id_menu]),
                                                    'data-jvista'=>'seleccion','class'=>'btn',
                                                    'data-jkey'=>'opcion'
                                                    )
                                );
        $vista -> tipoControl = 2;
        $vista->setParametrosVista($GLOBALS['configVista']);
        $vista->mensajeError="No hay opciones <a href=\"$urlForm\" class=\"btn\">Registar Opci&oacute;n</a>";
        $this->dv->vista= $vista -> obtenerVista();

        return $this->dv->vista;

    }


} // END
