<?php

namespace App\Controllers;

use App\Config\Configuracion;
use App\Modulos\Categorias\Modelos\Categoria;
use App\Modulos\Media\Modelos\Media;
use App\Modulos\Proyectos\Modelos\Proyecto;
use Jida\Medios\Debug;
use Jida\Medios\Mensajes;
use Jida\Render\Formulario;
use Jida\Componentes\Correo;

class Index extends App {

    var $slider = [];

    function index() {

        $this->slider = [
            'slide-1' => [
                'imagen' => $this->rutaImagen . 'hasna-1.jpg',
                'titulo' => 'Bodas'
            ],
            'slide-2' => [
                'imagen' => $this->rutaImagen . 'hasna-2.jpg',
                'titulo' => 'Eventos'
            ],
            'slide-3' => [
                'imagen' => $this->rutaImagen . 'hasna-3.jpg',
                'titulo' => 'Retratos'
            ]
        ];

        $this->data([
            'slider' => $this->slider
        ]);

    }

    function acerca() {

    }

    function contacto() {

        $form = new Formulario('Contacto');
        $form->titulo('Contacto');
        $form->boton('principal')->attr('value', 'Enviar Correo');

        if ($this->post('btnContacto')) {

            if ($form->validar()) {
                $this->_enviarCorreo($this->post(), 'Contacto | ' . Configuracion::NOMBRE_APP);
                $msj = 'Correo enviado exitosamente, pronto estaremos en contacto contigo.';
                Mensajes::crear('info', $msj, true);
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
