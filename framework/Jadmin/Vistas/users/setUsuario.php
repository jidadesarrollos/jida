
<h1 id="tituloForm"><?=$this->tituloForm?></h1>
<form action="<?=$this->action?>" method="POST" enctype="application/x-www-form-urlencoded">
    
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->form['nombre_usuario']->label->render()?>
        <?=$this->form['nombre_usuario']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->form['nombres']->label->render()?>
        <?=$this->form['nombres']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->form['apellidos']->label->render()?>
        <?=$this->form['apellidos']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->form['correo']->label->render()?>
        <?=$this->form['correo']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->form['id_estatus']->label->render()?>
        <?=$this->form['id_estatus']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->form['clave_usuario']->label->render()?>
        <?=$this->form['clave_usuario']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->formPerfiles['perfil']->label->render()?>
        <?=$this->formPerfiles['perfil']->render()?>
    </div>
  </div>
  
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$this->form['id_usuario']->render()?>
    </div>
  </div>
    
  <div class="form-group">
      <div class="col-lg-6 col-md-6 col-md-12 col-xs-12"><hr>
          <input type="submit" name="btnRegistroUsuarios" id="btnRegistroUsuarios" value="<?=$this->valueBotonForm?>" class="btn btn-primary pull-right">
      </div>
  </div>
  
</form>