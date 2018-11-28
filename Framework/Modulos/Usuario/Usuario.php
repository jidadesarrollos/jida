<?php

namespace Jida\Modulos\Usuario;

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

    public function validarInicioSesion($_usuario, $_clave) {

        $modelo = new Modelos\Usuario();
        $modelo->select('*');
        $modelo->filtro(['nombre_usuario' => $_usuario, 'clave_usuario' => md5($_clave)]);
        $usuario = $modelo->fila();

        if (!$usuario) {
            return false;
        }

        Sesion::registrar();
        $this->_modelo = new Modelos\Usuario($usuario['id_usuario']);
        return $this->_modelo->obtenerPropiedades();

    }

}