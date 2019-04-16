<div class="card">
    <h4 class="card-header">Usuarios</h4>
    <div class="card-body">
        <div id="vista" class="row">
            <div class="col-12">
                <?= \Jida\Medios\Mensajes::imprimirMsjSesion() ?>
            </div>
            <div class="col-md-6">
                <?= $this->vista ?>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <p>
                        <i class="fa fa-info-circle"></i>
                        Carga tu imagen de perfil en formato <strong>JPG</strong> preferiblemente,
                        con dimensiones no mayores a 500 x 500 px.
                    </p>
                </div>
                <figure id="preview-img" class="img-preview">
                    <?php if ($this->img_perfil !== ''): ?>
                        <img src="<?= $this->img_perfil ?>" alt="">
                    <?php endif ?>
                </figure>
            </div>
        </div>
    </div>
</div>
