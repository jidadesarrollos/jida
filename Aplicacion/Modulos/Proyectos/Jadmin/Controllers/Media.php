<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Proyectos\Jadmin\Controllers;

use App\Jadmin\Controllers\Jadmin;
use App\Modulos\Proyectos\Jadmin\Controllers\Media\Carga;
use App\Modulos\Proyectos\Jadmin\Controllers\Media\Gestion;
use App\Modulos\Proyectos\Jadmin\Controllers\Media\Vista;
use App\Modulos\Proyectos\Modelos\{Proyecto};
use Jida\Manager\Estructura;

class Media extends Jadmin {

    use Vista, Gestion, Carga;

    function index($idProyecto = "") {

        $this->layout()->incluirJS([
            'modulo/jCargaFile.js',
            'modulo/form.js'
        ]);

        if (empty($idProyecto)) {
            $this->redireccionar('/jadmin/proyectos/');
        }

        $proyecto = new Proyecto($idProyecto);

        if (!$proyecto->id_proyecto) {
            $this->redireccionar('/jadmin/proyectos/');

        }

        if ($this->files('imagen')) {
            $this->_procesarCarga($idProyecto, $proyecto);
        }

        $this->data([
            'idProyecto' => $idProyecto,
            'nombre'     => $proyecto->nombre,
            'media'      => $proyecto->media(),
            'urlEnvio'   => Estructura::$url
        ]);
    }

    function gestion($idProyecto = "", $id = "") {
        $this->layout()->incluirJSAjax(['modulo/gestion.js']);
        $this->_gestion($idProyecto, $id);

    }

    function eliminar($id = "") {

    }

}