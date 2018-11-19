<?php
$data = $this->data;

if (isset($data->formLoggin)) { ?>

    <div class="card card-login mx-auto mt-5">
        <div class="card-header">JIDA Framework</div>
        <div class="card-body">
            <?= $data->formLoggin ?>
        </div>
    </div>

    <?php

}
