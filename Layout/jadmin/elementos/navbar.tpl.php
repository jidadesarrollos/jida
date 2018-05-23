<?php


$usuario = is_object($this->usuario) ? $this->usuario->nombres . ' ' . $this->usuario->apellidos : '';
$conf = \Jida\Configuracion\Config::obtener();
$url = $conf::URL_BASE;

?>

<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="/" class="navbar-brand" target="_blank">
                <?php if (defined('LOGO_APP')): ?>
                    <img src="<?= $conf::LOGO_APP ?>" alt="<?= $conf::NOMBRE_APP ?>" class="logo-admin top-nav"/>
                <?php else: ?>
                    <?= $conf::NOMBRE_APP ?>
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
                        <i class="fa fa-user"></i>
                        <?= $usuario ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a data-modal=true href="<?= $url ?>jadmin/users/cambio-clave">
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