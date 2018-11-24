<?PHP

?>
    <style>
        input[type=text] {
            width: 100%;
        }

        textarea {
            width: 100%;
            max-height: 550px;
            min-height: 200px;

        }
    </style>
    <h1>Procesar Formularios</h1>
    <div class="row">
        <div class="col-lg-12 col-ms-12 col-xs-12">
            <p class="bg-success">Total Campos del formulario <strong
                        class="label label-primary"><?= $this->totalCampos ?></strong></p>

        </div>
    </div>
<?= $this->formulario ?>