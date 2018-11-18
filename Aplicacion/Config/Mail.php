<?php
/**
 * Clase Modelo
 * @author Julio Rodriguez
 * @package
 * @version
 * @category
 */

namespace App\Config;

use Jida\Configuracion\Config;

class Mail {

    var $index = [
        'Username'   => 'pruebas@jidadesarrollos.com',
        'Password'   => 'pru3b45',
        'From'       => 'pruebas@jidadesarrollos.com',
        'FromName'   => Configuracion::NOMBRE_APP,
        'Host'       => 'gtr.websitewelcome.com',
        'Port'       => 465,
        'SMTPSecure' => 'ssl'
    ];

    var $data = [
        'url_sitio'            => Configuracion::URL_ABSOLUTA,
        'url_app'              => Configuracion::URL_ABSOLUTA,
        'logo_app'             => Configuracion::URL_ABSOLUTA . 'htdocs/img/logo.png',
        'url_app_fb'           => URL_FACEBOOK,
        'url_app_twitter'      => URL_TWITTER,
        'url_imagenes'         => URL_IMAGENES,
        'url_media_app'        => URL_MEDIA_CORREOS,
        'url_app_instagram'    => URL_INSTAGRAM,
        'cuenta_twitter_app'   => CUENTA_TWITTER,
        'cuenta_instagram_app' => CUENTA_INSTAGRAM,
        'nombre_app'           => TITULO_SISTEMA
    ];

}
