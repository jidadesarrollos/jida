<?php

namespace Jida\Modulos\Usuarios;

use Jida\Medios\Sesion;
use Jida\Modulos\Usuarios\Componentes\Permisos;

class Usuario {

    /**
     * @var Permisos $permisos
     */
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

    static function iniciarSesion($usuario, $clave) {

        $instancia = self::instancia();

        $datos = $instancia
            ->_modelo
            ->consulta()
            ->filtro(['usuario' => $usuario, 'clave' => md5($clave)])
            ->fila();

        if (!$datos) {
            return false;
        }

        $instancia->_modelo->instanciar($datos['id_usuario'], $datos);
        $instancia->permisos->obtener();

        Sesion::registrar();
        Sesion::editar('_usuario', self::$_instancia);

        return $instancia->_modelo->obtenerPropiedades();

    }

    /**
     * Retorna la instancia única del objeto usuario
     */
    static function instancia() {

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

    public function obtener($propiedad) {

        if (is_object($this->_modelo) and property_exists($this->_modelo, $propiedad)) {
            $this->_modelo->{$propiedad};
        }

        return false;

    }
}