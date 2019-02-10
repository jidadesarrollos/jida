<?php

namespace Jida\Modulos\Usuarios\Modelos;

use Jida\Core\Modelo;

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

    protected $perfiles = [];

    protected $tablaBD = "s_usuarios";
    protected $pk = "id_usuario";
    protected $unico = ['usuario'];
    protected $registro = false;

}