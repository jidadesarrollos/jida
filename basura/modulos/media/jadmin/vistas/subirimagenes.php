<div class="card">
    <h4 class="card-header">Subir Imagenes al Proyecto "<?= $this->nombre ?>"</h4>
    <div class="card-body">
        <?= \Jida\Medios\Mensajes::imprimirMsjSesion() ?>
        <form method="post" action="/jadmin/media/subir-imagenes/<?= $this->idFk ?>" enctype="multipart/form-data"
              multiple>

            <div class="form-group">

                <label for="imagen">Fotografia</label>
                <p>Selecciona el conjunto de imagenes que desees subir al servidor.</p>
                <input id="imagen" name="imagenes[]" type="file" class="form-control" multiple accept="image/*"/>

            </div>

            <div class="form-group">

                <input name="id_medias" type="hidden" value="<?= (isset($this->id)) ? $this->id : "" ?>"/>

            </div>

            <div class="form-group">

                <input name="btnMedias" class="btn btn-primary" type="submit" value="Enviar">

            </div>

        </form>
        <div class="clear"></div>
    </div>
</div>
