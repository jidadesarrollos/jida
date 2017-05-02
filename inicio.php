<?php
/**
 * Archivo de Arranque de la aplicación
 *
 * @internal
 *  Ejecuta las configuraciones requeridas para que una
 * aplicación arranque correctamente.
 *
 *
 * @author Julio Rodriguez <jirc48@gmail.com>
 * @package Framework
 * @category bootstrap
 */

global $jdOpciones;
$jdOpciones=[];
/**
 * Incluir archivo de configuración de Base de datos
 */

include_once 'Helpers/FuncionesBasicas.php';
set_time_limit(180);

/**
 * Directorio del directorio del framework
 */

define ('framework_dir', ROOT . 'Framework' . DS);
define ('DIR_FRAMEWORK',ROOT.'Framework'. DS );
define ('DIR_APP', ROOT . 'Aplicacion'. DS );

define ('libs_dir', ROOT . 'libs' . DS);

/**
 * Directorio publico de HTDOCS completo
 *
 * Usada para manejo interno en busqueda de archivos.
 */
define ('HTDOCS_DIR',ROOT.'htdocs/');

define('htdocs_dir',HTDOCS_DIR);
/**
 * @constante dev Deterimina si el sistema se entorno de desarrollo
 */
define('dev','dev');
/**
 * @constante prod Constante definida para determinar el sistema en entorno de producción
 */
define('prod','prod');

if(function_exists('ini_set')){
	/**
	 * Inclusión de directorios de aplicación, framework y libs dentro del path
	 */
	ini_set('include_path',DIR_APP . PS . DIR_FRAMEWORK .PS . libs_dir . PS . get_include_path());
}else{
	echo "<h5>No existe la funci&oacute;n</h5>";
}

if(!defined('TEST_PLATFORM')){
	define('TEST_PLATFORM',false);
}
if(TEST_PLATFORM==TRUE){

	TestPlataforma();
}

#=======================================================================
#=======================================================================
#=======================================================================
/**
 * Se incluye el archivo de configuración por defecto
 */

/**
 * Incluir archivo de configuración general del framework
 */
 if(file_exists(DIR_APP.'Config/BDConfig.php')){

	include_once 'Config/BDConfig.php';

 }

if(file_exists(DIR_APP . 'Config/initConfig.php'))
	include_once 'Config/initConfig.php';
include_once 'Settings/jConstantes.php';
include_once 'Settings/jidaConfiguracion.php';

if(file_exists(DIR_APP) and file_exists(DIR_APP . 'Config/appConfig.php'))
	include_once 'Config/appConfig.php';
if(array_key_exists('include', $GLOBALS)){
	foreach ($GLOBALS['include'] as $key => $archivo) {
		include_once $archivo;
	}
}
#=======================================================================
#=======================================================================
#=======================================================================
/**
 * Manejo de Errores
 *
 */
if(ENTORNO_APP == 'dev'){
	/* True */
    ini_set("display_errors", 1);
    ini_set("track_errors", 1);
    ini_set("html_errors", 1);
    error_reporting(E_ALL);
}else{
    /* False */
    ini_set("display_errors", 0);
    ini_set("track_errors", 0);
    ini_set("html_errors", 0);
    error_reporting(0);
}

#include_once 'Core/Autoload.class.php';
#Carga de clases automaticamente
#Autoload::init();

if(file_exists(DIR_FRAMEWORK.'/vendor/autoload.php')){

	 require_once DIR_FRAMEWORK.'/vendor/autoload.php';
	#Debug::mostrarArray(get_declared_classes (  ));
}
if(file_exists('vendor/autoload.php')){
	
	require_once 'vendor/autoload.php';
}

global $elementos;
$elementos=['areas'=>[],'elementos'=>[]];
Jida\Helpers\Sesion::iniciar();
