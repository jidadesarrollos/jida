<div class="main-header">
    <div class="logo">
        <img src="<?= $this->urlBase ?>/htdocs/img/logo.png" alt="">
    </div>

    <div class="menu-toggle">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div style="margin: auto"></div>

    <div class="header-part-right">
        <!-- Full screen toggle -->
        <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>
        <!-- User avatar dropdown -->
        <div class="dropdown">
            <i class="i-Administrator text-muted header-icon" id="dropdownMenuButton" data-toggle="dropdown"
               aria-haspopup="true" role="button" aria-expanded="false"></i>
            <div class="user col align-self-end">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <div class="dropdown-header">
                        <i class="i-Lock-User mr-1"></i> <?= \Jida\Medios\Sesion::$usuario->nombre() ?>
                    </div>
                    <a class="dropdown-item" href="<?= $this->urlBase ?>/jadmin/usuario/cambioclave">Cambiar clave</a>
                    <a class="dropdown-item" href="<?= $this->urlBase ?>/jadmin/logout">Salir</a>
                </div>
            </div>
        </div>
    </div>

</div>

<!--=============== Left side End ================-->