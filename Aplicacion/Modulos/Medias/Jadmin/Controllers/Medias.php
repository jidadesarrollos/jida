<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Medias\Jadmin\Controllers;

use App\Jadmin\Controllers\Jadmin;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Medias\Modelos\Media as Modelo;
use App\Modulos\Proyectos\Modelos\Proyecto;
use Jida\Medios\Archivos\Imagen;
use Jida\Medios\Archivos\ProcesadorCarga;
use Jida\Medios\Mensajes;
use Jida\Render\Formulario;
use Jida\Render\JVista;

class Medias extends Jadmin {

    function index($idFk = "") {
        if (empty($idFk)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyectos = new Proyecto($idFk);
        $data = $this->modelo->consulta(['id_media', 'url_media', 'nombre', 'descripcion', 'externa'])->filtro(['id_proyecto' => $idFk])->obt();
        $parametros = ['titulos' =>
                           ['Foto', 'Nombre', 'Descripcion', 'Origen']];

        $vista = new JVista($data, $parametros, "Lista de Material Multimedia del proyecto " . $proyectos->nombre . ":");

        $vista->acciones([
            'Agregar Fotos'      => ['href' => "/jadmin/medias/subir-imagenes/{$idFk}/"],
            'Volver a Proyectos' => ['href' => '/jadmin/proyectos/']
        ]);

        $vista->addMensajeNoRegistros('No hay materia multimedia registrado.',
            [
                'link'    => "/jadmin/medias/subir-imagenes/{$idFk}/",
                'txtLink' => 'Subir Fotografias'
            ]);

        $vista->accionesFila([
            [
                'span'  => 'fa fa-edit',
                'title' => "Editar Fotografia",
                'href'  => "/jadmin/medias/gestion/{$idFk}/{clave}"
            ],
            [
                'span'        => 'fa fa-trash',
                'title'       => 'Eliminar Fotografia',
                'href'        => "/jadmin/medias/eliminar/{clave}",
                'data-jvista' => 'confirm',
                'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar la categoria seleccionada?'
            ]
        ]);

        $render = $vista->render(
            function ($datos) {
                foreach ($datos as $key => &$media) {
                    $imagen = new Modelo($media['id_media']);
                    $url = substr($imagen->thumbnail(150, 150), 1);
                    $media['url_media'] = "<img src='{$url}' class='img-thumbnail' />";
                    $media['externa'] = isset($media['externa']) ? "Remoto" : "Local";
                }

                return $datos;
            }

        );

        $this->data(['vista' => $render]);
    }

    function gestion($idFk = "", $id = "") {
        if (empty($idFk)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyecto = new Proyecto($idFk);

        $form = new Formulario('Medias/Medias', $id);

        $form->action = $this->obtUrl('', [$id]);

        if ($this->post('btnFormularioMedia')) {
            if ($form->validar()) {
                if ($this->modelo->salvar($this->post())) {
                    $condicion = empty($id) ? 'almacenada' : 'editada';
                    Mensajes::almacenar(Mensajes::suceso("Fotografia {$condicion} correctamente"));
                    $this->redireccionar("/jadmin/medias/index/{$idFk}");
                }
                else Mensajes::almacenar(Mensajes::error('Error al guardar la informacion'));
            }
            else Mensajes::almacenar(Mensajes::error('Informacion no valida'));
        }


        $this->data([
            'form' => $form->render(),
            'idFk' => $idFk
        ]);

    }

    function subirImagenes($idFk = "") {
        if (empty($idFk)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyecto = new Proyecto($idFk);
        $categoria = new Categoria($proyecto->id_categoria);

        if ($this->post('btnMedias')) {

            $procesador = new ProcesadorCarga('imagenes');
            if ($procesador->validar()) {

                $archivos = $procesador->mover(
                    "/htdocs/{$categoria->nombre}/{$proyecto->nombre}"
                );

                $objetos = [];
                foreach ($archivos as $archivo) {
                    $objeto = [];
                    $objeto['nombre'] = " ";
                    $objeto['url_media'] = $archivo;
                    $objeto['id_proyecto'] = $idFk;
                    array_push($objetos, $objeto);
                    $imagen = new Imagen($archivo);
                    $imagen->redimensionar(150, 150);
                    $imagen->redimensionar(300, 300);
                    $imagen->redimensionar(600, 600);
                    $imagen->redimensionar(1200, 1200);
                }

                $this->modelo->salvarTodo($objetos);

                Mensajes::almacenar(Mensajes::suceso("Imagenes Guardadas correctamente."));
                $this->redireccionar("/jadmin/medias/index/{$idFk}");

            }
            else Mensajes::almacenar(Mensajes::error("Una o mas fotografias no son validad"));

        }

        $this->data([
            'idFk'   => $idFk,
            'nombre' => $proyecto->nombre
        ]);

    }

    function eliminar($id = "") {
        if (!empty($id)) {

            $this->modelo = new Modelo($id);
            $idFk = $this->modelo->id_proyecto;
            unlink($this->modelo->thumbnail(150, 150));
            unlink($this->modelo->thumbnail(300, 300));
            unlink($this->modelo->thumbnail(600, 600));
            unlink($this->modelo->thumbnail(1200, 1200));
            unlink($this->modelo->url_media);
            if ($this->modelo->eliminar()) {
                Mensajes::almacenar(Mensajes::suceso("La foto fue eliminada correctamente."));
            }
            else {
                Mensajes::almacenar(Mensajes::error("No se pudo eliminar la foto."));
            }

        }
        else {
            Mensajes::almacenar(Mensajes::error("La foto indicada no existe."));
        }

        $this->redireccionar("/jadmin/medias/index/{$idFk}");
    }

}