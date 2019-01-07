<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->nombreApp ?></title>
    <?= $this->imprimirLibrerias('head', 'principal') ?>
</head>

<body class="adminbody">

<div id="main">

    <?php

    //todo: cambiar por metodo para incluir layout
    include 'elementos/header.php';
    ?>

    <!-- Left Sidebar -->
    <div class="left main-sidebar">

        <div class="sidebar-inner leftscroll">

            <div id="sidebar-menu">

                <?= $this->menu ?>

                <div class="clearfix"></div>

            </div>

            <div class="clearfix"></div>

        </div>

    </div>
    <!-- End Sidebar -->

    <div class="content-page">

        <!-- Start content -->
        <div class="content">

            <div class="container-fluid">

                <?= $contenido ?>

            </div>
            <!-- END container-fluid -->

        </div>
        <!-- END content -->

    </div>
    <!-- END content-page -->

    <?php

    //todo:cambiar por metodo para incluir layout
    include 'elementos/footer.php';
    ?>

</div>
<!-- END main -->

<?= $this->imprimirLibrerias('js', 'principal') ?>

</body>
</html>