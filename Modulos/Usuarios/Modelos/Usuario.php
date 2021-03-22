<?php

namespace Jida\Modulos\Usuarios\Modelos;

use Jida\Core\Modelo;
use Jida\Medios\Debug;

class Usuario extends Modelo {

    static private $_ce = 90010;

    public $id_usuario;
    public $usuario;
    public $clave;

    public $nombres;
    public $apellidos;
    public $correo;
    public $sexo;

    public $activo;
    public $id_estatus;
    public $ultima_sesion;
    public $validacion;
    public $codigo_recuperacion;
    public $img_perfil;

    public $birthday;
    public $telefono;
    public $permission;

    protected $perfiles = [];

    protected $tablaBD = "s_usuarios";
    protected $pk = "id_usuario";
    protected $unico = ['usuario'];
    protected $registro = false;

    static private $instancia = null;

    static function listaUsuarios($consulta = []) {

        if (self::$instancia == null) {
            self::$instancia = new Usuario();
        }

        if (empty($consulta)) {
            $consulta = ['id_usuario', 'usuario', 'nombres', 'apellidos', 'correo', 'id_usuario as perfiles', 'id_estatus'];
        }
        else {
            $consulta[] = 'id_usuario as perfiles';
        }

        $data = self::$instancia->consulta($consulta)->obt();
        $perfil = new UsuarioPerfil();

        foreach ($data as &$fila) {

            $fila['perfiles'] = $perfil->obtPerfiles($fila['id_usuario']);

        }

        return $data;
    }

}