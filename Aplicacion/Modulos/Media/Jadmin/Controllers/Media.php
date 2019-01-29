<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Media\Jadmin\Controllers;

use App\Jadmin\Controllers\Jadmin;

use App\Modulos\Media\Jadmin\Controllers\Media\Carga;
use App\Modulos\Media\Jadmin\Controllers\Media\Gestion;
use App\Modulos\Media\Jadmin\Controllers\Media\Vista;
use App\Modulos\Proyectos\Modelos\{Proyecto};
use Jida\Medios\Mensajes;

class Media extends Jadmin {

    use Vista, Gestion, Carga;

    function index($idFk = "") {

        if (empty($idFk)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyectos = new Proyecto($idFk);

        $titulo = "Lista de Material Multimedia del proyecto " . $proyectos->nombre;
        $data = $this->modelo->consulta(
            ['id_media', 'url_media', 'nombre', 'descripcion', 'externa'])
            ->filtro(['id_proyecto' => $idFk])
            ->obt();

        $vista = $this->_vista($data, $titulo);

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
        $this->_gestion($idFk, $id);

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

        $this->redireccionar("/jadmin/media/index/{$idFk}");
    }

}