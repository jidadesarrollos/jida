<?php
/**
 * Created by PhpStorm.
 * User: Rosmy Rodriguez
 * Date: 20/8/2018
 * Time: 11:00 AM
 */

namespace Jida\Jadmin\Layout;

use App\Config\Configuracion;

class Layout {

    private $css = [
        "bt" => URL_BASE . DS . Configuracion::PATH_JIDA . '/Jadmin/Layout/jadmin/htdocs/bootstrap/dist/css/bootstrap.css'
    ];

    private $js = [
        "bt" => URL_BASE . DS . Configuracion::PATH_JIDA . '/Jadmin/Layout/jadmin/htdocs/bootstrap/dist/js/bootstrap.js'
    ];

    function __construct () {

    }

    public function css () {

        return $this->css;

    }


    public function js () {

        return $this->js;

    }

}