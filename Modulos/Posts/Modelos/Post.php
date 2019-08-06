<?php

namespace Jida\Modulos\Posts\Modelos;

use Jida\Core\Modelo;
use Jida\Medios\Cadenas;
use Jida\Medios\Debug;

class Post extends Modelo {
    private static $instance = null;
    public $id_post;
    public $post;
    public $resumen;
    public $contenido;
    public $meta_descripcion;
    public $identificador;
    public $relevancia;
    public $id_media_principal;
    public $id_seccion;
    public $fecha_publicacion;
    public $numero_visitas;
    public $id_estatus_post;
    public $visibilidad;
    public $nombre_post;
    public $tipo;
    public $data;
    public $texto_original;
    public $id_idioma;
    protected $tablaBD = "s_posts";
    protected $pk = "id_post";

    static function obtPostFromSlug($slug) {

        if (self::$instance == null) {
            self::$instance = new Post();
        }

        $id_post = self::$instance->consulta(['id_post'])->filtro(['identificador' => $slug])->obt();
        $id_post = $id_post[0]['id_post'];

        $post = new Post($id_post);

        return $post;

    }

    function salvar($data = "") {

        $dataMod = $data;
        $slug = Cadenas::guionCase($data['post']);
        $registros = $this->consulta(['identificador'])->obt();
        $count = 0;
        foreach ($registros as $registro) {
            if (strpos($registro, $slug) !== false) {
                $count++;
            }
        }

        if ($count) {
            $dataMod['identificador'] = $slug . "-{$count}";
        }

        return parent::salvar($dataMod); // TODO: Change the autogenerated stub
    }

}