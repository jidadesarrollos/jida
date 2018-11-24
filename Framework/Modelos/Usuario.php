<?php

namespace Jida\Modelos;

use Jida\BD\DataModel;
use Jida\Medios as Medios;
use Exception;

class Usuario extends DataModel {

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

    function __construct ($id = false) {

        parent::__construct($id);

    }

    public function asociarPerfiles ($perfiles) {

        $perfil = new UsuarioPerfil();
        $perfil->eliminar($this->id_usuario, 'id_usuario');

        foreach ($perfiles as $k => $idPerfil) {

            $perfil = new UsuarioPerfil();
            $perfil->id_usuario = $this->id_usuario;
            $perfil->id_perfil = $idPerfil;
            $perfil->salvar();

        }

        $perfil = new UsuarioPerfil();
        $perfil->consulta()->filtro(['id_usuario' => $this->id_usuario]);
        $resultado = $perfil->obt();

        if (is_array($resultado) and count($resultado) > 0) {

            return $resultado;

        }
        else {

            return false;

        }

    }

    public function guardar ($datos, $perfiles = "", $validacion = true) {

        if (empty($perfiles)) {
            throw new Exception("Debe asociarse al menos un perfil al usuario a registrar", $this->_ce . 1);
        }

        if ($validacion === true) {
            $codigo = hash("sha256", Medios\FechaHora::timestampUnix() . Medios\FechaHora::datetime());
            $this->validacion = $codigo;
            $this->activo = 0;
        }

        $this->id_estatus = (empty($this->id_estatus)) ? 1 : $this->id_estatus;

        if ($this->salvar($datos)) {
            $this->asociarPerfiles($perfiles);
            return $this->id_usuario;
        }
        else {
            return false;
        }

    }

    private function obtenerPerfiles ($idUsuario = "") {

        if ($idUsuario != "") {
            $this->id_usuario = $idUsuario;
        }

        if (is_array($this->perfiles) and count($this->perfiles) < 1) {

            $perfiles = new UsuarioPerfil();
            $data = $perfiles->obtPerfiles($this->id_usuario);

            if (is_array($data) and count($data) < 1) {
                throw new Exception("No se han obtenido los perfiles del usuario", $this->_ce . 4);
            }

            foreach ($data as $k => $perfil) {
                $this->perfiles[$perfil['clave_perfil']] = $perfil['clave_perfil'];
            }

        }
        else {
            $this->perfiles[] = 'UsuarioPublico';
        }

    }

    function validarLogin ($usuario, $clave, $validacion = true) {

        $clave = md5($clave);

        $result = $this->consulta()->filtro([
                                                'clave_usuario'  => $clave,
                                                'nombre_usuario' => $usuario,
                                                'validacion'     => $validacion
                                            ])->fila();

        if (is_array($result) and count($result) > 0) {

            $this->establecerAtributos($result);
            $this->obtenerBy($this->id_usuario);
            $this->obtenerDataRelaciones();
            $this->iniciarSesion();
            $this->activo = 1;
            $this->salvar();
            $this->obtenerPerfiles();

            return $result;

        }
        else {
            return false;
        }

    }

    function iniciarSesion () {

        Medios\Sesion::sessionLogin();
        Medios\Sesion::editar('Usuario', $this);

        return $this;

    }


    function cerrarSesion ($idUser = "") {

        if (empty($idUser)) {
            $idUser = $this->id_usuario;
        }

        $this->activo = 0;
        $this->salvar();

    }

    function cambiarClave ($clave, $nuevaClave) {

        $clave = md5($clave);

        if ($clave === $this->clave_usuario) {
            $this->clave_usuario = md5($nuevaClave);
            $this->salvar();
            return true;
        }
        else {
            return false;
        }

    }

    function obtenerToken () {

    }

}