<?php
/**
 * Archivo de arranque de la aplicacion
 */

use \Jida\Manager\Estructura as Estructura;

if (file_exists($ruta . '/vendor/autoload.php')) {
    require_once $ruta . '/vendor/autoload.php';
}
if (!file_exists($ruta . '/Config/Base.php')) {

    include_once 'plantillas/error/errorConfig.php';
    exit;
}

include_once $ruta . '/Config/Base.php';

if (class_exists('\Jida\Manager\Manager')) {

    $manager = new \Jida\Manager\Manager();
    $manager->inicio();

}
else {
    include_once 'plantillas/error/errorConfig.php';
    exit;
}
