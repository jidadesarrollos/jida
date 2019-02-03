<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Proyectos\Jadmin\Controllers;

use App\Jadmin\Controllers\Jadmin;

use App\Modulos\Proyectos\Modelos\Media as Modelo;
use App\Modulos\Proyectos\Jadmin\Controllers\Media\Carga;
use App\Modulos\Proyectos\Jadmin\Controllers\Media\Gestion;
use App\Modulos\Proyectos\Jadmin\Controllers\Media\Vista;
use App\Modulos\Proyectos\Modelos\{Proyecto};
use Jida\Medios\Mensajes;

class Media extends Jadmin {

    use Vista, Gestion, Carga;

    function index($idProyecto = "") {

        if (empty($idProyecto)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyectos = new Proyecto($idProyecto);

        $titulo = "Lista de Material Multimedia del proyecto " . $proyectos->nombre;
        $data = $this->modelo->consulta(
            ['id_media_proyecto', 'directorio', 'nombre', 'descripcion'])
            ->filtro(['id_proyecto' => $idProyecto])
            ->obt();

        $vista = $this->_vista($data, $titulo, $idProyecto);

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

    function gestion($idProyecto = "", $id = "") { $this->_gestion($idProyecto, $id); }

    function eliminar($id = "") {
        if (!empty($id)) {

            $this->modelo = new Modelo($id);
            $idProyecto = $this->modelo->id_proyecto;
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

        $this->redireccionar("/jadmin/media/index/{$idProyecto}");
    }

}