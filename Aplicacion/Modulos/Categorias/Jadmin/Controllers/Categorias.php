<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Categorias\Jadmin\Controllers;

use App\Modulos\Categorias\Modelos\Categorias as Modelo;
use Jida\Jadmin\Controllers\JControl;
use Jida\Medios\Debug;
use Jida\Medios\Mensajes;
use Jida\Render\Formulario;
use Jida\Render\JVista;

class Categorias extends JControl {

    function index() {

        $this->modelo = new Modelo();
        $data = $this->modelo->consulta()->obt();
        $parametros = ['titulos' => ['Nombre', 'Descripcion', 'Identificador']];
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
                'href'  => "/jadmin/categorias/gestion/{clave}"
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

    function gestion($id = "") {

        $modelo = new Modelo($id);

        $form = new Formulario('Categorias/Categorias', $id);

        $form->action = $this->obtUrl('', [$id]);

        if ($this->post('btnFormularioCategorias')) {
            if ($form->validar()) {
                if ($modelo->salvar($this->post())) {
                    $condicion = empty($id) ? 'almacenada' : 'modificada';
                    Mensajes::almacenar(Mensajes::suceso("Categoria {$condicion} correctamente"));
                    $this->redireccionar("/jadmin/categorias");
                }
                else Mensajes::almacenar(Mensajes::error('Error al guardar la informacion'));
            }
            else Mensajes::almacenar(Mensajes::error('Informacion no validad'));
        }

        $this->data([
            'vista' => $form->render()
        ]);

    }

}