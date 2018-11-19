<?php

use Jida\Helpers\Sesion as Sesion;

?>

<form action="<?= $this->urlForm ?>" method="post" accept-charset="utf-8" id="formInstagram">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1>Conexi&oacute;n con API Instagram</h1>
            <?php if (Sesion::obt('__msjForm')):
                echo Sesion::obt('__msjForm');
                Sesion::destroy('__msjForm');
            endif;
            ?>
            <div class="alert alert-info">
                <p>
                    Autenticar cuenta de <strong><?= $this->nombreApp ?></strong> para la API de Instagram.
                </p>
                Para activar esta opci&oacute;n te enviaremos a un enlace donde
                debes aceptar los terminos de Instagram para poder
                acceder a la informaci&oacute;n de su cuenta.

            </div>
            <div class="row">
                <div class="col-md-12 top-15">
                    <input id="btnPermisosInstagram"
                           name="btnPermisosInstagram"
                           type="submit"
                           value="Autenticar"
                           data-jida="validador"
                           class="btn btn-primary pull-right"
                    />
                </div>
            </div>
        </div>
    </div>
</form>