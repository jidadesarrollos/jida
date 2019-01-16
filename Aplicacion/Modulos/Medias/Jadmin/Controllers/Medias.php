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
use Jida\Medios\Archivos\ProcesadorCarga;
use Jida\Medios\Debug;
use Jida\Medios\Mensajes;
use Jida\Render\JVista;

class Medias extends Jadmin {

    function index($idFk = "") {
        if (empty($idFk)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyectos = new Proyecto($idFk);
        $data = $this->modelo->consulta(['id_media', 'url_media', 'nombre', 'descripcion', 'externa'])->filtro(['id_proyectos' => $idFk])->obt();
        $parametros = ['titulos' =>
                           ['Foto', 'Nombre', 'Descripcion', 'Origen']];

        $vista = new JVista($data, $parametros, "Lista de Material Multimedia del proyecto " . $proyectos->nombre . ":");

        $vista->acciones([
            'Agregar Fotos'      => ['href' => "/jadmin/medias/subir-imagenes/{$idFk}/"],
            'Volver a Proyectos' => ['href' => '/jadmin/proyectos/']
        ]);

        $vista->addMensajeNoRegistros('No hay cateogorias registradas.',
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
                    $url = substr($media['url_media'], 1);
                    $media['url_media'] = "<img src='{$url}' class='img-thumbnail' />";
                    $media['externa'] = isset($media['externa']) ? "Remoto" : "Local";
                }

                return $datos;
            }

        );

        $this->data(['vista' => $render]);
    }

    function gestion($idFk = "") {
        if (empty($idFk)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->data([
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
                    "./htdocs/{$categoria->nombre}/{$proyecto->nombre}"
                );

                $objetos = [];
                foreach ($archivos as $archivo) {
                    $objeto = [];
                    $objeto['nombre'] = "";
                    $objeto['url_media'] = $archivo;
                    $objeto['id_proyectos'] = $idFk;
                    array_push($objetos, $objeto);
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

}