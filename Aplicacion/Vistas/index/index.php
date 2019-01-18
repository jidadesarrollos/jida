<section class="big-slider">
    <div class="owl-carousel owl-theme owl-lol" id="owl-demo">
        <div class="item slide-1">
            <div class="container">
                <div class="title-lol">
                    <h2>Bodas</h2>
                </div>
            </div>
        </div>
        <div class="item slide-2">
            <div class="container">
                <div class="title-lol">
                    <h2>Retratos</h2>
                </div>
            </div>
        </div>
        <div class="item slide-3">
            <div class="container">
                <div class="title-lol">
                    <h2>Eventos</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content-lol">
    <div class="container">
        <main>
            <h5>Soy un apasionado de la fotografía</h5>
            <div class="row">
                <?php foreach ($this->galeria as $medio): ?>
                    <div class="col-sm-4 no-pad-right clear">
                        <div class="grid mar-top-0 mar-btm-15">
                            <figure class="effect-julia">
                                <img alt="img10" class="img-full" src="<?= $medio['imagen'] ?>">
                                <figcaption>
                                    <h3><?= $medio['proyecto'] ?></h3>
                                    <p><?= $medio['categoria'] ?></p>
                                    <a href="/">Ver más</a>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</section>