<div class="main-header">

    <div class="logo">
        <a href="<?= $this->urlBase ?>" target="_blank">
            <img src="<?= $this->logo() ?>" alt="logo">
        </a>
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
            <?php if (is_null($this->imgPerfil)): ?>
                <i class="i-Administrator text-muted header-icon" id="dropdownMenuButton" data-toggle="dropdown"
                   aria-haspopup="true" role="button" aria-expanded="false"></i>
            <?php else: ?>
                <img src="<?= $this->imgPerfil ?>" class="profile" id="dropdownMenuButton" data-toggle="dropdown"
                     aria-haspopup="true" role="button" aria-expanded="false" width="36px">
            <?php endif; ?>

            <div class="user col align-self-end">
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <div class="dropdown-header">
                        <i class="i-Lock-User mr-1"></i>

                        <?= \Jida\Medios\Sesion::$usuario->nombre() ?>
                    </div>
                    <a class="dropdown-item" href="<?= $this->urlBase ?>/jadmin/usuario/cambioclave">Cambiar clave</a>
                    <a class="dropdown-item" href="<?= $this->urlBase ?>/jadmin/usuario/mi-perfil">Modificar Perfil</a>
                    <a class="dropdown-item" href="<?= $this->urlBase ?>/jadmin/logout">Salir</a>
                </div>
            </div>
        </div>
    </div>

</div>

<!--=============== Left side End ================-->