<?php
/**
 * Controlador de Session de la aplicación
 *
 * @package framework
 * @category core
 * @author  Julio Rodriguez <jirc48@gmail.com>
 * @version 0.1.0 02/01/2014
 * @since 0.5
 */

namespace Jida\Helpers;

class Sesion {

    /**
     * @internal Inicia una nueva sesión.
     * @method iniciarSession
     * @access public
     * @since 0.1
     *
     */

    static function iniciar() {
        session_start();
        self::set('__idSession', self::getIdSession());
    }

    /**
     * @internal Retorna el id de la sessión
     * @method getIdSession
     * @access public
     * @since 0.1
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
     * @access public
     * @since 0.1
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
     * @access static
     * @deprecated
     * @see self::destruir
     */
    static function destroy($key = false) {
        self::destruir($key);
    }

    /**
     * @internal  Registra el inicio de una sesion loggeada
     * cambiando el id de la sesion.
     * @method SessionLogin
     * @access static public
     * @since 0.1
     *
     */
    static function sessionLogin() {
        self::destroy('acl');
        self::set('isLoggin', TRUE);
        session_regenerate_id();
        self::set('__idSession', self::getIdSession());
    }

    static function set($clave, $param2, $param3 = "") {
        return self::editar($clave, $param2, $param3);
    }

    /**
     * @internal Modifica o crea una variable existente
     * @method set
     * @access public
     * @param string $clave key de la variable de sesion
     * @param string $param2 Valor de la variable a crear o modificar
     * @param string $param3 Si es pasado, el parametro dos será tomado como una segunda clave de la variable de sessión
     * y este será el valor de la variable.
     * @since 0.1
     *
     */
    static function editar($clave, $param2, $param3 = "") {

        if (!empty($param3)) {

            $_SESSION[$clave][$param2] = $param3;

        } else
            if (!empty($clave)) {
                $_SESSION[$clave] = $param2;
            }
    }

    /**
     * @internal Genera una nueva variable de sesión
     * @method obt
     * @access public
     * @param string clave key de la variable de session a obtener
     * @since 0.1
     *
     */
    static function obt($clave, $clave2 = "") {

        if (!empty($clave2) and isset ($_SESSION [$clave][$clave2])) {
            return $_SESSION [$clave][$clave2];
        } else
            if (isset ($_SESSION [$clave])) {
                return $_SESSION [$clave];
            } else {
                return false;
            }
    }

    /**
     * Genera una nueva variable de sesión
     * * @method get
     * @access public
     * @param string clave key de la variable de session a obtener
     * @since 0.5
     * @deprecated
     *
     */
    static public function get($clave, $clave2 = '') {

        return self::obt($clave, $clave2);

    }

    /**
     * @internal Verifica si el usuario actual tiene sesión iniciada
     * en el sistema
     *
     * @return boolean true
     * @since 0.1
     * @deprecated
     * @see activa
     *
     */
    static function checkLogg() {

        return self::activa();
    }

    /**
     * @internal Verifica si el usuario actual tiene sesión iniciada
     * en el sistema
     *
     * @return boolean true
     * @since 0.1
     * @deprecated
     * @see activo
     *
     */
    static function activa() {
        if (self::get('isLoggin'))
            return true;
        else
            return false;

    }

    /**
     * @internal Verifica si el usuario actual pertenece al $perfil
     * requerido o uno superior
     *
     * @param int $perfil Id del perfil requerido
     * @return boolean $acceso
     * @since 0.1
     *
     */
    static function checkAcceso($perfil) {
        return self::checkPerfilAcceso($perfil);
    }

    /**
     * @internal Verifica que el usuario actual tenga exactamente el mimso perfil
     * que el perfil requerido
     * @method checkPerfilAcceso
     * @param string $perfil Clave del perfil a consultar
     * @return boolean TRUE si es conseguida o FALSE si no se consigue
     * @since 0.1
     *
     */
    static function checkPerfilAcceso($perfil) {

        if (is_object(self::get('Usuario')) and property_exists(self::get('Usuario'), 'perfiles')) {
            $perfiles = self::get('Usuario')->perfiles;
            #Debug::imprimir($perfiles,true);
            if (is_array($perfiles) and in_array(Cadenas::upperCamelCase($perfil), $perfiles)) {
                return true;
            }
        }

        return false;
    }

    static function es($perfil) {
        if (is_object(self::get('Usuario')) and property_exists(self::get('Usuario'), 'perfiles')) {
            $perfiles = self::get('Usuario')->perfiles;
            if (!is_array($perfil)) $perfil = explode(" ", $perfil);

            $encontrados = array_intersect($perfil, $perfiles);
            if ($encontrados) {
                return true;
            }

        }

        return false;
    }

    /**
     * @internal Verifica si el usuario en sesion es administrador
     *
     * @method checkAdm
     * @return boolean
     * @since 0.1
     *
     */
    static function checkAdm() {
        $perfiles = self::get('usuario', 'perfiles');
        if (in_array('JidaAdministrador', $perfiles) or in_array('Administrador', $perfiles))
            return true;

        return false;
    }
} // END
