<?php

namespace Jida\Modulos\Usuario;

use Jida\Medios\Sesion;
use Jida\Modulos\Usuario\Componentes\Permisos;

class Usuario {

    public $permisos;

    private $_modelo;
    private static $_instancia;

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

    static function iniciarSesion($usuario, $clave) {

        $instancia = self::$_instancia;

        $datos = $instancia
            ->modelo
            ->consulta()
            ->filtro(['nombre_usuario' => $usuario, 'clave_usuario' => md5($clave)]);

        if (!$datos) {
            return false;
        }
        $instancia->_modelo->instanciar($datos['id_usuario'], $datos);
        Sesion::registrar();
        return $instancia->_modelo->obtenerPropiedades();

    }

    /**
     * Retorna la instancia única del objeto usuario
     */
    static function obtener() {

        if (self::$_instancia) {
            return self::$_instancia;
        }

        if (Sesion::obt('_sesionValida')
            and Sesion::obt('_usuario') instanceof Usuario) {
            $instancia = self::obt('_usuario');
        }
        else {
            $instancia = new Usuario();
        }
        self::$_instancia = $instancia;

        return self::$_instancia;

    }
}