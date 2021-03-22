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

use Jida\Medios\Debug;

class Config {

    const NOMBRE_APP = 'Aplicación Jida';
    const SHORT_NAME_APP = 'APP';
    const URL_BASE = '';
    const ENTORNO_APP = 'dev';
    const TIME_ZONE = 'America/Caracas';
    const MODELO_USUARIO = '\Jida\Modelos\User';
    const LOGO_APP = '';
    const PATH_JIDA = "vendor/jida/jida";
    const ENVIAR_EMAIL_ERROR = false;
    const EMAIL_SOPORTE = 'jcontreras@jidadesarrollos.com';
    const HASH_CLAVE = "md5"; // opciones: password_hash, md5
    const TIPO = 'API';
    const VERSION = '0.1';

    /**
     * Define si la aplicacion es multiidioma
     *
     * @const bolean MULTIIDIOMA
     */
    const MULTIIDIOMA = false;
    const IDIOMA_DEFAULT = "es";

    /**
     * Variable para definicion de modulos dentro de la aplicación
     *
     * @var $modulos
     * @access protected
     */
    //TODO: Crear propiedad privada y generar metodo getter
    public static $modulos = [];

    /**
     * Variable para definicion de clases para mensajes dentro de la aplicación
     *
     * @var $mensajes
     * @access protected
     */
    public $mensajes = [];

    /**
     * Variable para definicion de los idiomas dentro de la aplicación
     *
     * @var $idiomas
     * @access protected
     */
    public $idiomas = [
        'es' => "Español"
    ];

    /**
     * Variable para definicion del tema a utilizar dentro de la aplicación
     *
     * @var $tema
     * @access protected
     */
    public $tema = 'default';

    /**
     * Variable para definicion del logo dentro de la aplicación
     *
     * @var $logo
     * @access protected
     */
    public $logo;

    private static $instancia;

    /**
     * Retorna la configuración del proyecto
     *
     * Retorna un objeto de tipo Config definido por el usuario si existe
     * o la configuracion por defecto para Jida.
     *
     * @return \App\Config\Configuracion|Config
     */
    public static function obtener() {

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

    static function modulo($modulo) {

        $config = Config::obtener();

        if (in_array(ucfirst($modulo), $config::$modulos) or in_array($modulo, $config::$modulos)) return true;

        if (isset(self::$modulos[$modulo])) return true;

    }
}