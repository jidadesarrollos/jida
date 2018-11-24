<?php

$ruta = 'Framework';
define('DIR_JF', $ruta);

if (file_exists($ruta . '/vendor/autoload.php')) {
    require_once $ruta . '/vendor/autoload.php';
}

$jida = new Jida\Manager($ruta);
$jida->inicio();
