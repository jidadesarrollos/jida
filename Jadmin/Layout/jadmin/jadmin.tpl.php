<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadmin</title>
    <?= $this->imprimirLibrerias('css') ?>
</head>

<body class="adminbody">

<div id="main">

    <!-- top bar navigation -->
    <div class="headerbar">

        <!-- LOGO -->
        <div class="headerbar-left">
            <a href="<?= \App\Config\Configuracion::URL_BASE ?>/jadmin" class="logo">
                <span><?= \App\Config\Configuracion::NOMBRE_APP ?></span>
            </a>
        </div>

        <nav class="navbar-custom">

            <ul class="list-inline float-right mb-0">

                <li class="list-inline-item dropdown notif">
                    <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button"
                       aria-haspopup="false" aria-expanded="false">
                        <img src="Framework/Jadmin/Layout/jadmin/htdocs/images/avatars/admin.png" alt="Profile image"
                             class="avatar-rounded">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="text-overflow">
                                <small>Hola, [usuario]</small>
                            </h5>
                        </div>

                        <!-- item-->
                        <a href="/jadmin/users/cambio-clave" class="dropdown-item notify-item">
                            <i class="fa fa-cog"></i> <span>Cambiar clave</span>
                        </a>

                        <!-- item-->
                        <a href="/jadmin/users/cierresesion" class="dropdown-item notify-item">
                            <i class="fa fa-power-off"></i> <span>Salir</span>
                        </a>

                    </div>
                </li>

            </ul>

            <ul class="list-inline menu-left mb-0">
                <li class="float-left">
                    <button class="button-menu-mobile open-left">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </li>
            </ul>

        </nav>

    </div>
    <!-- End Navigation -->

    <!-- Left Sidebar -->
    <div class="left main-sidebar">

        <div class="sidebar-inner leftscroll">

            <div id="sidebar-menu">

                <?php
                $menu = new \Jida\Render\Menu('Jadmin');
                echo $menu->render();
                ?>

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

    <footer class="footer">
        <span class="text-right">
            Copyright 2018 <a target="_blank" href="#">Jida Desarrollos</a>
        </span>
        <span class="float-right">
            Powered by <a target="_blank" href="http://www.jidadesarrollos.com"><b>Jida Desarrollos</b></a>
        </span>
    </footer>

</div>
<!-- END main -->

<?= $this->imprimirLibrerias('js') ?>

</body>
</html>