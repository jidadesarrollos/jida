<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?= $this->imprimirMeta(); ?>
    <?= $this->imprimirLibrerias('css','principal'); ?>
</head>
<body class="demo-1">
<div class="ip-container" id="ip-container">
    <?= $this->incluirLayout('elementos/header-preloader') ?>
    <div class="ip-main">
        <?= $this->incluirLayout('elementos/header') ?>
        <?= $contenido ?>
    </div>

</div>
<?= $this->imprimirLibrerias('js','principal'); ?>
</body>
</html>