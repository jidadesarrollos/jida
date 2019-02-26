<!-- top bar navigation -->
<div class="headerbar">

    <!-- LOGO -->
    <div class="headerbar-left">
        <a href="<?= $this->urlBase ?>/jadmin" class="logo">
            <img src="<?= $this->urlBase ?>/htdocs/img/logo_corto.png" alt="<?= $this->nombreApp ?>" class="img-fluid">
            <span> <?= $this->nombreCorto ?></span>
        </a>
    </div>

    <nav class="navbar-custom">

        <ul class="list-inline float-right mb-0">

            <li class="list-inline-item dropdown notif">
                <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <?= \Jida\Medios\Sesion::$usuario->nombre() ?> <i class="fa fa-cog"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">

                    <!-- item-->
                    <a href="<?= $this->urlBase ?>/jadmin/usuario/cambioclave" class="dropdown-item notify-item">
                        <i class="fa fa-cog"></i> <span>Cambiar clave</span>
                    </a>

                    <!-- item-->
                    <a href="<?= $this->urlBase ?>/jadmin/logout" class="dropdown-item notify-item">
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