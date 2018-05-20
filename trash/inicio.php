<?php
/**
 * Archivo de Arranque de la aplicación
 *
 * @internal
 *  Ejecuta las configuraciones requeridas para que una
 * aplicación arranque correctamente.
 *
 *
 * @author   Julio Rodriguez <jirc48@gmail.com>
 * @package  Framework
 * @category bootstrap
 */

include_once 'Config/Base.php';

global $jdOpciones;
$jdOpciones = [];

define('DIR_FRAMEWORK', ROOT . 'Framework' . DS);
define('DIR_APP', ROOT . 'Aplicacion' . DS);
define('HTDOCS_DIR', ROOT . 'htdocs/');


if (function_exists('ini_set')) {
    /**
     * Inclusión de directorios de aplicación, framework y libs dentro del path
     */
    ini_set('include_path', DIR_APP . PS . DIR_FRAMEWORK . PS . get_include_path());

} else {
    throw new Exception("Debe activar la funcion ini_set para continuar..");
}

if (file_exists(DIR_FRAMEWORK . '/vendor/autoload.php')) {
    require_once DIR_FRAMEWORK . '/vendor/autoload.php';
    #$a = App\Config\Mail;
}

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

include_once 'Config/jConstantes.php';
include_once 'Config/jidaConfiguracion.php';

if (file_exists(DIR_APP) and file_exists(DIR_APP . 'Config/appConfig.php')) {
    include_once 'Config/appConfig.php';
}


if (class_exists('\Jida\Manager\Manager')) {

    $manager = new \Jida\Manager\Manager();
    $manager->inicio();

    #$ctrlGeneral = new \Jida\Core\Manager\JidaController();

} else {
    include_once 'plantillas/error/errorConfig.php';
    exit;
}
