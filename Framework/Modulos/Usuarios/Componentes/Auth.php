<?php

namespace Jida\Modulos\Usuarios\Componentes;

use Jida\Medios\Sesion;

class Auth {

    function iniciarSesion () {

    }

    function cerrarSesion () {

    }
    function cambioClave ($claveVieja, $claveNueva){
        Sesion::$usuario->obtener();
    }
}