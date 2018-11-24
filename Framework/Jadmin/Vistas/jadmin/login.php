<div class="card">
    <h4 class="card-header">Iniciar sesión</h4>

    <div class="card-body">

        <div class="alert alert-success" role="alert">

        </div>


        <form data-toggle="validator" role="form" method="post" action="">

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Usuario</label>
                        <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"
                                                                       aria-hidden="true"></i></span>
                            <input type="email" class="form-control" name="login_email"
                                   data-error="Input valid email" required>
                        </div>
                        <div class="help-block with-errors text-danger"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Clave</label>
                        <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-unlock" aria-hidden="true"></i>
                                    </span>
                            <input type="password" id="inputPassword" data-minlength="6" name="login_password"
                                   class="form-control" data-error="Password to short" required/>
                        </div>
                        <div class="help-block with-errors text-danger"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" name="redirect" value=""/>
                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="Iniciar sesión" name="submit"/>
                </div>
            </div>
        </form>

        <div class="clear"></div>

    </div>

</div>
