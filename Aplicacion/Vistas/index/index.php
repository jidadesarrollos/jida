<section class="big-slider">
    <div class="owl-carousel owl-theme owl-lol" id="owl-demo">
        <?php foreach ($this->slider as $slide): ?>
            <div class="item" style="background: url('<?= $slide['imagen'] ?>') center no-repeat; background-size: cover;">
                <div class="container">
                    <div class="title-lol">
                        <h2><?= $slide['titulo'] ?></h2>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<section class="content-lol">
    <div class="container">
        <main>
            <h5>Soy un apasionado de la fotografía</h5>
            <div class="row">
                <?php foreach ($this->proyectos as $medio): ?>
                    <div class="col-sm-4 pad-global">
                        <div class="grid mar-top-0 mar-btm-15">
                            <figure class="effect-julia">
                                <img alt="img10" class="img-full" src="<?= $medio['imagen'] ?>">
                                <figcaption>
                                    <h3><?= $medio['proyecto'] ?></h3>
                                    <p><?= $medio['categoria'] ?></p>
                                    <a href="/detalle/<?= $medio['id_proyecto'] ?>">Ver más</a>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="scroll">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="text-center mar-top-20">
                            <a class="btn-lool btn-black-square" href="image-load.html">Ver Más</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>