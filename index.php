<?php
/**
 * Archivo Vista
 * @category Jida - view
 */
#$data =& $this->data;

require_once 'init/Init.class.php';
echo "<h1>Bienvenido a Jida Framework... <small>Espere un momento por favor.</small></h1>";
$init = new Init();
$init->inicializarJida();
?>

