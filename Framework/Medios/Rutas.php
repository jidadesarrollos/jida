<?php

namespace Jida\Medios;

class Rutas {

    static function redireccionar ($url) {

        header('location:' . $url . '');
        exit;

    }

}