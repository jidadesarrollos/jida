<?php
/**
 * Arranque de Session de la aplicación
 *
 * @package  framework
 * @category core
 * @author   Julio Rodriguez <jrodriguez@jidadesarrollos.com>
 * @version  0.1.0 02/01/2014
 * @since    0.5
 */

namespace Jida\Medios;

use Jida\Medios\Sesion\Functions;
use Jida\Medios\Sesion\Validacion;

class Sesion {

    use Functions, Validacion;
    static public $usuario;

    /**
     * @internal Inicia una nueva sesión.
     * @method iniciarSession
     * @access   public
     * @since    0.1
     *
     */
    static function iniciar() {

        session_start();
        self::editar('__idSession', self::getIdSession());

        self::$usuario = new Usuario();
        if (self::obt('_usuario')) {

        }


    }

    /**
     * @internal Retorna el id de la sessión
     * @method getIdSession
     * @access   public
     * @since    0.1
     *
     */
    static function getIdSession() {

        return session_id();
    }

    /**
     * @internal  Registra el inicio de una sesion loggeada
     * cambiando el id de la sesion.
     * @method registrar
     * @access    static public
     * @since     0.1
     *
     */

    static function registrar() {

        self::destruir('acl');
        self::editar('_sesionValida', true);
        session_regenerate_id();
        self::editar('__idSession', self::getIdSession());

    }

    /**
     * @internal Verifica si el usuario actual tiene sesión iniciada
     * en el sistema
     *
     * @return boolean true
     * @since    0.1
     * @deprecated
     * @see      activo
     *
     */
    static function activa() {

        if (self::obt('_sesionValida')) return true;

        return false;

    }

    static function es($perfil) {

        $usuario = self::$usuario;
        if (!is_object($usuario)) return false;

        if (!property_exists($usuario, 'perfiles')) return false;

        if (array_intersect($perfil, $usuario->perfiles)) return true;

        return false;

    }

}

