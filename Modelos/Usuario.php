<?php

namespace Jida\Modelos;

use Jida\BD as BD;
use Jida\Helpers as Helpers;
use Exception;

class Usuario extends BD\DataModel {

    private $_ce = "900";

    var $id_usuario;
    var $nombre_usuario;
    var $clave_usuario;

    var $nombres;
    var $apellidos;
    var $correo;
    var $sexo;

    var $activo;
    var $id_estutus;
    var $ultima_session;
    var $validacion;
    var $codigo_recuperacion;
    var $img_perfil;

    protected $perfiles = [];

    protected $tablaBD = "s_usuarios";
    protected $pk = "id_usuario";
    protected $unico = ['nombre_usuario'];
    protected $registro = false;

    function asociarPerfiles ($perfiles) {

        $insert = "insert into s_usuarios_perfiles (id_usuario_perfil, id_usuario, id_perfil) values ";
        $i = 0;

        foreach ($perfiles as $key => $idPerfil) {

            if ($i > 0) {
                $insert .= ",";
            }

            $insert .= "(null,$this->id_usuario,$idPerfil)";
            $i++;

        }

        $delete = "delete from s_usuarios_perfiles where id_usuario=$this->id_usuario;";

        $this->bd->ejecutarQuery($delete . $insert, 2);

        return ['ejecutado' => 1];

    }

    function registrar ($datos, $perfiles = "", $validacion = true) {

        if (empty($perfiles)) {
            throw new Exception("Debe asociarse al menos un perfil al usuario a registrar", $this->_ce . 1);
        }

        if ($validacion === true) {
            $codigo = hash("sha256", Helpers\FechaHora::timestampUnix() . Helpers\FechaHora::datetime());
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

    function modificar ($idUsuario, $datos, $perfiles = "") {

        if (empty($idUsuario)) {
            throw new Exception("El usuario que desea modificar no existe.", $this->_ce . 2);
        }

        if ($this->salvar($datos)) {
            $this->asociarPerfiles($perfiles);
            return true;
        }
        else {
            return false;
        }

    }

    function eliminar ($idUsuario) {

        if (empty($idUsuario)) {
            throw new Exception("El usuario que desea eliminar no existe.", $this->_ce . 3);
        }

        if ($this->eliminar($idUsuario)) {
            return true;
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

            $query = "select
                a.id_usuario_perfil AS id_usuario_perfil,
                a.id_perfil AS id_perfil,
                a.id_usuario AS id_usuario,
                c.nombre_usuario,
                c.nombres,
                c.apellidos,
                b.clave_perfil AS clave_perfil
                from
                s_usuarios_perfiles a
                join s_perfiles b ON (a.id_perfil = b.id_perfil)
                join s_usuarios c on (a.id_usuario = c.id_usuario) where a.id_usuario=$this->id_usuario";

            $data = $this->bd->ejecutarQuery($query);

            if (is_array($data) and count($data) > 1) {
                throw new Exception("No se han obtenido los perfiles del usuario", $this->_ce . 4);
            }

            while ($perfil = $this->bd->obtenerArrayAsociativo($data)) {
                $this->perfiles[$perfil['clave_perfil']] = $perfil['clave_perfil'];
            }

        }
        else {
            $this->perfiles[] = 'UsuarioPublico';
        }

    }

    function validarLogin ($usuario, $clave, $validacion = true, $callback = null) {

        $clave = md5($clave);

        $result = $this->consulta()->filtro([
                                                'clave_usuario'  => $clave,
                                                'nombre_usuario' => $usuario,
                                                'validacion'     => 1
                                            ])->fila();

        if (is_array($result) and count($result) > 0) {

            $this->establecerAtributos($result);
            $this->__obtConsultaInstancia($this->id_usuario);
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

        Helpers\Sesion::sessionLogin();
        Helpers\Sesion::set('Usuario', $this);

        return $this;

    }


    function cerrarSesion ($idUser = "") {

        if (empty($idUser))
            $idUser = $this->id_usuario;
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