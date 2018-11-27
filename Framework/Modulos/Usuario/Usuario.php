<?php

namespace Jida\Modulos\Usuario;

use Jida\Medios\Debug;
use Jida\Medios\Sesion;
use Jida\Modulos\Usuario\Componentes\Permisos;

class Usuario {

    private $_modelo;
    public  $permisos;

    function __construct($id = null) {

        $this->_modelo = new Modelos\Usuario($id);
        $this->_inicializar();

    }

    private function _inicializar() {

        $this->permisos = new Permisos($this->_modelo);

    }

    public function validarSesion($_usuario, $_clave) {

        $modelo = $this->_modelo;
        $usuario = $modelo->select('*')
            ->filtro(['nombre_usuario' => $_usuario, 'clave_usuario' => md5($_clave)])
            ->obt();

        Debug::mostrarArray($usuario);

        Sesion::registrar();
        return $this->_modelo->obtenerPropiedades();

    }

}