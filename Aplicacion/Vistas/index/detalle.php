<section class="portfolio pull-top-header">
    <div class="container">
        <main>
            <h2>
                <?= $this->proyecto->nombre ?>
            </h2>
            <div class="row">
                <div class="col-sm-12">
                    <div class="owl-carousel owl-theme" id="owl-demo-3">
                        <?php foreach ($this->galeria as $medio): ?>
                            <div class="item">
                                <img src="<?= $medio['url_media'] ?>"
                                     alt="" class="img-full">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="row mar-top-15">
                <div class="col-sm-12">
                    <div class="text-center">
                        <a class="btn-lool btn-black-square" href="/galeria">Ver todos</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>