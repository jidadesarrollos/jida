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

class Config {

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

    /**
     * Variable para definicion del logo dentro de la aplicación
     *
     * @var $logo
     * @access protected
     */
    var $logo;
}