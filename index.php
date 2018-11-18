<?php

$ruta = "./vendor/Jida/";
$ruta = "./Framework/";

define('DIR_JF', $ruta);

if (!file_exists($ruta . 'inicio.php')) {
    exit("No se encuentra el directorio de Jida para inicializar la aplicación :" . $ruta);
}

$archivo = $ruta . 'inicio.php';

include_once $archivo;


$path = \Jida\Manager\Estructura::path();

include_once $path . '/Aplicacion/index.php';
