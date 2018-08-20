<?php

namespace Jida\Helpers;

class Rutas {

    static function redireccionar ($url) {

        header('location:' . $url . '');
        exit;

    }

}