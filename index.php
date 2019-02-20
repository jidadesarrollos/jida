<?php

$ruta = 'Framework';
define('DIR_JF', $ruta);

include_once 'vendor/autoload.php';

if (file_exists($ruta . '/vendor/autoload.php')) {
    require_once $ruta . '/vendor/autoload.php';
}

$jida = new Jida\Manager($ruta, __DIR__);
$jida->inicio();
