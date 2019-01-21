<section class="head-pages pull-top-header">
    <div class="container">
        <main>
            <h2 class="text-center">
                Contacto
            </h2>
            <hr>
            <div class="row">
                <div class="col-sm-6">
                    <form id="form-contacto" class="form-contacto" data-toggle="validator" method="post" action="">
                        <?php

                        if (\Jida\Medios\Sesion::obt('__msjContacto')):
                            echo \Jida\Medios\Sesion::obt('__msjContacto');
                            \Jida\Medios\Sesion::destroy('__msjContacto');

                        endif;
                        ?>
                        <div class="form-group">
                            <div class="div">
                                <h5 class="text-center">
                                    *Nombre
                                </h5>
                            </div>
                            <?= $this->form['nombre']->render() ?>
                        </div>
                        <div class="form-group">
                            <div class="div">
                                <h5 class="text-center">
                                    *Correo Electrónico
                                </h5>
                            </div>
                            <?= $this->form['correo']->render() ?>
                        </div>
                        <div class="form-group">
                            <div class="div">
                                <h5 class="text-center">
                                    *Mensaje
                                </h5>
                            </div>
                            <?= $this->form['mensaje']->render() ?>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="btnContacto" name="btnContacto" class="btn-lool btn-black-square"
                                   value="ENVIAR" data-jida="validador"
                                   style="margin-top: 10px;width: 100%;">
                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <h1>
                        Jonathan Jacobsen
                    </h1>
                    <p>
                        Póngase en contacto por teléfono, correo electrónico o mediante el siguiente formulario.
                    </p>
                </div>
            </div>
        </main>
    </div>
</section>