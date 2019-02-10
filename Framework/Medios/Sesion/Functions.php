<?php

namespace Jida\Medios\Sesion;

use Jida\Medios\Debug;

Trait Functions {

    /**
     * @internal Modifica o crea una variable existente
     * @method set
     * @access   public
     *
     * @param string $clave key de la variable de sesion
     * @param string $param2 Valor de la variable a crear o modificar
     * @param string $param3 Si es pasado, el parametro dos será tomado como una segunda clave de la variable de sessión
     *                       y este será el valor de la variable.
     *
     * @since    0.1
     *
     */
    static function editar($clave, $param2, $param3 = "") {

        if (!empty($param3)) {

            $_SESSION[$clave][$param2] = $param3;

        }
        else
            if (!empty($clave)) {
                $_SESSION[$clave] = $param2;
            }
    }

    /**
     * @internal Genera una nueva variable de sesión
     * @method obt
     * @access   public
     *
     * @param string clave key de la variable de session a obtener
     *
     * @since    0.1
     *
     */
    static function obt($clave, $clave2 = "") {

        if (!empty($clave2) and isset ($_SESSION [$clave][$clave2])) {
            return $_SESSION [$clave][$clave2];
        }
        else
            if (isset ($_SESSION [$clave])) {
                return $_SESSION [$clave];
            }
            else {
                return false;
            }

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

        if (!$key) {
            session_destroy();
            session_unset();
        }

        $key = !is_array($key) ? [$key] : $key;

        foreach ($key as $clave) {
            if (isset($_SESSION[$clave])) unset($_SESSION[$clave]);
        }

        return true;

    }

}
