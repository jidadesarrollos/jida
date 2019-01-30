<section class="portfolio pull-top-header">
    <div class="container">
        <main>
            <h2>
                Galería
            </h2>
            <hr>
            <div class="row row-global animated fadeIn">
                <?php foreach ($this->proyectos as $medio): ?>
                    <div class="col-sm-4 pad-global animated">
                        <div class="grid mar-top-15">
                            <figure class="effect-julia">
                                <img alt="" class="img-full" src="<?= $medio['imagen'] ?>">
                                <figcaption>
                                    <h3><?= $medio['proyecto'] ?></h3>
                                    <p><?= $medio['categoria'] ?></p>
                                    <a href="/detalle/<?= $medio['id_proyecto'] ?>"> Ver más</a>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</section>