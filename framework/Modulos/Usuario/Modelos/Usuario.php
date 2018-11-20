<?php

namespace Jida\Modulos\Usuario\Modelos;

use Jida\Core\Modelo;

class Usuario extends Modelo {

    private $_ce = "90010";

    var $id_usuario;
    var $nombre_usuario;
    var $clave_usuario;

    var $nombres;
    var $apellidos;
    var $correo;
    var $sexo;

    var $activo;
    var $id_estatus;
    var $id_empresa;
    var $ultima_session;
    var $validacion;
    var $codigo_recuperacion;
    var $img_perfil;

    protected $perfiles = [];

    protected $tablaBD = "s_usuarios";
    protected $pk = "id_usuario";
    protected $unico = ['nombre_usuario'];
    protected $registro = false;

}