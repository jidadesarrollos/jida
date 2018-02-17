<?php

/**
 * Helper para manejo de mensajes dentro de la aplicación
 *
 * @internal Las variables de session para mensajes dentro del jida-framework son:
 *
 * __msjForm Para mensajes en la clase formulario
 * __msj  Para mensajes en la clase vista y donde se desee.
 *
 * @package  framework
 * @category helper
 * @author   Julio Rodriguez
 * @version  0.1 12/01/2014
 */

namespace Jida\Helpers;

class Mensajes {

    function __construct() {
    }

    /**
     * @internal Define un arreglo con los valores css para los mensajes
     *
     * Las clases css a aplicar deben estar definidas en las constantes
     * usadas.
     * @method crear
     *
     * @param string $tipo tipo de mensaje
     *
     * @return string
     * @access   public
     * @since    0.1
     *
     */

    static function crear($tipo, $msj, $hidden = false) {

        $css = self::obtenerEstiloMensaje($tipo);
        if ($hidden == true) {

            $mensaje = "
                    <div class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" data-dismiss=\"alert\">
                        <span aria-hidden=\"true\">&times;</span></button>
                        $msj

                    </div>";

        } else {
            $mensaje = "
                    <div class=\"$css\">
                        $msj
                    </div>";
        }

        return $mensaje;
    }

    /**
     * @internal   Define el estilo de los mensajes tomando en cuenta la configuracion general
     * @method obtenerEstiloMensaje
     *
     * @param string $tipo tipo de mensaje
     *
     * @return string
     * @access     public
     * @since      0.1
     * @deprecated 0.6
     */
    static function obtenerEstiloMensaje($clave) {

        $estilo = array();
        if (array_key_exists('configMensajes', $GLOBALS)) {
            $estilo = $GLOBALS['configMensajes'];
        } else {

        }

        if (array_key_exists($clave, $estilo)) {
            return $estilo[$clave];
        }

    }

    /**
     * @internal Define el estilo de los mensajes tomando en cuenta la configuracion general
     * @method obtEstilo
     *
     * @param string $tipo tipo de mensaje
     *
     * @return string
     * @access   public
     * @since    0.6
     *
     */
    static function obtEstilo($clave) {

        $estilo = [];

        if (array_key_exists('configMensajes', $GLOBALS)) {
            $estilo = $GLOBALS['configMensajes'];
        }

        if (array_key_exists($clave, $estilo)) {

            return $estilo[$clave];
        }

        return $estilo[$clave];
    }


    /**
     * @internal   Crea mensaje de con estilo error
     * @method mensajeError
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access     public
     * @since      0.1
     * @deprecated 0.6
     */
    static function mensajeError($mensaje) {

        $css = self::obtenerEstiloMensaje('error');
        $mensaje = "
                    <DIV class=\"$css\">
                    <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";

        return $mensaje;


    }

    /**
     * @internal Crea mensaje de con estilo error
     *
     * @method error
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access   public
     * @since    0.6
     *
     */
    static function error($mensaje) {

        $css = self::obtEstilo('error');

        $mensaje = "
                    <DIV class=\"$css\">
                    <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";

        return $mensaje;
    }

    /**
     * @internal   Crea mensaje de con estilo alerta
     * @method mensajeError
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access     public
     * @since      0.1
     * @deprecated 0.6
     */

    static function mensajeAlerta($mensaje) {

        $css = self::obtenerEstiloMensaje('alerta');
        $mensaje = "
                    <DIV class=\"$css\">
                    <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";


        return $mensaje;
    }

    /**
     * @internal Crea mensaje de con estilo alerta
     * @method alerta
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access   public
     * @since    0.6
     *
     */
    static function alerta($mensaje) {

        $css = self::obtEstilo('alerta');
        $mensaje = "
                    <div class=\"$css\">
                    <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </div>";


        return $mensaje;
    }

    /**
     * @internal   Crea mensaje de con estilo suceso
     * @method mensajeError
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access     public
     * @since      0.1
     * @deprecated 0.6
     */
    static function mensajeSuceso($mensaje) {

        $css = self::obtenerEstiloMensaje('suceso');
        $mensaje = "
                    <DIV class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";

        return $mensaje;
    }

    /**
     * <<<<<<< HEAD
     *
     * @internal Crea mensaje de con estilo informacion
     * @method mensajeError
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access   public
     * @since    0.1
     *
     * =======
     * @internal Crea mensaje de con estilo suceso
     * @method suceso
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access   public
     * @since    0.6
     *
     */
    static function suceso($mensaje) {

        $css = self::obtEstilo('suceso');
        $mensaje = "
                    <div class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </div>";

        return $mensaje;
    }

    /**
     * @internal   Crea mensaje de con estilo informacion
     * @method mensajeError
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access     public
     * @since      0.1
     * @deprecated 0.6
     * >>>>>>> c843122350ef21b73c3a8aa57f90e3313eb58c64
     */
    static function mensajeInformativo($mensaje) {

        $css = self::obtenerEstiloMensaje('info');
        $mensaje = "
                    <DIV class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-label=\"Close\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </DIV>";

        return $mensaje;
    }

    /**
     * @internal Crea mensaje de con estilo informacion
     * @method informativo
     *
     * @param string $mensaje con el mensaje a mostrar
     *
     * @return string
     * @access   public
     * @since    0.6
     *
     */
    static function informativo($mensaje) {

        $css = self::obtEstilo('info');
        $mensaje = "
                    <div class=\"$css\">
                        <button type=\"button\" class=\"close pull-right\" aria-label=\"Close\" aria-hidden=\"true\">&times;</button>
                        $mensaje
                    </div>";


        return $mensaje;
    }

    /**
     * Imprime un mensaje si existe
     *
     * @param string $msj Nombre de la variable a imprimir.
     * @method imprimirMensaje
     *
     * @access     public
     * @since      0.1
     * @deprecated 0.6
     */
    static function imprimirMensaje($msj = "__msj") {

        self::imprimirMsjSesion($msj);
    }

    /**
     * Imprime un mensaje si existe
     *
     * @param string $msj Nombre de la variable a imprimir.
     * @method imprimir
     *
     * @access public
     * @since  0.6
     *
     */
    static function imprimir($msj = "__msj") {

        self::imprimirMsjSesion($msj);
    }

    /**
     * Imprime el mensaje guardado en una variable de sesión y luego es destruida la variable
     *
     * @internal Si no se pasa ningun parametro, la función verificará si existe una variable de sesion "__msj".
     *
     * @param string $msj Nombre de la variable de sesión a imprimir.
     * @method imprimirMsjSesion
     *
     * @access   public
     * @since    0.1
     */
    static function imprimirMsjSesion($msj = "__msj") {

        if (isset($_SESSION[$msj])) {
            echo $_SESSION[$msj];
            Sesion::destruir($msj);
        }
    }

    static function msjExcepcion($msj, $ruta) {

        $_SESSION['__excepcion'] = $msj;
        echo $_SESSION['__excepcion'];
    }

} // END

