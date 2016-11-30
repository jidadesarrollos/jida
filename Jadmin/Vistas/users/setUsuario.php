<?php
$data =& $this->data;
?>
<h1 id="tituloForm"><?=$data->tituloForm?></h1><form action="<?=$data->action?>" method="POST" enctype="application/x-www-form-urlencoded">
    
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->form['nombre_usuario']->label->render()?>
        <?=$data->form['nombre_usuario']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->form['nombres']->label->render()?>
        <?=$data->form['nombres']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->form['apellidos']->label->render()?>
        <?=$data->form['apellidos']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->form['correo']->label->render()?>
        <?=$data->form['correo']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->form['id_estatus']->label->render()?>
        <?=$data->form['id_estatus']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->form['clave_usuario']->label->render()?>
        <?=$data->form['clave_usuario']->render()?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->formPerfiles['perfil']->label->render()?>
        <?=$data->formPerfiles['perfil']->render()?>
    </div>
  </div>
  
  <div class="row">
    <div class="col-lg-6 col-md-6 col-md-12 col-xs-12">
        <?=$data->form['id_usuario']->render()?>
    </div>
  </div>
    
  <div class="form-group">
      <div class="col-lg-6 col-md-6 col-md-12 col-xs-12"><hr>
          <input type="submit" name="btnRegistroUsuarios" id="btnRegistroUsuarios" value="<?=$data->valueBotonForm?>" class="btn btn-primary pull-right">
      </div>
  </div>
  
</form>