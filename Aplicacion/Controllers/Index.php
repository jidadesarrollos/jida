<?php
/**
 * Controlador por defecto
 */

namespace App\Controllers;

class Index extends App {

    function index() {

        $this->layout('principal.tpl.php');

        $this->vista('home');

        $form = new Formulario('jida/Contactanos');
        $form->boton('principal', 'Enviar solicitud');

        if ($this->post('btnContactanos')) {

            $nombre = $this->post('nombre');
            $mensaje = $this->post('mensaje');

            if ($form->validar()) {
                $this->_enviarCorreo([$nombre, $mensaje]);
                $this->redireccionar('index');
            }

            Formulario::msj('error', 'Datos incorrectos');

        }

        $this->data([
            'mensaje' => "Somos Jida Desarrollos"
        ]);

    }

    private function _enviarCorreo($post) {

        $correo = new \Jida\Componentes\Correo();
        $correo->plantilla('contacto')->data([
            'nombre'  => $post['nombre'],
            'mensaje' => $post['mensaje']
        ])->enviar('contacto@jidadesarrollos.com', 'Nuevo mensaje!');

    }

}
