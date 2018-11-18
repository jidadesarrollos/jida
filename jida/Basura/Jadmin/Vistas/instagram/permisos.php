<form action="<?= $this->urlForm ?>" method="post" accept-charset="utf-8" id="formPermisosInstagram">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <h1>Solicitud de Permisos Instagram</h1>

            <div class="alert alert-info">
                Acepto los terminos y condiciones para acceder y mostrar la informaci&oacute;n de la cuenta de Instagram
                en <?= $this->nombreApp ?>.
            </div>

            <div class="row">
                <input type="hidden" name="codigo" id="codigo" value="<?= $this->codigo ?>"/>
                <div class="col-md-12 top-15">
                    <input type="submit"
                           value="Acepto"
                           data-jida="validador"
                           class="btn btn-primary pull-right"
                           name="btnPermisosInstagram"/>
                </div>
            </div>

        </div>
    </div>
</form>