<?php

namespace App\Modulos\Proyectos\Jadmin\Controllers\Media;

use Jida\Render\JVista;

Trait Vista {

    function _vista($data, $titulo, $idProyecto) {

        $parametros = [
            'titulos' => ['Foto', 'Nombre', 'Descripcion', 'Origen']
        ];
        $vista = new JVista($data, $parametros, $titulo);

        $vista->acciones([
            'Agregar Fotos'      => ['href' => "/jadmin/media/subir-imagenes/{$idProyecto}/"],
            'Volver a Proyectos' => ['href' => '/jadmin/proyectos/']
        ]);

        $vista->addMensajeNoRegistros('No hay materia multimedia registrado.',
            [
                'link'    => "/jadmin/proyectos/media/carga/{$idProyecto}/",
                'txtLink' => 'Subir Fotografias'
            ]);

        $vista->accionesFila([
            [
                'span'  => 'fa fa-edit',
                'title' => "Editar Fotografia",
                'href'  => "/jadmin/proyectos/media/gestion/{$idProyecto}/{clave}"
            ],
            [
                'span'        => 'fa fa-trash',
                'title'       => 'Eliminar Fotografia',
                'href'        => "/jadmin/proyectos/media/eliminar/{clave}",
                'data-jvista' => 'confirm',
                'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar la categoria seleccionada?'
            ]
        ]);

        return $vista;

    }
}