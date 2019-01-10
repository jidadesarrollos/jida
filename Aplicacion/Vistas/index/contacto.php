<section class="head-pages pull-top-header">
    <div class="container">
        <main>
            <h2 class="text-center">
                Contacto
            </h2>
            <hr>
            <div class="row">
                <!--<div class="col-sm-12">
                     <div id="map-canvas"></div>
                     <h2 class="text-center">
                         Send your Message
                     </h2>
                 </div>-->
                <div class="col-sm-6">
                    <form id="form-contacto" class="form-contacto" data-toggle="validator" method="post" action="">
                        <?php

                        if (\Jida\Medios\Sesion::get('msjContacto')):
                            echo \Jida\Medios\Sesion::get('msjContacto');
                            \Jida\Medios\Sesion::destroy('msjContacto');

                        endif;
                        ?>
                        <div class="form-group">
                            <div class="div">
                                <h5 class="text-center">
                                    *Nombre Completo
                                </h5>
                            </div>
                            <input id="nombre" name="nombre" class="form-control form-lool"
                                   placeholder="Nombre Completo" type="text"
                                   required>
                        </div>
                        <div class="form-group">
                            <div class="div">
                                <h5 class="text-center">
                                    *Correo Electrónico
                                </h5>
                            </div>
                            <input id="correo" name="correo" class="form-control form-lool"
                                   placeholder="Correo Electrónico" type="email"
                                   required>
                        </div>
                        <div class="form-group">
                            <div class="div">
                                <h5 class="text-center">
                                    *Mensaje
                                </h5>
                            </div>
                            <textarea name="mensaje" id="mensaje" class="form-control form-lool" placeholder="Mensaje"
                                      required></textarea>
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
            <div class="row mar-top-15 hide">
                <div class="col-sm-12">
                    <div class="text-center">
                        <a class="btn-lool btn-black-square" href="index.html">Back To Home</a>
                    </div>
                </div>
            </div>
        </main>
        <hr>
        <?php $this->incluirLayout('../../Layout/default/elementos/footer') ?>
    </div>
</section>