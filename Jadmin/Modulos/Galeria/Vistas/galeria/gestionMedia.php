<?php
$src = $this->obj->directorio.'/'. $this->obj->data('md');
?>
<div class="container jida-container galeria">
	<div class="row">
		<div class="col-md-12">
			<header class="page-header jida-header">
				<h1>Gesti&oacute;n Objeto</h1>
			</header>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-sm-8 col-xs-12 text-center">
			<figure class='objeto-media objeto-media-detalle'>
				<img src="<?=$src?>" alt="" />
				<figcaption></figcaption>
			</figure>
		</div>
		<div class="col-sm-4 col-md-4 col-xs-12">
			<?=$this->form?>
		</div>
	</div>
</div>