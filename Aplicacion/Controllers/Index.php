<?php

namespace App\Controllers;

use App\Config\Configuracion;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Medias\Modelos\Media;
use App\Modulos\Proyectos\Modelos\Proyecto;
use Jida\Medios\Debug;
use Jida\Render\Formulario;
use Jida\Componentes\Correo;

class Index extends App {

    var $correoContacto = 'rrodriguez@jidadesarrollos.com';

    function index() {

        $galeria = [];
        $proyecto = new Proyecto();
        $proyecto->select(['id_proyecto', 'nombre', 'slug', 'id_categoria']);
        $proyecto->order('id_proyecto', 'desc');
        $proyectos = $proyecto->obt();

        foreach ($proyectos as $k => $row) {

            $cat = new Categoria($row['id_categoria']);

            $medios = new Media();
            $medios->select(['id_media', 'url_media']);
            $medios->filtro(['id_proyecto' => $row['id_proyecto']]);
            $medio = $medios->obt();

            $imagen = new Media($medio[0]['id_media']);
            $imgPortada = $imagen->thumbnail(300, 300);

            $galeria[$k]['proyecto'] = $row['nombre'];
            $galeria[$k]['categoria'] = $cat->nombre;
            $galeria[$k]['imagen'] = $imgPortada;

        }

        $this->data([
            'galeria' => $galeria,
        ]);

    }

    function acerca() {

    }

    function galeria() {

        $galeria = [];
        $medios = new Media();
        $medios->select(['id_media', 'url_media', 'id_proyecto']);
        $result = $medios->obt();

        foreach ($result as $k => $row) {
            $medio = new Media($row['id_media']);
            $imagen = $medio->thumbnail(300, 300);
            $proyecto = new Proyecto($row['id_proyecto']);
            $categoria = new Categoria($proyecto->id_categoria);
            $galeria[$k]['id_proyecto'] = $proyecto->id_proyecto;
            $galeria[$k]['proyecto'] = $proyecto->nombre;
            $galeria[$k]['categoria'] = $categoria->nombre;
            $galeria[$k]['imagen'] = $imagen;
        }

        $this->data([
            'galeria' => $galeria
        ]);

    }

    function detalle($id = "") {

        if (empty($id)) {
            $this->_404();
        }

        $proyecto = new Proyecto($id);
        $categoria = new Categoria($proyecto->id_categoria);

        $galeria = [];
        $medios = new Media();
        $medios->select(['id_media', 'url_media', 'id_proyecto']);
        $medios->filtro(['id_proyecto' => $id]);
        $galeria = $medios->obt();

        $this->data([
            'proyecto' => $proyecto,
            'galeria'  => $galeria
        ]);

    }

    function contacto() {

        $form = new Formulario('Contacto');
        $form->titulo('Contacto');
        $form->boton('principal')->attr('value', 'Enviar Correo');

        if ($this->post('btnContacto')) {

            if ($form->validar()) {
                $this->_enviarCorreo($this->post(), 'Contacto | ' . Configuracion::NOMBRE_APP);
                $msj = 'Correo enviado exitosamente, pronto estaremos en contacto contigo.';
                Medios\Mensajes::crear('info', $msj, true);
            }

        }

        $this->data([
            'form' => $form->enArreglo()
        ]);
    }

    private function _enviarCorreo($post, $asunto) {

        $correo = new Correo();
        $correo
            ->plantilla('contacto')
            ->data([
                'nombre'  => $post['nombre'],
                'mensaje' => $post['mensaje'],
                'correo'  => $post['correo']
            ])
            ->enviar([$this->correoContacto], $asunto);
    }

}
