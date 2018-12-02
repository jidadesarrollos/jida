<?php

namespace Jida\Modulos\Usuario;

use Jida\Medios\Debug;
use Jida\Medios\Sesion;
use Jida\Modulos\Usuario\Componentes\Permisos;

class Usuario {

    public $permisos;

    private        $_modelo;
    private static $_instancia;

    function __construct($id = null) {

        $this->_modelo = new Modelos\Usuario($id);
        $this->_inicializar();

    }

    private function _inicializar() {

        $this->permisos = new Permisos($this->_modelo);

    }

    static function iniciarSesion($usuario, $clave) {

        $instancia = self::obtener();

        $datos = $instancia
            ->_modelo
            ->consulta()
            ->filtro(['nombre_usuario' => $usuario, 'clave_usuario' => md5($clave)])
            ->fila();

        if (!$datos) {
            return false;
        }

        $instancia->_modelo->instanciar($datos['id_usuario'], $datos);

        Sesion::registrar();

        Debug::mostrarArray(Sesion::$usuario);

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
            $instancia = Sesion::obt('_usuario');
        }
        else {
            $instancia = new Usuario();
        }
        self::$_instancia = $instancia;

        return self::$_instancia;

    }
}