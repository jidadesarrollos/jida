<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Categoria\Jadmin\Controllers;

use Jida\Jadmin\Controllers\Jadmin;
use App\Modulos\Categoria\Modelos\Categoria as Modelo;
use Jida\Render\Formulario;
use Jida\Render\JVista;

class Categoria extends Jadmin {


    function index () {

        $this->modelo = new Modelo();

        $data = $this->modelo->consulta();

        $parametros = ['titulos' => ['Nombre', 'Descripcion']];

        $vista = new JVista($data, $parametros, 'Listado de Categorias');

        $vista->acciones([
                             'Nueva Categoria' => ['href' => $this->obtUrl('gestion')],
                         ]);

        $vista->addMensajeNoRegistros('No hay cateogorias registradas.',
                                      [
                                          'link'    => $this->obtUrl('gestion'),
                                          'txtLink' => 'Agregar Categoria'
                                      ]);

        $vista->accionesFila([
                                 [
                                     'span'  => 'fa fa-edit',
                                     'title' => "Editar Categoria",
                                     'href'  => $this->obtUrl('gestion',
                                                              [
                                                                  '{clave}'
                                                              ])
                                 ],
                                 [
                                     'span'        => 'fa fa-trash',
                                     'title'       => 'Eliminar Categoria',
                                     'href'        => $this->obtUrl('eliminar', ['{clave}']),
                                     'data-jvista' => 'confirm',
                                     'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar la categoria seleccionada?'
                                 ]
                             ]);

        $render = $vista->render();
        $this->data([
                        'vista' => $render
                    ]);

    }

    function gestion ($id = "") {

        $form = new Formulario('FormularioCategorias', $id);

        $form->action = $this->obtUrl('', [$id]);

    }

}