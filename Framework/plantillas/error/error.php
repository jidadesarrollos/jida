<h3>Excepción Capturada</h3>

<h4>Excepcion: <?= $this->mensaje ?></h4>
<h5>Código <?= $this->codigo ?></h5>

<?php foreach ($this->traza as $i => $item) { ?>

    <UI>
        <LI>Traza <?= $i ?>
            <ul>
                <li>Archivo: <?= $item['file'] ?></li>
                <li>Linea: <?= $item['line'] ?></li>
                <li><?= "{$item['class']}{$item['type']}{$item['function']}" ?></li>
            </ul>
        </LI>
    </UI>

<?php } ?>