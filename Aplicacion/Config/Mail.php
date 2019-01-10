<?php

/**
 * Clase Modelo
 * @author Julio Rodriguez
 * @package
 * @version
 * @category
 */

namespace App\Config;

class Mail {

    var $index = [
        'Username'   => 'pruebas@jidadesarrollos.com',
        'Password'   => 'pru3b45',
        'From'       => 'pruebas@jidadesarrollos.com',
        'FromName'   => NOMBRE_APP,
        'Host'       => 'gtr.websitewelcome.com',
        'Port'       => 465,
        'SMTPSecure' => 'ssl',
    ];
    var $data = [
        'url_sitio'            => URL_APP_PUBLICA,
        'url_app'              => URL_APP_PUBLICA,
        'logo_app'             => 'http://jidadesarrollos.com/htdocs/img/jida/jida_solid.png',
        'url_app_fb'           => URL_FB,
        'url_app_twitter'      => URL_TWITTER,
        'url_imagenes'         => URL_IMAGENES,
        'url_media_app'        => URL_MEDIA_CORREOS,
        'url_app_instagram'    => URL_INSTAGRAM,
        'cuenta_twitter_app'   => CUENTA_TWITTER,
        'cuenta_instagram_app' => CUENTA_INSTAGRAM,
        'nombre_app'           => TITULO_SISTEMA
    ];

}
