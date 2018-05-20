<?PHP

/**
 * Archivo de arranque del framework
 *
 * Inicializa las constantes principales y hace un llamado al archivo
 * inicio.php para finalizar el arranque de la aplicación
 *
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 */

/**
 * Inclusión de constantes
 */
#include_once 'contantes.php';
/**
 * Alias para separador de directorios
 */
define('DS', DIRECTORY_SEPARATOR);

define("PS", PATH_SEPARATOR);
/*
 * Directorio root de la aplicacion
 */
define('ROOT', realpath(dirname(__FILE__)) . DS);

include_once 'Framework/inicio.php';
$ctrlGeneral = new Jida\Core\Manager\JidaController();
?>