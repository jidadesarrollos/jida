<div class="row">
    <div class="col-md-12">
        <header class="page-header">
            <h1>
                Galeria
                <small>Gesti&oacute;n de Imagenes</small>
            </h1>
        </header>
    </div>
    <div class="col-md-12 col-xs-12">
        <ul class="list-inline jida-galeria-media">
            <?php if ($this->objetosGaleria): ?>


            <?php if ($this->objetosGaleria): ?>
                <?php
                foreach ($this->objetosGaleria as $key => $obj):
                    $dataObj = json_decode($obj['meta_data']);
                    $src = URL_IMGS . $obj['directorio'] . '/' . $dataObj->sm;
                    ?>
                    <li>
                        <figure data-galeria="<?= $obj['id_objeto_media'] ?>">
                            <img src="<?= $src ?>" alt=""/>
                            <figcaption></figcaption>
                        </figure>
                    </li>
                <?php endforeach ?>

            <?php endif ?>

        </ul>
        <?php else: ?>
            </ul>
            <h4 id="mensajeNoRegistros">No Hay imagenes cargadas</h4>
        <?php endif ?>

    </div>


</div>
<div class="row">
    <div class="col-md-12">
        <div class="btn-seccion-panel">
            <button class="btn btn-default" id="btnCargaFile">Cargar Imagen</button>
        </div>
    </div>
</div>

<script type="mostache-script" id="mediaTemplate">
	<li>
		<figure data-galeria="{{id}}" class="{{class}}">
			<img src="{{src}}" alt="{{alt}}" />
			<figcaption></figcaption>
		</figure>
	</li>

</script>