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