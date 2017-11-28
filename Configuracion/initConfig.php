<?php
/**
 * Archivo Inicial de configuracion de la Aplicacion
 * El InitConfig es usado para definir todas aquellas variables globales o constantes que
 * solo sean utilizadas en un ambiente especifico (como desearrollo, calidad o producción), esto facilita
 * agrupar en un solo archivo todo lo que no desea ser pasado de un ambiente a otro.
 */

define('ENTORNO_APP', 'dev');

define('NOMBRE_APP', '');
define('URL_APP_PUBLICA', '/');

/**
 * @constante MANEJADOR_BD y manejadorBD para Manejador de Base de datos utilizado en el sistema
 */
define('manejadorBD', 'MySQL');
define('MANEJADOR_BD', 'MySQL');

/**
 * Constantes para Redes Sociales
 */
define('URL_APP_PUBLICA', '');
define('URL_GOOGLE', '');
define('URL_YOUTUBE', '');
define('URL_TWITTER', '');
define('URL_FACEBOOK', '');
define('URL_INSTAGRAM', '');

define('CUENTA_TWITTER', '');
define('CUENTA_INSTAGRAM', '');


# - img size
define('IMG_TAM_LG', '1600');
define('IMG_TAM_MD', '720');
define('IMG_TAM_SM', '350');
define('IMG_TAM_XS', '140');