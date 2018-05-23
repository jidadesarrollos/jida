<?php
/**
 * Archivo vista de ...
 * @author Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @package
 * @category view
 * @version 1.0 //2014
 */

?>

<div class="col-md-6 col-md-offset-3 top-60">
    <div class="panel panel-login">
        <section class="panel-heading" role="title">
            <h1>
                <?php if (defined('LOGO_APP')): ?>
                    <img src="<?= $this->logo ?>" alt="<?= $this->nombreApp ?>" class="logo-admin top-nav"/>
                <?php else: ?>
                    <?= $this->nombreApp ?>
                <?php endif ?>
                <small>Administrador</small>
            </h1>
        </section>
        <section class="panel-body">

            <?= $this->formLoggin ?>
        </section>
    </div>
</div>