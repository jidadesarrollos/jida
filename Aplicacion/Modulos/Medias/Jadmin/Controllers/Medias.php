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
use Jida\Manager\Estructura;
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
        $data = $this->modelo->consulta()->filtro(['id_proyectos' => $idFk])->obt();
        $parametros = ['titulos' =>
                           ['Nombre', 'Descripcion', 'Identificador', 'Proyecto', 'Pequeno', 'Medio', 'Grande']];

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

        $render = $vista->render();

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
                    Estructura::$directorio . "/htdocs/{$categoria->nombre}/{$proyecto->nombre}"
                );
                foreach ($archivos as $archivo) {
                    $this->modelo->salvar([
                        "nombre"       => "Test",
                        "url_media"    => $archivo,
                        "id_proyectos" => $idFk
                    ]);
                }
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