<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Proyecto\Jadmin\Controllers;

use Jida\Jadmin\Controllers\Jadmin;
use App\Modulos\Proyecto\Modelos\Proyecto as Modelo;
use Jida\Render\Formulario;
use Jida\Render\JVista;

class Proyecto extends Jadmin {

    function index () {

        $this->modelo = new Modelo();

        $data = $this->modelo->consulta();

        $parametros = ['titulos' => ['Nombre', 'Descripcion','Categoria']];

        $vista = new JVista($data, $parametros, 'Listado de Proyectos');

        $vista->acciones([
                             'Nueva Proyecto' => ['href' => $this->obtUrl('gestion')],
                         ]);

        $vista->addMensajeNoRegistros('No hay proyectos registradas.',
                                      [
                                          'link'    => $this->obtUrl('gestion'),
                                          'txtLink' => 'Agregar Proyecto'
                                      ]);

        $vista->accionesFila([
                                 [
                                     'span'  => 'fa fa-edit',
                                     'title' => "Editar Proyecto",
                                     'href'  => $this->obtUrl('gestion',
                                                              [
                                                                  '{clave}'
                                                              ])
                                 ],
                                 [
                                     'span'        => 'fa fa-trash',
                                     'title'       => 'Eliminar Proyecto',
                                     'href'        => $this->obtUrl('eliminar', ['{clave}']),
                                     'data-jvista' => 'confirm',
                                     'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el proyecto seleccionado?'
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