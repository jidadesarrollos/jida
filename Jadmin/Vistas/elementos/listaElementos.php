<?php
?>


<div class="row">
	<div class="col-md-4">
	<?php foreach ($this->elementos as $tipo => $elementos): ?>
		<h2>
			<?=$tipo?>
		</h2>
		<?php foreach ($elementos as $key => $ele): ?>
		<div class="media">
			<div class="media-body">
				<h3 class="media-heading">
					<?=$ele->nombre?>
				</h3>
				<p><?=$ele->descripcion?></p>
			</div>
		</div>
		<?php endforeach ?>
	<?php endforeach ?>
	</div>
	<div class="col-md-8"></div>
</div>
