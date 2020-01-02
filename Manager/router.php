<?php

namespace Jida\Manager;

use Jida\Medios\Debug;

Class Router {

    static function rewrite() {

        chdir(__DIR__);

        if (isset($_GET['url'])) {
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            unset($_GET['url']);
        }
        else {
            $url = filter_input(INPUT_SERVER, 'PATH_INFO', FILTER_SANITIZE_URL);
        }

        Debug::imprimir([0, $_SERVERSER]);
        $url = str_replace([
            '.php',
            '.html',
            '.htm'
        ], '', $url);

        $url = explode('/', $url);
        $url = implode("/", $url);
        if ($url === 'index') $url = '';

        return $url;

    }
}