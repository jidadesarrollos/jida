<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left">
            <li class="nav-item">
                <a class="nav-item-hold" href="<?= $this->urlBase ?>/jadmin/">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="nav-text">Inicio</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item">
                <a class="nav-item-hold" href="<?= $this->urlBase ?>/jadmin/actividades/">
                    <i class="nav-icon i-Suitcase"></i>
                    <span class="nav-text">Actividades</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item">
                <a class="nav-item-hold" href="<?= $this->urlBase ?>/jadmin/centros-costos/">
                    <i class="nav-icon i-Computer-Secure"></i>
                    <span class="nav-text">Centros de Costos</span>
                </a>
                <div class="triangle"></div>
            </li>
            <li class="nav-item" data-item="ajustes">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-File-Clipboard-File--Text"></i>
                    <span class="nav-text">Ajustes</span>
                </a>
                <div class="triangle"></div>
            </li>
        </ul>
    </div>


    <div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <!-- Submenu Dashboards -->
        <ul class="childNav" data-parent="ajustes">
            <li class="nav-item">
                <a href="<?= $this->urlBase ?>/jadmin/usuarios/">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">Usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->urlBase ?>/jadmin/departamentos/">
                    <i class="nav-icon i-Clock-4"></i>
                    <span class="item-name">Departamentos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= $this->urlBase ?>/jadmin/cargos/">
                    <i class="nav-icon i-Over-Time"></i>
                    <span class="item-name">Cargos</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-overlay"></div>
</div>
