<?php
/**
 * @see \Jida\Core\Controlador
 */

namespace App\Modulos\Proyectos\Jadmin\Controllers\Media;

use App\Modulos\Proyectos\Modelos\Media;
use App\Modulos\Proyectos\Modelos\Proyecto;
use Jida\Manager\Estructura;
use Jida\Medios\Archivos\Imagen;
use Jida\Medios\Archivos\ProcesadorCarga;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Proyectos\Modelos\Media as Modelo;
use Jida\Medios\Debug;
use Jida\Modelos\ObjetoMedia;
use Jida\Modulos\Proyectos\Modelo\Objeto;

Trait Carga {

    function carga($idProyecto = "") {

        $this->layout()->incluirJS([
            'modulo/jCargaFile.js',
            'modulo/form.js'
        ]);

        if (empty($idProyecto)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyecto = new Proyecto($idProyecto);

        if (!$proyecto->id_proyecto) {
            $this->redireccionar('/jadmin/proyectos/');

        }

        if ($this->files('imagen')) {
            $this->_procesarCarga($idProyecto, $proyecto);
        }

        $this->data([
            'idFk'     => $idProyecto,
            'nombre'   => $proyecto->nombre,
            'media'    => $proyecto->media(),
            'urlEnvio' => Estructura::$url
        ]);

    }

    private function _procesarCarga($idProyecto, $proyecto) {

        $categoria = $proyecto->Categoria;

        $imagen = $this->files('imagen');

        $procesador = new ProcesadorCarga('imagen');
        $media = new Media();

        if ($procesador->validar()) {

            $directorio = Estructura::$directorio . "/htdocs/imagenes/{$categoria->identificador}/{$proyecto->identificador}";
            $archivos = $procesador->mover($directorio)->archivos();

            $ok = true;
            $datos = [];
            $urls = [];

            foreach ($archivos as $item => $archivo) {

                $imagen = new Imagen($archivo->directorio());

                if (!$imagen->redimensionar(['150x150', '300x300'])) {
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