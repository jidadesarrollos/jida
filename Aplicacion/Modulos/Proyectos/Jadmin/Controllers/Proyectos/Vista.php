<?php

namespace App\Modulos\Proyectos\Jadmin\Controllers\Proyectos;

use App\Modulos\Proyectos\Modelos\Proyecto as Modelo;
use App\Modulos\Categorias\Modelos\Categoria;
use Jida\Render\JVista;

Trait Vista {

    private function _vista($idCategoria) {

        $modelo = $this->modelo = new Modelo();

        $data = (!empty($idCategoria))
            ? $modelo->consulta()->filtro(['id_categoria' => $idCategoria])->obt()
            : $modelo->consulta()->obt();

        $titulo = "Proyectos";
        if (!empty($idCategoria)) {
            $categoria = new Categoria($idCategoria);
            $titulo = "Proyectos de {$categoria->nombre}";
        }
        $parametros = ['titulos' => ['Nombre', 'Descripcion', 'Identificador', 'Categoria']];

        $vista = new JVista($data, $parametros, $titulo);

        $vista->acciones([
            'Nuevo Proyecto'  => ['href' => "/jadmin/proyectos/gestion/"],
            'Ir a Categorias' => ['href' => '/jadmin/categorias']
        ]);

        $vista->addMensajeNoRegistros('No hay cateogorias registradas.',
            [
                'link'    => $this->obtUrl('gestion'),
                'txtLink' => 'Agregar Proyecto'
            ]);

        $vista->accionesFila([
            [
                'span'  => 'fas fa-images',
                'title' => "ver Proyecto",
                'href'  => "/jadmin/media/{clave}"
            ],
            [
                'span'  => 'fas fa-file-upload',
                'title' => "Subir Imagenes",
                'href'  => "/jadmin/media/subir-imagenes/{clave}"
            ],
            [
                'span'  => 'fa fa-edit',
                'title' => "Editar Proyecto",
                'href'  => "/jadmin/proyectos/gestion/{clave}"
            ],
            [
                'span'        => 'fa fa-trash',
                'title'       => 'Eliminar Proyecto',
                'href'        => "/jadmin/proyectos/eliminar/{clave}",
                'data-jvista' => 'confirm',
                'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar la categoria seleccionada?'
            ]
        ]);

        return $vista;

    }
}