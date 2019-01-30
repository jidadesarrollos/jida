<section class="jumbotron text-center">
    <div class="container">
        <h1 class="jumbotron-heading">Subir imagenes al proyecto "<?= $this->nombre ?>"</h1>
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

<div class="album py-5 bg-light">
    <div class="container">
        <div class="row jida-galeria-media" id="lista-imagenes">
            <?php

            foreach ($this->contenidos as $key => $contenido) :
                $imagen = json_decode($contenido['meta_data']);
                $parametros = $contenido['id_objeto_media'];
                ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm" data-imagen="<?= $imagen->md; ?>"
                         data-parametros="<?= $parametros ?>">
                        <img src="<?= $this->url . $imagen->sm ?>" class="img-fluid">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a data-modal data-href="/contenidos/" class="btn btn-sm btn-outline-secondary">
                                    <span class="fa fa-edit"
                                          data-toggle="tooltip"
                                          data-placement="top"
                                          title="Editar"></span>
                                    </a>
                                    <a data-accion="eliminar" data-href="/contenidos/eliminar/"
                                       class="btn btn-sm btn-outline-secondary">
                                    <span class="fa fa-trash"
                                          data-toggle="tooltip"
                                          data-placement="top"
                                          title="Eliminar"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<script type="mostache-script" id="imgTemplate">

	<div class="col-md-4">
        <div class="card mb-4 shadow-sm" data-imagen="{{img}}"
             data-parametros="{{parametros}}">
            <img src="{{src}}" alt="{{alt}}" class="img-fluid">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <a data-modal data-href="/contenidos/" class="btn btn-sm btn-outline-secondary">
                        <span class="fa fa-edit"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Editar"></span>
                        </a>
                        <a data-accion="eliminar" data-href="/contenidos/eliminar/"
                           class="btn btn-sm btn-outline-secondary">
                        <span class="fa fa-trash"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Eliminar"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</script>
