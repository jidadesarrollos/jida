<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Proyectos\Jadmin\Controllers;

use App\Modulos\Categorias\Modelos\Categorias;
use App\Modulos\Proyectos\Modelos\Proyectos as Modelo;
use Jida\Jadmin\Controllers\JControl;
use Jida\Medios\Debug;
use Jida\Medios\Mensajes;
use Jida\Render\Formulario;
use Jida\Render\JVista;

class Proyectos extends JControl {

    function index($idfk = "") {

        $this->modelo = new Modelo();
        $categoria = new Categorias($idfk);
        $data = $this->modelo->consulta();

        if (!empty($idfk)) {
            $data = $data->filtro(['id_categoria' => $idfk])->obt();
        }
        else {
            $data = $data->obt();
        }

        $parametros = ['titulos' => ['Nombre', 'Descripcion', 'Identificador', 'Categoria']];
        $vista = new JVista($data, $parametros, "Listado de Proyectos" . ((!empty($idfk)) ? " de " . $categoria->nombre : "."));

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

        $render = $vista->render();
        $this->data([
            'vista' => $render
        ]);

    }

    function gestion($id = "") {

        $modelo = new Modelo($id);

        $form = new Formulario('Proyectos/Proyectos', $id);

        $form->action = $this->obtUrl('', [$id]);

        if ($this->post('btnFormularioProyectos')) {
            if ($form->validar()) {
                if ($modelo->salvar($this->post())) {
                    $condicion = empty($id) ? 'almacenada' : 'modificada';
                    Mensajes::almacenar(Mensajes::suceso("Proyecto {$condicion} correctamente"));
                    $this->redireccionar("/jadmin/categorias");
                }
                else Mensajes::almacenar(Mensajes::error('Error al guardar la informacion'));
            }
            else Mensajes::almacenar(Mensajes::error('Informacion no valida'));
        }

        $this->data([
            'vista' => $form->render()
        ]);

    }

    function eliminar($id) {

        if (!empty($id)) {

            $modelo = new Modelo($id);
            if ($modelo->eliminar()) {
                Mensajes::almacenar(Mensajes::suceso("Proyecto eliminado correctamente."));
            }
            else {
                Mensajes::almacenar(Mensajes::error("No se pudo eliminar el Proyecto."));
            }

        }
        else {
            Mensajes::almacenar(Mensajes::error("El proyecto indicado no existe."));
        }

        $this->redireccionar("/jadmin/proyectos");
    }

}