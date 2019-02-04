<section class="card mb-2">
    <div class="card-body">
        <h4 class="card-title">Subir imagenes al proyecto "<?= $this->nombre ?>"</h4>
        <p>
            <button type="button"
                    class="btn btn-primary my-2"
                    data-url-envio=<?= $this->urlEnvio ?>
                    id="btnCargaImagen">
                <span class="fa fa-camera-retro"></span>
                Subir Imagen
            </button>
        </p>
        <?= \Jida\Medios\Mensajes::imprimirMsjSesion() ?>
    </div>
</section>

<div class="album p-3 bg-light">

    <div class="row jida-galeria-media">
        <?php foreach ($this->media as $key => $item) :
            $parametros = $item['id_media_proyecto'];
            ?>
            <section class="col-md-4">
                <figure class="card mb-4 shadow-sm item"
                        data-id="<?= $item['id_media_proyecto'] ?>"
                        data-imagen="<?= $item['url']['original']; ?>">
                    <img src="<?= $item['url']['400x400'] ?>" class="img-fluid"/>
                    <figcaption class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a data-accion="gestion" class="btn btn-sm btn-outline-secondary">
                                <span class="fa fa-edit"
                                      data-toggle="tooltip"
                                      data-placement="top"
                                      title="Editar"></span>
                                </a>
                                <a data-accion="eliminar" class="btn btn-sm btn-outline-secondary">
                                <span class="fa fa-trash"
                                      data-toggle="tooltip"
                                      data-placement="top"
                                      title="Eliminar"></span>
                                </a>
                            </div>
                        </div>
                    </figcaption>
                </figure>
            </section>
        <?php endforeach; ?>
    </div>

</div>

<script type="mostache-script" id="imgTemplate">

	<section class="col-md-4">
        <figure class="card mb-4 shadow-sm" data-imagen="{{img}}"
             data-parametros="{{parametros}}">
            <img src="{{src}}" alt="{{alt}}" class="img-fluid">
            <figcaption class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <a data-accion="editar"  class="btn btn-sm btn-outline-secondary">
                        <span class="fa fa-edit"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Editar"></span>
                        </a>
                        <a data-accion="eliminar" class="btn btn-sm btn-outline-secondary">
                        <span class="fa fa-trash"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Eliminar"></span>
                        </a>
                    </div>
                </div>
            </figcaption>
        </figure>
    </section>





</script>
