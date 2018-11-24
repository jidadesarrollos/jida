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
use Jida\Modulos\Usuario\Usuario;

class Sesion {

    use Functions;
    static public $user;

    /**
     * @internal Inicia una nueva sesión.
     * @method iniciarSession
     * @access   public
     * @since    0.1
     *
     */
    static function iniciar() {

        session_start();
        self::set('__idSession', self::getIdSession());

        self::$user = new Usuario();
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
     * Elimina una variable de sesón o la session completa
     *
     * @internal Si es pasado un key se elimina una variable especifica,
     * caso contrario se elimina la session completa
     * @var $key clave o arreglo de claves de variable de session que se desea eliminar
     * @method destruir
     * @access   public
     * @since    0.1
     *
     */

    static function destruir($key = false) {

        if ($key) {
            if (is_array($key)) {
                foreach ($key as $clave) {
                    if (isset($_SESSION[$clave])) {
                        unset($_SESSION[$clave]);
                    }
                }
            } else {

                if (array_key_exists($key, $_SESSION)) {
                    unset($_SESSION[$key]);
                }
            }

        } else {
            session_destroy();
            session_unset();

        }

        return true;

    }

    /**
     * Alias de funcion destruir
     * @method destroy
     *
     * @access static
     * @deprecated
     * @see    self::destruir
     */

    static function destroy($key = false) {

        self::destruir($key);
    }

    /**
     * @internal  Registra el inicio de una sesion loggeada
     * cambiando el id de la sesion.
     * @method SessionLogin
     * @access    static public
     * @since     0.1
     *
     */
    static function sessionLogin() {

        self::destroy('acl');
        self::set('isLoggin', true);
        session_regenerate_id();
        self::set('__idSession', self::getIdSession());
    }

    static function set($clave, $param2, $param3 = "") {

        return self::editar($clave, $param2, $param3);
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

        if (self::obt('isLoggin'))
            return true;
        else
            return false;

    }

    /**
     * @internal Verifica si el usuario actual pertenece al $perfil
     * requerido o uno superior
     *
     * @param int $perfil Id del perfil requerido
     *
     * @return boolean $acceso
     * @since    0.1
     *
     */
    static function checkAcceso($perfil) {

        return self::checkPerfilAcceso($perfil);
    }

    static function es($perfil) {

        if (is_object(self::obt('Usuario')) and property_exists(self::obt('Usuario'), 'perfiles')) {
            $perfiles = self::obt('Usuario')->perfiles;
            if (!is_array($perfil))
                $perfil = explode(" ", $perfil);

            $encontrados = array_intersect($perfil, $perfiles);
            if ($encontrados) {
                return true;
            }

        }

        return false;

    }

}

