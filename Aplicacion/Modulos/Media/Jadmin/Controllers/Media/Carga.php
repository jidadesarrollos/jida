<?php
/**
 * @see \Jida\Core\Controlador
 */

namespace App\Modulos\Media\Jadmin\Controllers\Media;

use App\Modulos\Proyectos\Modelos\Proyecto;
use Jida\Manager\Estructura;
use Jida\Medios\Archivos\Imagen;
use Jida\Medios\Archivos\ProcesadorCarga;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Media\Modelos\Media as Modelo;
use Jida\Medios\Debug;

Trait Carga {

    private function _procesarCarga($idProyecto, $proyecto, $categoria) {

        $imagen = $this->files('imagen');
        $procesador = new ProcesadorCarga('imagenes');

        if ($procesador->validar()) {

            $archivos = $procesador->mover("/htdocs/{$categoria->nombre}/{$proyecto->nombre}");

            $objetos = [];
            foreach ($archivos as $archivo) {
                $objeto = [];
                $objeto['nombre'] = " ";
                $objeto['url_media'] = $archivo;
                $objeto['id_proyecto'] = $idProyecto;
                array_push($objetos, $objeto);
                $imagen = new Imagen($archivo);
                $imagen->redimensionar(150, 150);
                $imagen->redimensionar(300, 300);
                $imagen->redimensionar(600, 600);
                $imagen->redimensionar(1200, 1200);
            }

            $this->modelo->salvarTodo($objetos);

            Mensajes::crear('suceso', "Imagenes Guardadas correctamente.");
            $this->redireccionar("/jadmin/media/index/{$idProyecto}");

        }
        else Mensajes::crear('error', "Una o mas fotografias no son validad");

    }

    function subirImagenes($idProyecto = "") {

        $this->layout()->incluirJS([
            'modulo/jCargaFile.js',
            'modulo/form.js'
        ]);

        if (empty($idProyecto)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $this->modelo = new Modelo();
        $proyecto = new Proyecto($idProyecto);
        $categoria = new Categoria($proyecto->id_categoria);

        if ($this->files    ('imagen')) {
            $this->_procesarCarga($idProyecto, $proyecto, $categoria);
        }

        $this->data([
            'idFk'       => $idProyecto,
            'nombre'     => $proyecto->nombre,
            'contenidos' => [],
            'urlEnvio'   => Estructura::$url
        ]);

    }

}