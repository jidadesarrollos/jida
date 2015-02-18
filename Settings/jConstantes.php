<?PHP 
/**
 * Archivo contenedor de constantes de configuración establecidas con valores por defecto.
 * 
 * Todas las constantes pueden ser reescritas en la carpeta de configuración del directorio Aplicacion.
 * en los archivos appSetting. initConfig y BDConfig.
 * 
 */
/**
 * Permite defi
 */
 if(!defined('DBCONTAINER_NIVEL_ORM'))
    define('DBCONTAINER_NIVEL_ORM',1);

if(!defined('DB_PREFIJOS')){
    define('DB_PREFIJOS',TRUE);
}


if(!defined('APP_MANTENIMIENTO')){
    define('APP_MANTENIMIENTO',FALSE);
}
if(!defined('TPL_MANTENIMIENTO')){
    define('TPL_MANTENIMIENTO','Framework/jidaPlantillas/mantenimiento.tpl.php');
}


if(!defined('titulo_sistema'))
    define ('titulo_sistema','Jida');
if(!defined('NOMBRE_APP'))
    define('NOMBRE_APP','Aplicación de JidaFramework');


if(!defined('PLURAL_ATONO')){
    define('PLURAL_ATONO','s');
}
if(!defined('PLURAL_CONSONANTE')){
    define('PLURAL_CONSONANTE','es');
}

