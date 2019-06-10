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
     * Imprime las etiquetas Open Graph configurada para la página actual
     *
     * @method imprimir
     *
     */

    static function imprimir($data) {

        $html = "";

        if (count($data->og) > 0) {

            $selector = "";

            foreach ($data->og as $property => $content) {

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