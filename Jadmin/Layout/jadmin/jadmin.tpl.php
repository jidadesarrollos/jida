<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->nombreApp ?></title>
    <?= $this->imprimirLibrerias('head', 'principal') ?>
    <?php $this->incluir('jida-js') ?>
</head>

<body class="adminbody">
<div id="main">

    <?php include 'elementos/header.php'; ?>

    <!-- Left Sidebar -->
    <aside class="left main-sidebar">
        <div class="sidebar-inner leftscroll">
            <div id="sidebar-menu">
                <?= $this->menu ?>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>

    </aside>
    <!-- End Sidebar -->
    <main class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid mt-4">
                <?= $contenido ?>
            </div>
            <!-- END container-fluid -->
        </div>
        <!-- END content -->
    </main>
    <!-- END content-page -->
    <?php include 'elementos/footer.php'; ?>

</div>
<?= $this->imprimirLibrerias('js', 'principal') ?>

</body>
</html>