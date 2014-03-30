<?PHP 
/**
 * Define la configuración de entorno de la aplicación
 * @author Julio Rodriguez <jirc48@gmail.com>
 */
#--------------------------------------------------------------
 /**
  * Define el entorno de desarrollo
  */
  if(!defined('dev')){
    define('dev','dev');
  }
  if(!defined('prod')){
    define('prod','prod');
  }
  /**
   * Define el entorno actual de la aplicación
   */
  if(!defined('entorno_app')){
    define('entorno_app', dev );
  }
  
if(!defined('TEST_PLATFORM')){
    define('TEST_PLATFORM',FALSE);
  
}
define ('header_default', 'plantillas/default/header.php');
 
define ('footer_default','plantillas/default/footer.php');

define('USER_EMAIL','jirc48@gmail.com');
define('PASSWORD_EMAIL','ark0s0n3r.gma1l');
define('NAME_USER_MAIL','Grupo Electr&oacute;n');
  /**
   * Define ubicación de las plantillas del framework
   */
define  ('plantillas_framework_dir', framework_dir ."jidaPlantillas/");
define ('directorio_plantillas',app_dir.'plantillas/');
define  ('jida_admin_vistas_dir', framework_dir ."jadmin/Vistas/" );
define('img_dir','/htdocs/img/');
define ('plantillas_excepciones_dir', app_dir."plantillas/error/");


define ('titulo_sistema','Jida');
  /**
   * DEfinicion de constantes para mensajes
   */
       
   define('cssMsjError','alert alert-danger');
   define('cssMsjAlerta','alert alert-warning');
   define('cssMsjSuccess','alert alert-success');
   define('cssMsjInformacion','alert alert-info');
   
   
   
   
$GLOBALS['configPaginador'] = array(
    'cssLinkPaginas'        =>  "",
    'cssPaginaActual'       =>  "active",
    'cssListaPaginador'     =>  "pagination",
    'tipoPaginador'         =>  "lista",
    'filasPorPagina'        =>  15
);

$GLOBALS['configVista']=array(
                            'cssSectionBusqueda'=>'row form-inline',
                            'cssFormBusqueda'=>'navbar-form navbar-right',
                            'cssDivBusqueda'=>'col-lg-12 txt-derecha',
                            #'cssDivInputTextBusqueda'=>'col-xs-4',
                            'cssInputTextBusqueda'=>'form-control input-busqueda',
                            'cssDivInputBtnBusqueda'=>'',
                            'cssBotonBusqueda'=>'btn btn-default',
                            'cssTable'=>'table animate2 fadeInRight',
                            'cssTituloVista'=>'animate1 rotateInDownLeft',
                            
                            
                            );
$GLOBALS['configFormsElectron']=array(
							'cssSubmit'=>'btn btn-electron'
);
/**
 * Arreglo de modulos existentes
 */
define('mailNoResponder','no-responder@electron.com.ve');
define('mailCompras','compras@version1.electron.com.ve');
define('passwordNoResponder','3l3ctr0napp');
define('passwordCompras','3l3ctr0nappc0mpras');
define('url_sitio','version1.electron.com.ve');
$GLOBALS['modulos'] = array(
                            'jadmin',
                            'error',
                            'admin'
                            )

?>