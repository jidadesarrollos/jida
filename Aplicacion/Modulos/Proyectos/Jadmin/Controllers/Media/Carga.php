<?php
/**
 * @see \Jida\Core\Controlador
 */

namespace App\Modulos\Proyectos\Jadmin\Controllers\Media;

use App\Modulos\Proyectos\Modelos\Media;
use App\Modulos\Proyectos\Modelos\Proyecto;
use Jida\Configuracion\Config;
use Jida\Manager\Estructura;
use Jida\Medios\Archivos\Imagen;
use Jida\Medios\Archivos\ProcesadorCarga;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Proyectos\Modelos\Media as Modelo;
use Jida\Medios\Debug;
use Jida\Modelos\ObjetoMedia;
use Jida\Modulos\Proyectos\Modelo\Objeto;

Trait Carga {

    private function _procesarCarga($idProyecto, $proyecto) {

        $categoria = $proyecto->Categoria;

        $imagen = $this->files('imagen');

        $procesador = new ProcesadorCarga('imagen');
        $media = new Media();

        $configuracion = Config::obtener();

        if ($procesador->validar()) {

            $directorio = Estructura::$directorio . "/htdocs/imagenes/{$categoria->identificador}/{$proyecto->identificador}";
            $archivos = $procesador->mover($directorio)->archivos();

            $ok = true;
            $datos = $urls = [];

            foreach ($archivos as $item => $archivo) {

                $imagen = new Imagen($archivo->directorio());

                if (!$imagen->redimensionar($configuracion::REDIMENSION_IMAGEN)) {
                    $ok = false;
                    continue;
                }

                array_push($datos, $this->_data($imagen, $idProyecto));
                array_push($urls, $imagen->obtUrls());

            }

            $media->salvarTodo($datos);

            $this->respuestaJson([
                'procesado'  => $ok,
                'urls'       => $urls,
                'directorio' => $directorio
            ]);

        }

    }

    private function _data(Imagen $imagen, $idProyecto) {

        return [
            'nombre'      => $imagen->nombre,
            'tipo_media'  => $imagen->tipo,
            'directorio'  => $imagen->directorio,
            'id_proyecto' => $idProyecto,
            'meta_data'   => json_encode(['urls' => $imagen->obtUrls()]),
            'id_idioma'   => 'esp'
        ];

    }

}