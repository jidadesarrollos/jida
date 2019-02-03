<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Proyectos\Jadmin\Controllers;

use App\Jadmin\Controllers\Jadmin;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Proyectos\Jadmin\Controllers\Media\Gestion;
use App\Modulos\Proyectos\Jadmin\Controllers\Proyectos\Vista;
use App\Modulos\Proyectos\Modelos\Proyecto as Modelo;
use Jida\Medios\Mensajes;

class Proyectos extends Jadmin {

    use Gestion, Vista;

    function index($idCategoria = "") {

        $vista = $this->_vista($idCategoria);

        $render = $vista->render(
            function ($datos) {
                foreach ($datos as $key => &$proyecto) {
                    $categoria = new Categoria($proyecto['id_categoria']);
                    $proyecto['id_categoria'] = $categoria->nombre;
                }

                return $datos;
            }
        );

        $this->data(['vista' => $render]);

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