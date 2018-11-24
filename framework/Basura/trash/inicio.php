<?php
/**
 *
 * Archivo de arranque de la aplicacion
 * @since 0.1
 * @author <jrodriguez@jidadesarrollos.com>
 *
 */


if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

if (!file_exists($ruta . '/Config/Base.php')) {

    include_once 'plantillas/error/errorConfig.php';
    exit;
}




if (class_exists('\Jida\Manager\Manager')) {

    $manager = new \Jida\Manager\Manager();
    $manager->inicio();

}
else {
    include_once 'plantillas/error/errorConfig.php';
    exit;
}