<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->nombreApp ?></title>
    <?= $this->imprimirLibrerias('head', 'login') ?>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1>Error jadmin</h1>
            <?= $contenido ?>
        </div>
    </div>
</div>

<?= $this->imprimirLibrerias('js', 'principal') ?>

</body>
</html>