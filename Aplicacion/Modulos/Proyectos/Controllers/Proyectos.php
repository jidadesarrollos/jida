<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26/11/18
 * Time: 08:26 AM
 */

namespace App\Modulos\Proyectos\Controllers;

use App\Controllers\App;
use Jida\Medios\Debug;

class Proyectos extends App {

    public $proyectos = [];

    public function __construct() {
        parent::__construct();

        $this->proyectos = [
            1 => [
                'id_proyecto' => 1,
                'proyecto'    => 'Proyecto 1',
                'categoria'   => 'Bodas',
                'imagen'      => $this->rutaImagen . 'hasna-1.jpg',
            ],
            2 => [
                'id_proyecto' => 2,
                'proyecto'    => 'Proyecto 2',
                'categoria'   => 'Bodas',
                'imagen'      => $this->rutaImagen . 'hasna-2.jpg',
            ],
            3 => [
                'id_proyecto' => 3,
                'proyecto'    => 'Proyecto 3',
                'categoria'   => 'Bodas',
                'imagen'      => $this->rutaImagen . 'hasna-3.jpg',
            ]
        ];

    }

    public function index() {

        $this->data([
            'proyectos' => $this->proyectos
        ]);

    }

    public function galeria($id = "") {

        if (empty($id)) {
            $this->redireccionar('/proyectos');
        }

        $this->data([
            'proyecto'  => $this->proyectos[$id],
            'proyectos' => $this->proyectos
        ]);

    }

}