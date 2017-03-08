<div class="row">
	<div class="col-md-12">
		<header class="page-header">
			<h1>
				Galeria
				<small>Gesti&oacute;n de Imagenes</small>
			</h1>
		</header>
	</div>
	<div class="col-md-12 col-xs-12">
		<ul class="list-inline jida-galeria-media">
			
			<?php if ($this->objetosGaleria): ?>
				<?php 
				foreach ($this->objetosGaleria as $key => $obj): 
					$dataObj = json_decode($obj['meta_data']);	
					$src = URL_IMGS . $obj['directorio'] .'/'. $dataObj->sm;
				?>
			<li>
				<figure data-galeria="<?=$obj['id_objeto_media']?>">
					<img src="<?=$src?>" alt="" />
					<figcaption></figcaption>
				</figure>
			</li>		
				<?php endforeach ?>
			
			<?php endif ?>
			<li>
				<figure class="selected">
					<img src="/Framework/htdocs/img/dummy.png" alt="" />
					<figcaption></figcaption>
				</figure>
			</li>
		</ul>
	</div>
		
	
</div>
<div class="row">
	<div class="col-md-12">
		<div class="btn-seccion-panel">
			<button class="btn btn-default" id="btnCargaFile">Cargar</button>
		</div>
	</div>
</div>
<script type="mostache-script" id="mediaTemplate">
	<li>
		<figure class="{{class}}">
			<img src="{{src}}" alt="{{alt}}" />
			<figcaption></figcaption>
		</figure>
	</li>
</script>