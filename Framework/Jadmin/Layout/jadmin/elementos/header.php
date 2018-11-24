<!-- top bar navigation -->
<div class="headerbar">

    <!-- LOGO -->
    <div class="headerbar-left">
        <a href="<?= $this->urlBase ?>/jadmin" class="logo">
            <span><?= $this->nombreApp ?></span>
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