<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 11/01/19
 * Time: 03:03 PM
 */

namespace App\Modulos\Proyectos\Modelos;

use Jida\Core\Modelo;
use Jida\Manager\Estructura;
use Jida\Medios\Debug;

class Proyecto extends Modelo {

    var $id_proyecto;
    var $nombre;
    var $descripcion;
    var $identificador;
    var $id_categoria;

    protected $tieneUno = [
        '\\App\\Modulos\\Categorias\\Modelos\\Categoria' => [
            'pk' => 'id_categoria',
            'fk' => 'id_categoria'
        ]
    ];

    protected $tieneMuchos = [
        'Media' => [
            'objeto' => "\\App\\Modulos\\Proyectos\\Modelos\\Media",
            'campos' => [
                'id_media_proyecto',
                'nombre',
                'directorio',
                'tipo_media',
                'leyenda',
                'meta_data',
                'id_idioma'
            ]
        ]
    ];

    protected $tablaBD = "m_proyectos";
    protected $pk = 'id_proyecto';

    function media() {

        $media = [];

        foreach ($this->Media as $id => $mediaItem) {

            $item = array_merge($mediaItem, [
                'url' => json_decode($mediaItem['meta_data'], true)['urls']
            ]);
            array_walk($item['url'],
                function (&$item) {
                    $item = Estructura::$urlBase . $item;
                });

            unset($mediaItem['meta_data']);
            array_push($media, $item);

        }

        return $media;

    }

}