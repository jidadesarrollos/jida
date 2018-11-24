<?php

namespace Jida\Medios\Sesion;

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

        } else
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
        } else
            if (isset ($_SESSION [$clave])) {
                return $_SESSION [$clave];
            } else {
                return false;
            }
    }

}
