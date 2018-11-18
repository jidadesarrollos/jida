<?php
$data = $this->data;
$elementosCargados =& $data->elementosCargados;
?>


<h1>Elementos</h1>
<section class="seccion seccion-areas">
<div class="row">

	<div class="col-md-12 col-xs-12 col-sm-6">
		<div class="row">
		<?php foreach ($data->areas as $key => $area): ?>
			<section class="col-md-4 col-sm-6 col-xs-12">
				<article class="area-elemento-container" id="area-<?=$area['id']?>">
					<h3 class="area-nombre" data-toggle="collapse" data-target="#body-<?=$area['id']?>" aria-expanded="false" aria-controls="body-<?=$area['id']?>">
						<?=$area['nombre']?>
					</h3>
					<section class="area-body" id="body-<?=$area['id']?>">
							<?php if (array_key_exists('descripcion',$area)): ?>
								<?=$area['descripcion']?>
							<?php endif ?>
							<?php
							if (array_key_exists($area['id'],$elementosCargados) and count($elementosCargados[$area['id']]>0)):
								$i=1;
							?>
								<?php foreach ($elementosCargados[$area['id']] as $key => $elem): ?>
									<div class="contenedor-element">
										<section class="panel-heading">
											<h4><?=$elem['elemento']?></h4>
										</section>
										<section class="panel-body">

										</section>
										<section class="panel-footer">
											<button class="btn btn-default"></button>
										</section>
									</div>
								<?php
								++$i;
								endforeach ?>

							<?php endif ?>
					</section>

					<div class="seccion-btns">
						<div class="row">
							<div class="col-md-12 text-right">

								<button data-area="<?=$area['id']?>" class="btn-primary btn" data-jadmin="elementos">Agregar Elemento</button>
							</div>
						</div>
					</div>
				</article>
			</section>
		<?php endforeach ?>
		</div>
	</div>
</div>

</section>

<section class="seccion-elementos seccion" id="containerElementos">
	<div class="contenedor-fix">

	<div class="container-fluid">
		<a href="#" data-jida="hide" data-elemento=".seccion-elementos" ><i class="close-jida-modal fa fa-times"></i></a>
		<div class="row">
			<?php foreach ($data->elementos as $key => $elemento): ?>
				<?php $ele = new $elemento;?>

				<div class="col-md-3">
					<div class="seccion-elemento">
						<div id="<?=$ele->id()?>" class="elemento-nombre" data-jadmin="clone-form">
							<?=$ele->nombre()?>
						</div>
						<div class="elemento-form hidden" data-elemento='{"nombre":"<?=$ele->nombre()?>","id":"<?=$ele->id()?>"}'>
							<?=$ele->jform()?>
						</div>
					</div>

				</div>
			<?php endforeach ?>

		</div>
		</div>
	</div>

</section>