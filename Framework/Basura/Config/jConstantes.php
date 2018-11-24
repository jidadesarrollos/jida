<?PHP
/**
 * Archivo contenedor de constantes de configuración establecidas con valores por defecto.
 *
 * @internal Todas las constantes pueden ser reescritas en la carpeta de configuración del directorio Aplicacion.
 * en los archivos appSetting. initConfig y BDConfig.
 *
 */
if (!array_key_exists('modulos', $GLOBALS)) {
    $GLOBALS['modulos'] = ['Jadmin'];
}

if (!defined('MODELO_USUARIO')) {
    define('MODELO_USUARIO', '\Jida\Modelos\User');
}
if (!defined('URL_BASE'))
    define('URL_BASE', "");
//Debug::mostrarArray($GLOBALS['modulos'],false);
if (!defined('MANEJADOR_BD'))
    define('MANEJADOR_BD', false);
/**
 * @constante TITULO_SISTEMA Nombre de la aplicación
 */
if (!defined('TITULO_SISTEMA'))
    define('TITULO_SISTEMA', 'Aplicación Jida - Framework');

/**
 * Nombre de la aplicacion,
 *
 * @deprecated
 * @see TITULO_SISTEMA
 * */
if (!defined('titulo_sistema'))
    define('titulo_sistema', TITULO_SISTEMA);
if (!defined('NOMBRE_APP'))
    define('NOMBRE_APP', 'Aplicación de JidaFramework');

if (!defined('CONTROLADOR_EXCEPCIONES'))
    /**
     * Nombre del controlador de las excepciones
     * Por defecto es el ExcepcionController del Framework el cual será llamdo,
     * si se define la constante será reemplazado el controlador e intentará
     * ejecutarse el definido en la constante.
     */
    define('CONTROLADOR_EXCEPCIONES', '\Jida\Core\Excepcion');
if (!defined('DIR_EXCEPCION_PLANTILLAS'))
    /**
     * Define el directorio en el que se encuentran las plantillas
     * de exepciones
     *
     * @constant   DIR_EXCEPCION_PLANTILLAS
     * @deprecated 2.0
     */
    define('DIR_EXCEPCION_PLANTILLAS', DIR_FRAMEWORK . DS . "plantillas/error/");
if (!defined('DIR_PLANTILLAS_APP'))
    /**
     * Define la ubicacion de las plantillas de una aplicacion
     *
     * @default Aplicacion/plantillas
     */
    define('DIR_PLANTILLAS_APP', DIR_APP . 'plantillas/');
if (!defined('METODO_EXCEPCION'))
    /**
     * Nombre del metodo a ejecutar en el CONTROLADOR_EXCEPCIONES
     * al conseguir una excepción.
     *
     * @constant METODO_EXCEPCION
     *
     */
    define('METODO_EXCEPCION', 'error');

#===============================================================================
# Constantes de entorno
#===============================================================================

if (!defined('ENTORNO_APP')) {
    /**
     * @constante ENTORNO_APP Define el entorno de la aplicación
     */
    define('ENTORNO_APP', dev);
}
if (!defined('entorno_app'))
    define('entorno_app', ENTORNO_APP);
if (!defined('TEST_PLATFORM')) {
    define('TEST_PLATFORM', false);

}
#===============================================================================
# Configuración del Framework
#===============================================================================
/* Definirá el nivel del orm del DataModel [aun no funcional] */
if (!defined('NIVEL_ORM'))
    define('NIVEL_ORM', 1);

if (!defined('DB_PREFIJOS')) {
    define('DB_PREFIJOS', true);
}
if (!defined('ORM_REGISTROS_RELACION')) {
    define('ORM_REGISTROS_RELACION', 20);
}

if (!defined('APP_MANTENIMIENTO')) {
    define('APP_MANTENIMIENTO', false);
}
if (!defined('TPL_MANTENIMIENTO')) {
    define('TPL_MANTENIMIENTO', 'Framework/plantillas/mantenimiento.tpl.php');
}

if (!defined('PREFIJO_TABLA'))
    define('PREFIJO_TABLA', true);
if (!defined('PREFIJO_RELACIONAL'))
    define('PREFIJO_RELACIONAL', "r");

if (!defined('PLURAL_ATONO')) {
    define('PLURAL_ATONO', 's');
}
if (!defined('PLURAL_CONSONANTE')) {
    define('PLURAL_CONSONANTE', 'es');
}

if (!defined('FECHA_CREACION')) {
    define('FECHA_CREACION', true);
}
if (!defined('FECHA_MODIFICACION')) {
    define('FECHA_MODIFICACION', true);
}

/**
 * Define la ubicacion fisica de las carpetas para archivos publicos y del lado cliente.
 */
if (!defined('DIR_HTDOCS'))
    define('DIR_HTDOCS', ROOT . 'htdocs/');

/**
 * @constant MANEJADOR_PARAMS
 * Mantiene el funcionamiento del pase de parametros en las URLs entre las distintas versiones del Framework
 * TRUE para utilizar el manejo actualizado desde V-1.4
 * FALSE para versiones inferiores
 */
if (!defined('MANEJADOR_PARAMS'))
    define('MANEJADOR_PARAMS', true);

define('LAYOUT_JIDA', 'jadminIntro.tpl.php');

/**
 * Constantes Framework
 */
if (!defined('LAYOUT_DEFAULT'))
    /**
     * Define el layout a usar por defecto
     */
    define('LAYOUT_DEFAULT', 'default.tpl.php');

if (!defined('LAYOUT_EXCEPCIONES'))
    /**
     * Nombre del layout para excepciones
     */
    define('LAYOUT_EXCEPCIONES', 'error.tpl.php');
if (!defined('DIR_LAYOUT_JIDA'))
    /**
     * Definición de la ubicacion de los templates para el backend del Framework
     *
     * Puede ser modificada su ubicación si desea personalizarse el disenio.
     */
    define('DIR_LAYOUT_JIDA', DIR_FRAMEWORK . DS . "Layout/jadmin/");
if (!defined('DIR_LAYOUT_APP'))
    define('DIR_LAYOUT_APP', DIR_APP . 'Layout/');
if (!defined('DIR_PLANTILLAS_FRAMEWORK'))
    define('DIR_PLANTILLAS_FRAMEWORK', DIR_FRAMEWORK . DS . "plantillas/");

if (!defined('ZONA_HORARIA')) {
    /**
     * Define la zona horaria con la cual se trabajarán todas las funciones
     * de fecha. basado en la estructura de php
     *
     * @link http://php.net/manual/es/timezones.america.php
     */
    define('ZONA_HORARIA', 'America/Caracas');
}

/**
 * Determina si los caracteres especiales son codificados en código ASCII HTML
 * antes de ser guardados en base de datos
 */
if (!defined('CODIFICAR_HTML_BD'))
    define('CODIFICAR_HTML_BD', false);

if (!defined('BD_REQUERIDA'))
    define('BD_REQUERIDA', false);
/**
 * Determina
 */
if (!defined('PATH_APP'))
    define('PATH_APP', '/');

$GLOBALS['idiomas'] = ['es' => 'Espa&ntilde;ol'];

if (!defined('URL_APP_PUBLICA'))
    define('URL_APP_PUBLICA', '');
if (!defined('URL_FB'))
    define('URL_FB', '');
if (!defined('URL_TWITTER'))
    define('URL_TWITTER', '');
if (!defined('URL_IMAGENES'))
    define('URL_IMAGENES', '');
if (!defined('URL_MEDIA_CORREOS'))
    define('URL_MEDIA_CORREOS', '');
if (!defined('URL_GOOGLE'))
    define('URL_GOOGLE', '');
if (!defined('URL_INSTAGRAM'))
    define('URL_INSTAGRAM', '');
if (!defined('CUENTA_TWITTER'))
    define('CUENTA_TWITTER', '');
if (!defined('CUENTA_INSTAGRAM'))
    define('CUENTA_INSTAGRAM', '');
/**
 * IMAGENES
 */

if (!defined('IMG_TAM_LG'))
    define('IMG_TAM_LG', '1200');
if (!defined('IMG_TAM_MD'))
    define('IMG_TAM_MD', '800');
if (!defined('IMG_TAM_SM'))
    define('IMG_TAM_SM', '400');
if (!defined('IMG_TAM_XS'))
    define('IMG_TAM_XS', '140');
