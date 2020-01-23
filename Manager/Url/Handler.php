<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 20/3/2019
 * Time: 21:24
 */

namespace Jida\Manager\Url;

use Jida\Manager\Url\Url;
use Jida\Medios\Debug;

abstract class Handler {

    protected static $_ce = '80000';
    /**
     * @var string $path Nombre del path a buscar en la url.
     */
    protected $path;
    /**
     * @var boolean $aplica Define si el handler aplica para la url validada
     */
    static public $aplica;
    /**
     * @var Url Objeto Url.
     */
    protected $url;

    protected $nombre;

    function __construct(Url $url) {
        $this->url = $url;
    }

    function validacion() {

        $parametro = $this->url->proximoParametro();

        if (strtolower($parametro) !== $this->path) {

            $this->url->reingresarParametro($parametro);
            return false;

        }

        return self::$aplica = true;

    }

    function procesar() {

        if (!$this->validacion()) {
            return;
        }

        return $this->definir();

    }

    function nombre() {
        return $this->nombre;
    }

    /**
     * @return mixed
     */
    abstract function definir();

}