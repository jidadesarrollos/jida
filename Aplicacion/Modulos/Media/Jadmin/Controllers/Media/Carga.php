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

        $procesador = new ProcesadorCarga('imagen');

        if ($procesador->validar()) {

            $directorio = Estructura::$directorio . "/htdocs/{$categoria->identificador}/{$proyecto->identificador}";
            $archivos = $procesador->mover($directorio)->archivos();

            $ok = true;
            foreach ($archivos as $item => $archivo) {

                $imagen = new Imagen($archivo->directorio());
                if (!$imagen->redimensionar(['150x150', '300x300'])) {
                    $ok = false;

                }

            }
            $this->respuestaJson(['procesado' => $ok, 'directorio' => $directorio]);
            $objetos = [];

            //$this->modelo->salvarTodo($objetos);


        }

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

        if ($this->files('imagen')) {
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