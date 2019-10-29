<?php
/**
 * Manager de etiquetas meta para el Head de una página o Layout
 *
 * ce 0
 */

namespace Jida\Manager\Vista;

use Jida\Core\Selector;

class Meta {

    private $_ce = 10010;

    /**
     * Imprime la información meta HTML configurada para la página actual
     *
     * Si no se ha configurado nada, se intentaran imprimir los valores por defectos
     * que pueden estar configurados con las constantes APP_DESCRIPCION, APP_IMAGEN y APP_AUTOR
     *
     * @method printHeadTags
     *
     */

    static function imprimir($data) {

        $meta = "";
        $itemprop = "";
        $initTab = 0;
        //Titulo de La pagina

        if (count($data->meta) > 0) {
            $metaAdicional = "";

            foreach ($data->meta as $key => $dataMeta) {

                $metaAdicional .= Selector::crear('meta', $dataMeta, null, 2);
            }
            //$itemprop.=$metaAdicional;
            $meta .= $metaAdicional;
        }
        if ($data->google_verification != false) {
            $meta .= Selector::crear(
                'meta',
                [
                    "name"    => "google-site-verification",
                    "content" => $data->google_verification
                ]);
        }
        if ($data->responsive) {

            $meta .= Selector::crear(
                'meta',
                [
                    "name"    => "viewport",
                    'content' => "width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
                ]);
        }
        if (!empty($data->title)) {
            $meta .= Selector::crear('TITLE', null, $data->title, 0);
            $initTab = 2;
            $meta .= Selector::crear(
                'meta',
                [
                    'name'    => 'title',
                    'content' => $data->title
                ],
                null,
                $initTab);
        }
        if (!empty($data->meta_descripcion)) {
            $meta .= Selector::crear(
                'meta',
                [
                    'name'    => 'description',
                    'content' => $data->meta_descripcion
                ],
                null,
                $initTab);
            $itemprop .= Selector::crear('meta',
                [
                    'itemprop' => 'description',
                    'content'  => $data->meta_descripcion
                ],
                null,
                2);
        }
        if (!empty($data->meta_autor)) {
            $meta .= Selector::crear('meta',
                [
                    'name'    => 'author',
                    'content' => $data->meta_autor
                ],
                null,
                2);
            $itemprop .= Selector::crear('meta',
                [
                    'itemprop' => 'author',
                    'content'  => $data->meta_autor
                ],
                null,
                2);
        }
        if (!empty($data->meta_image)) {
            $meta .= Selector::crear('meta',
                [
                    'name'    => 'image',
                    'content' => $data->meta_image
                ],
                null,
                2);
            $itemprop .= Selector::crear('meta',
                [
                    'itemprop' => 'image',
                    'content'  => $data->meta_image
                ],
                null,
                2);
        }

        if (count($data->meta) > 0) {
            $metaAdicional = "\t\t<!---Tags Meta-----!>\n";

            foreach ($data->meta as $key => $dataMeta) {

                $metaAdicional .= Selector::crear('meta', $dataMeta, null, 2);
            }
            //$itemprop.=$metaAdicional;
        }
        if (!$data->robots) {
            $itemprop .= Selector::crear('meta',
                [
                    'name'    => 'robots',
                    'content' => 'noindex'
                ],
                null,
                2);
        }
        //URL CANNONICA
        if (!empty($data->url_canonical)) {
            $itemprop .= Selector::crear('link',
                [
                    'rel'  => 'canonical',
                    'href' => $data->url_canonical
                ],
                null,
                2);
        }

        return $meta . $itemprop . "\n";

    }

}