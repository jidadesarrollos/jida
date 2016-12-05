<?php
/**
 * Archivo Vista
 * @category Jida - view
 */
$data =& $this->data;
use Jida\Helpers as Helpers;
?>

<article id="galeria">
    <div class="seccion-titulo page-header">
        <h1>Galeria Media</h1>
    </div>

    <section class="row" id="panel-top">
        <div class="col-md-12">
            <div class="row">
                <section class="col-md-12 text-right">
                    <button id="btnCargaImagen" name="btnCargaImagen" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Agregar Imagenes al Articulo">
                            <span class="fa fa-camera-retro"></span> Nueva Imagen
                    </button>
                </section>
            </div>
            <div class="row bg-claro top-15">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <section id="galeria-media" class="content-media">

                        <?PHP if(count($data->imagenes)>0):?>

                            <ul class="list-inline lista-galeria" id="lista-imagenes" data-multiple="<?=$data->seleccionMultiple?>">

                                <?php foreach ($data->imagenes as $key => $imagen):
                                    $imagen = Helpers\Arrays::convertirAObjeto($imagen);

                                    $dataMeta = Helpers\Arrays::convertirAObjeto(json_decode($imagen->meta_data));

                                    $arrayData = [  'html'          =>  URL_IMGS.$imagen->directorio.$dataMeta->img,
                                                    'id'            =>  $imagen->id_objeto_media,
                                                    'descripcion'   =>  $imagen->descripcion,
                                                    'alt'           =>  $imagen->alt,
                                                ];
                                    $dataInfo = json_encode($arrayData);
                                ?>

                                    <li  class="ui-widget-content" data-imagen="<?=$imagen->id_objeto_media?>" data-inf='<?=$dataInfo?>'>
                                        <figure>
                                            <img src="<?=URL_IMGS.$imagen->directorio.$dataMeta->min?>"/>
                                            <figcaption></figcaption>
                                        </figure>

                                    </li>
                                <?php endforeach ?>

                            </ul>
                        <?PHP endif;?>
                    </section>
                </div>
            </div>

        </div>
        <aside class="col-md-4 col-sm-5 col-xs-12" id="data-imagen" class="content-media">
        </aside>
    </section>


    <div class="row">
        <div class="col-md-12">
            <hr/>
            <?php if (!$data->seleccionMultiple): ?>
                <button class="btn btn-primary pull-right" id="btnAddPortada">Agregar de Portada</button>
            <?php else: ?>
                <div class="btn-group  pull-right" role="group">
                    <button class="btn btn-primary" id="btnAddPortada">Agregar de Portada</button>
                    <button class="btn btn-primary" id="btnAddImg">Insertar En Publicaci&oacute;n</button>
                </div>
            <?php endif ?>



        </div>
    </div>
</article>