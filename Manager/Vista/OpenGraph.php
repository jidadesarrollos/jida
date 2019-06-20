<?php
/**
 * Manager de etiquetas open graph para el Head de una página o Layout
 *
 */

namespace Jida\Manager\Vista;

use Jida\Medios\Debug;
use Jida\Render\Selector;

class OpenGraph {

    /**
     * Rendriza las etiquetas Open Graph configuradas para la página actual
     *
     * @method render
     * @param $data arreglo de valores personalizados para crear las etiquetas meta open graph
     *
     */

    static function render($data) {

        $html = "";

        if (count($data) > 0) {

            $selector = "";

            foreach ($data as $property => $content) {

                $selector .= $html = Selector::crear(
                    'meta',
                    [
                        'property' => $property,
                        'content'  => $content
                    ],
                    null,
                    2);

            }

            $html = $selector;

        }

        return $html;

    }

}