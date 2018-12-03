<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->nombreApp ?></title>
    <?= $this->imprimirLibrerias('css', 'login') ?>
</head>

<body>

<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <?= $contenido ?>
    </div>
</div>

<?= $this->imprimirLibrerias('js', 'principal') ?>

</body>
</html>