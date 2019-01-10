<?php

namespace App\Controllers;

use Jida\Medios as Medios;

class Index extends App {

    var $correoContacto = 'developers@jidadesarrollos.com';

    function index() {

    }

    function acerca() {

    }

    function galeria() {

    }

    function contacto() {

        $form = new \Jida\Render\Formulario('Contacto');
        $form->titulo('Contacto');
        $form->boton('principal')->attr('value', 'Enviar Correo');

        if ($this->post('btnContacto')) {

            if ($form->validar()) {
                $this->_enviarCorreo($this->post(), 'Contacto | ' . NOMBRE_APP);
                $msj = 'Correo enviado exitosamente, pronto estaremos en contacto contigo.';
                Medios\Mensajes::crear('info', $msj, true);
            }

        }

        $this->data([
            'form' => $form->render()
        ]);
    }

    private function _enviarCorreo($post, $asunto) {

        $correo = new \Jida\Componentes\Correo();
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
