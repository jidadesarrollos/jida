<?php

/**
 * Clase Config
 *
 * Clase con las configuraciones de inicializacion
 *
 * @author   Felix Tovar <ftovar@jidadesarrollos.com>
 * @version  1.0 - 22/12/2017
 * @package  Framework
 * @category Configuracion
 *
 */

namespace Jida\Configuracion;

use Jida\Helpers\Debug;

class Config {

    const NOMBRE_APP = 'Aplicación Jida';
    const URL_BASE = '/jida/jidadesarrollos/';
    const ENTORNO_APP = 'dev';
    const ZONA_HORARIA = 'America/Caracas';
    const MODELO_USUARIO = '\Jida\Modelos\User';
    const LOGO_APP = '';
    const PATH_JIDA = "vendor";
    const ENVIAR_EMAIL_ERROR = false;
    const EMAIL_SOPORTE = 'jcontreras@jidadesarrollos.com';

    /**
     * Variable para definicion de modulos dentro de la aplicación
     *
     * @var $modulos
     * @access protected
     */
    var $modulos = [];

    /**
     * Variable para definicion de clases para mensajes dentro de la aplicación
     *
     * @var $mensajes
     * @access protected
     */
    var $mensajes = [];

    /**
     * Variable para definicion de los idiomas dentro de la aplicación
     *
     * @var $idiomas
     * @access protected
     */
    var $idiomas = ['es'];


    /**
     * Variable para definicion del tema a utilizar dentro de la aplicación
     *
     * @var $tema
     * @access protected
     */
    var $tema = 'default';

    var $temaJadmin = 'jadmin';

    /**
     * Variable para definicion del logo dentro de la aplicación
     *
     * @var $logo
     * @access protected
     */
    var $logo;


    private static $instancia;


    public static function obtener () {

        if (!self::$instancia) {

            if (class_exists('\App\Config\Configuracion')) {
                self::$instancia = new \App\Config\Configuracion();
            }
            else {
                self::$instancia = new Config();
            }

        }

        return self::$instancia;

    }

}