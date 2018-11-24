<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadmin</title>
    <?= $this->imprimirLibrerias('css') ?>
</head>

<body>

<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">

        <?= $contenido ?>

    </div>
</div>

<?php

//todo:cambiar por metodo para incluir layout
include 'elementos/footer.php';
?>

<?= $this->imprimirLibrerias('js') ?>

</body>
</html>