<header>
    <div class="bs-example bs-navbar-top-example" data-example-id="navbar-fixed-to-top">
        <nav class="navbar navbar-default navbar-fixed-top navbar-lool">
            <!--We use the fluid option here to avoid overriding the fixed width of a normal container within the narrow content columns.-->
            <div class="container">
                <div class="navbar-header">
                    <button aria-expanded="false" class="navbar-toggle navbar-lool-toggle collapsed"
                            data-target="#bs-example-navbar-collapse-6"
                            data-toggle="collapse" type="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">
                        <img class="ancho-logo" src="<?= $this->urlTema ?>/htdocs/images/LOGO1-01.png">
                    </a>
                </div>
                <div class="navbar-collapse navbar-collapse-lool"
                     id="bs-example-navbar-collapse-6">
                    <?= $this->menu ?>
                </div>
                <!--/.navbar-collapse-->
            </div>
        </nav>
    </div>
</header>