<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Categorias\Jadmin\Controllers;

use App\Jadmin\Controllers\Jadmin;
use App\Modulos\Categorias\Modelos\Categoria as Modelo;
use Jida\Manager\Estructura;
use Jida\Medios\Cadenas;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;
use Jida\Medios\Mensajes;
use Jida\Render\Formulario;
use Jida\Render\JVista;

class Categorias extends Jadmin {

    function index() {

        $vista = $this->_vista();
        $render = $vista->render();
        $this->data([
            'vista' => $render
        ]);

    }

    private function _vista() {

        $parametros = ['titulos' => ['Nombre', 'Descripcion', 'Identificador']];

        $this->modelo = new Modelo();
        $data = $this->modelo->consulta()->obt();

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
                'href'        => "/jadmin/categorias/eliminar/{clave}",
                'data-jvista' => 'confirm',
                'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar la categoria seleccionada?'
            ]
        ]);

        return $vista;
    }

    function gestion($id = "") {

        $modelo = new Modelo($id);

        $form = new Formulario('Categorias/Categorias', $id);

        $form->action = $this->obtUrl('', [$id]);

        if ($this->post('btnFormularioCategorias')) {

            if ($form->validar()) {

                $modelo->identificador = Cadenas::guionCase($this->post('nombre'));
                $this->_validarDirectorio($modelo->identificador);
                if ($modelo->salvar($this->post())) {

                    $condicion = empty($id) ? 'creada' : 'modificada';

                    Mensajes::crear('suceso', "Categoria {$condicion} correctamente");

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

    private function _validarDirectorio($nombre) {

        $ruta = trim(Estructura::$directorio . '/htdocs/media/' . $nombre);

        if (Directorios::validar($ruta)) return true;

        Directorios::crear($ruta);

    }

    function eliminar($id) {

        if (!empty($id)) {

            $modelo = new Modelo($id);
            if ($modelo->eliminar()) {
                Mensajes::almacenar(Mensajes::suceso("Categoria eliminada correctamente."));
            }
            else {
                Mensajes::almacenar(Mensajes::error("No se pudo eliminar la categoria."));
            }

        }
        else {
            Mensajes::almacenar(Mensajes::error("La categoria indicada no existe."));
        }

        $this->redireccionar("/jadmin/categorias");
    }

}