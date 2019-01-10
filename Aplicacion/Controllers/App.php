<?php

namespace App\Controllers;

class App extends \Jida\Core\Controller {

    function __construct() {

        parent::__construct();

        $this->layout('default');

        $js = [];

        $this->dv->incluirJS($js, URL_BASE . URL_HTDOCS_TEMAS . '');

        $this->data([
            'title'   => NOMBRE_APP,
            'urlTema' => URL_BASE . URL_HTDOCS_TEMAS . 'default/'
        ]);

    }

}
