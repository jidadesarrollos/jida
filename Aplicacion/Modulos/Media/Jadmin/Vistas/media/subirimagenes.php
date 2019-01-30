<div class="card">
    <h4 class="card-header">Subir Imagenes al Proyecto "<?= $this->nombre ?>"</h4>
    <div class="card-body">

        <header class="row">
            <div class="col-md-12">
                <div>
                    Total Imagenes
                    <span><?= $this->totalImagenes ?></span>
                    <span class="bg-notice text-notice text-center" id="mensaje-carga"></span>
                </div>

                <button type="button"
                        class="pull-right btn btn-primary"
                        data-url-envio=<?= $this->urlEnvio ?>
                        id="btnCargaImagen">
                    <span class="fa fa-camera-retro"></span>
                    Subir Imagen
                </button>
            </div>
            <?= \Jida\Medios\Mensajes::imprimirMsjSesion() ?>
        </header>
        <section class="row">
            <div class="col-md-12">
                <ul id="lista-imagenes"
                    class="jida-galeria-media list-unstyled row grid">
                    <?php

                    foreach ($this->contenidos as $key => $contenido) :
                        $imagen = json_decode($contenido['meta_data']);
                        $parametros = $contenido['id_objeto_media'];
                        ?>

                        <li data-imagen="<?= $imagen->md; ?>"
                            data-parametros="<?= $parametros ?>"
                            class="img-contenido col-md-3 col-sm-3 col-xs-12 grid-item">

                            <figure class="img-container-contenido img-delete">
                                <img src="<?= $this->url . $imagen->sm ?>" width="120px"/>
                                <figcaption>
                                    <span class="content-options">
                                        <a data-modal data-href="/contenidos/" class="btn">
                                            <span class="fa fa-edit"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="Editar"></span>
                                        </a>
                                        <a data-accion="eliminar" data-href="/contenidos/eliminar/" class="btn">
                                            <span class="fa fa-trash"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="Eliminar"></span>
                                        </a>
                                    </span>
                                </figcaption>
                            </figure>
                        </li>
                    <?PHP endforeach; ?>
                </ul>
            </div>
        </section>
    </div>
</div>


<script type="mostache-script" id="imgTemplate">
	<li data-imagen="{{img}}"
	    data-parametros="{{parametros}}"
	    class="jcargafile img-contenido col-md-3 col-sm-3 col-xs-12 grid-item">
		<figure class="img-container-contenido img-delete">
			<img src="{{src}}" alt="{{alt}}" />
			<figcaption>
				<span class="content-options">
                	<a data-modal data-href="/contenidos/" class="btn">
                		<span class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Editar"></span>
                	</a>
                	<a data-accion="eliminar" data-href="/contenidos/eliminar/" class="btn">
                		<span class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Eliminar" ></span>
                	</a>
                </span>
			</figcaption>
		</figure>
	</li>








</script>
