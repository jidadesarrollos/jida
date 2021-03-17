<?php

namespace Jida\Modulos\Usuarios;

use Jida\Medios\Debug;
use Jida\Medios\Sesion;
use Jida\Modulos\Usuarios\Componentes\Permisos;
use Jida\Validador\Type\Clave;

class Usuario {

    private static $_instancia;
    /**
     * @var Permisos $permisos
     */
    public $permisos;
    public $modelo;

    function __construct($id = null) {

        $this->_modelo = new Modelos\Usuario($id);
        $this->_inicializar();

    }

    private function _inicializar() {

        $this->permisos = new Permisos($this->_modelo);
    }

    public static function getRandomString($num = 5) {

        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key = substr(str_shuffle($chars), 0, $num);

        return $key;

    }

    static function iniciarSesion($usuario, $clave) {

        $instancia = self::instancia();
        $hash = new Clave($clave);

        $datos = $instancia
            ->_modelo
            ->consulta()
            ->filtro(
                ['usuario' => $usuario, 'id_estatus' => 1],
                ['correo' => $usuario, 'id_estatus' => 1])
            ->fila();

        if (!$datos) {
            return false;
        }
        if (!$hash->compare($datos['clave'])) {

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

        if (Sesion::obt('_sesionValida') and Sesion::obt('_usuario') instanceof Usuario) {
            $instancia = Sesion::obt('_usuario');
        }
        else {
            $instancia = new Usuario();
        }
        self::$_instancia = $instancia;

        return self::$_instancia;

    }

    public function get($property) {
        if (is_object($this->_modelo) and property_exists($this->_modelo, $property)) {
            return $this->_modelo->{$property};
        }

        return null;
    }

    public function obtener($propiedad) {
        return $this->get($propiedad);
    }

    public function cambiarClave($claveVieja, $claveNueva) {

        $hashViejo = new Clave($claveVieja);
        $hashNuevo = new Clave($claveNueva);
        if ($hashViejo->compare($this->_modelo->clave)) {
            $this->_modelo->clave = $hashNuevo->hash();
            $this->_modelo->salvar();
            return true;
        }
        else return false;

    }

    public function nombre() {
        if (empty($this->_modelo->nombres) and empty($this->_modelo->apellidos))
            return $this->_modelo->usuario;
        else return "{$this->_modelo->nombres} {$this->_modelo->apellidos}";
    }
}