<?php
if(URL_BASE == ''){
    $url = '/'   ;
}else{
    $url = URL_BASE;
}

$usuario = is_object($this->usuario) ? $this->usuario->nombres : '';
?>
<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="#" class="navbar-brand">
                <?php if (defined('LOGO_APP')): ?>
                    <img src="<?= LOGO_APP ?>" alt="<?= NOMBRE_APP ?>" class="logo-admin top-nav"/>
                <?php else: ?>
                    <?= NOMBRE_APP ?>
                <?php endif ?>

            </a>
        </div>
        <section class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <?=$usuario?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= $url ?>jadmin/users/cambio-clave">
                                Cambiar de Clave
                            </a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?= $url ?>jadmin/users/cierresesion">
                                Cerrar Sesi&oacute;n
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </section>
    </div>

</nav>