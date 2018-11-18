<?php
/**
 * Archivo Vista
 * @category Jida - view
 */
$data =& $this->data;
?>

<form action="<?= $this->url ?>" method="post">
    <fieldset>

        <legend>Tablas Encontradas</legend>

        <div class="col-md-4">
            <div class="checkbox">
                <input type="checkbox" data-selectall=".seleccionables">
                <label for="checkbox1">
                    Seleccionar todos
                </label>
            </div>
        </div>
        <div class="col-md-8 text-right">
            <b>Total tablas : <?= count($this->tablas) ?> </b>
        </div>

        <hr/>
        <?= Jida\Helpers\Mensajes::imprimirMsjSesion() ?>
        <?php foreach ($this->tablas as $key => $tabla): ?>
            <div class="col-md-3 col-sm-4">
                <div class="checkbox">
                    <input type="checkbox" class="seleccionables" id="tablas_bd_<?= $tabla['table_name'] ?>"
                           name="tablas_bd[]" value="<?= $tabla['table_name'] ?>">
                    <label for="checkbox1">
                        <?= $tabla['table_name'] ?>
                    </label>
                </div>
            </div>
        <?php endforeach ?>
    </fieldset>
    <div class="row">
        <div class="col-md-6">
            <section class="form-group">
                <label for="prefijos">Agrega los prefijos que deseas ignorar.</label>
                <input type="text" name="txtPrefijos" id="txtPrefijos" class="form-control"/>
            </section>
        </div>
        <div class="col-md-6">
            <?= Jida\Helpers\Mensajes::crear('info',
                                             'Si deseas ignorar algunos prefijo de base de datos puedes agregarlos separandolos por coma (,)') ?>
        </div>
    </div>
    <section class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
            <button class="btn btn-default" id="btnCrearModelos" name="btnCrearModelos" type="submit" value="TRUE">Crear
                Modelos
            </button>
        </div>
    </section>


</form>