<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <?= $this->imprimirLibrerias('head', 'principal') ?>

</head>
<body class="demo-1">
<div class="ip-container" id="ip-container">
    <?php $this->incluir('elementos/header-preloader') ?>
    <div class="ip-main">
        <?php $this->incluir('elementos/header') ?>
        <?= $contenido ?>
        <?php $this->incluir('elementos/footer') ?>
    </div>
</div>
<?= $this->imprimirLibrerias('js', 'principal') ?>

</body>
</html>