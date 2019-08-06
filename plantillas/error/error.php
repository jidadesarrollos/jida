<header class="page-header">
    <h1>Excepción Capturada</h1>
</header>


<h5>Excepcion: <?= $this->exception['error'] ?></h5>
<h6>Código <?= $this->exception['code'] ?></h6>

<?php foreach ($this->exception['trace'] as $i => $item) { ?>
    <ul>
        <LI>Traza <?= $i ?>
            <ul>
                <?php if (isset($item['file'])): ?>
                    <li>Archivo: <?= $item['file'] ?></li>
                <?php endif; ?>

                <?php if (isset($item['line'])): ?>
                    <li>Linea: <?= $item['line'] ?></li>
                <?php endif; ?>

                <?php if (isset($item['class'])): ?>
                    <li><?= "{$item['class']}{$item['type']}{$item['function']}" ?></li>
                <?php endif; ?>

            </ul>
        </LI>
    </ul>

<?php } ?>