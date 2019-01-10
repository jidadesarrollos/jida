<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">


    <?= $this->imprimirHead('css') ?>

</head>
<body class="demo-1">
<div class="ip-container" id="ip-container">
    <?php $this->incluirLayout('elementos/header-preloader') ?>
    <div class="ip-main">
        <?php $this->incluirLayout('elementos/header') ?>
        <?= $contenido ?>
    </div>
</div>
<?= $this->imprimirLibrerias('js') ?>

</body>
</html>