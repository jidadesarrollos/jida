<?php
/**
 * Archivo Vista
 * @category Jida - view
 */
$data =& $this->data;
?>
<h1>Bienvenido a la configuraci&oacute;n del Jida <br />
	<small>
		Solo debes realizar unos cortos pasos y estaremos listos
	</small></h1>
<form action="/<?= $this->url?>" method="post" class="row" >
	<?=Mensajes::imprimirMensaje('__msjForm')?>
    <fieldset class="col-md-6 col-md-offset-3" id="formBdConfig">
    	<legend>Configuracion de base de datos</legend>
    	<div class="form-group">
    		<label for="servidor">Servidor</label>
    		<input class="form-control" type="text" id="servidor" name="servidor" />
    	</div>
    	<div class="form-group">
    		<label for="servidor">Base de datos</label>
    		<input class="form-control" type="text" id="bd" name="bd" />
    	</div>
    	
    	<div class="form-group">
    		<label for="servidor">Usuario</label>
    		<input class="form-control" type="text" id="usuario_bd" name="usuario_bd" />
    	</div>
    	<div class="form-group">
    		<label for="servidor">Clave</label>
    		<input class="form-control" type="password" id="clave_bd" name="clave_bd" />
    	</div>
    	<div class="form-group">
	   
	  </div>
	</fieldset>    
    <div class="row">
    	 <div class="col-md-6 col-md-offset-3 text-right">
	      <button type="submit" value="true" class="btn btn-default" id="btnBdConfig" name="btnBdConfig">Continuar</button>
	    </div>
    </div>
    
</form>