<?php
/**
 * @see \Jida\Core\Controlador
 */

namespace App\Modulos\Media\Jadmin\Controllers\Media;

use App\Modulos\Proyectos\Modelos\Proyecto;
use Jida\Medios\Archivos\Imagen;
use Jida\Medios\Archivos\ProcesadorCarga;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Media\Modelos\Media as Modelo;

Trait Carga {

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
                $this->redireccionar("/jadmin/media/index/{$idFk}");

            }
            else Mensajes::almacenar(Mensajes::error("Una o mas fotografias no son validad"));

        }

        $this->data([
            'idFk'   => $idFk,
            'nombre' => $proyecto->nombre
        ]);

    }

}