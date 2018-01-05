<?php

namespace Jida\Inicio\Rutas;

use Jida\Helpers as Helpers;

class Jadmin extends Procesador {


    function __construct(Parser $padre) {

        $this->_padre = $padre;
    }

    public function procesar() {

        $this->_moduloValidado = false;
        $path = '\\App\\Jadmin\\Controllers';
        $posController = $this->_padre->proximoParametro();

        if($this->_esModulo()) {

        }

        if ($this->_esControlador($path, $posController)) {




    }
}