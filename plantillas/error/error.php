<?php
$e = $this->excepcion;
?>

<header class="page-header">
    <h1>
        Error <?= $e->codigo; ?>
    </h1>
</header>
<div class="alert alert-danger mt-30">
    <?= $e->mensaje; ?>
</div>

<div class="alert alert-warning">
    <ul>
        <?php foreach ($e->traza as $key => $hito): ?>
            <li>
                <?php if (array_key_exists('file', $hito)) : ?>
                    <h5><strong><?= $key ?> Archivo: </strong>
                        <?= $hito['file'] ?>
                    </h5>
                <?php endif; ?>
                <?php if (array_key_exists('line', $hito)) : ?>

                    <strong>Linea: </strong><?= $hito['line'] ?> <br/>
                <?php endif; ?>
                <?php if (array_key_exists('class', $hito)): ?>
                    <h5>
                        <strong>Clase: </strong><?= $hito['class'] ?>::<?= $hito['function'] ?>
                    </h5>
                <?php else: ?>
                    <?= $hito['function'] ?>
                <?php endif ?>

            </li>
        <?php endforeach ?>
    </ul>
</div>

<div class="alert alert-info">
    <h3>Si Desea cambiar esta plantilla</h3>
    <ul>
        <li>Cree un Controlador para excepciones y registrelo en la constante CONTROLADOR_EXCEPCIONES</li>
        <li>Cree una plantilla para la excepción en el directorio <strong>Aplicacion/layout/error</strong>
            llamara "error.php"
        </li>
        <li>Puede crear una plantilla para el error que desee dentro de ese directorio, solo debe crear el
            archivo .php con el codigo de la excepcion como nombre.
        </li>
    </ul>
</div>
