<?PHP
/**
 * Layout por defecto para modulo jadmin del framework
 * @author Julio Rodriguez
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?= $this->imprimirMeta(); ?>
    <?= $this->imprimirLibrerias('css', 'jadmin') ?>
</head>
<body>
<div class="jida-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-12 contenido-principal">
                <?= $contenido ?>
            </div>
        </div>
        <div class="separador-footer"></div>
    </div>
</div>
<?= $this->imprimirLibrerias('js', 'jadmin') ?>
</body>
</html>
