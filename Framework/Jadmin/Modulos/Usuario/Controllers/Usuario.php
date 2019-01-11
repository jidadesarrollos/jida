<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 10/01/19
 * Time: 03:15 PM
 */

namespace Jida\Jadmin\Modulos\Usuario\Controllers;

use Jida\Jadmin\Controllers\JControl;
use Jida\Medios\Mensajes;
use Jida\Medios\Sesion;
use Jida\Render\Formulario;
use Jida\Modulos\Usuarios\Usuario as Persona;


class Usuario extends JControl {
    public function login () {

        $this->layout('login');

        $formLogin = new Formulario('jida/Login');
        $formLogin->boton('principal', 'Iniciar sesión');

        if ($this->post('btnLogin')) {

            $usuario = $this->post('usuario');
            $clave = $this->post('clave');

            if ($formLogin->validar() and Persona::iniciarSesion($usuario, $clave)) {
                $this->redireccionar('jadmin');
            }

            Formulario::msj('error', 'Datos incorrectos');

        }

        $this->data([
                        'formulario' => $formLogin->render()
                    ]);
    }

    public function cambioclave () {

        $formCambioClave = new Formulario('jida/cambioClave');
        $formCambioClave->boton('principal', 'Cambiar Clave');

        if ($this->post('btnCambioClave')) {
            if ($formCambioClave->validar()) {
                if ($this->post('nueva_clave') == $this->post('confirmacion_clave')) {
                    $claveVieja = $this->post('clave_actual');
                    $claveNueva = $this->post('nueva_clave');
                    $resp = Sesion::$usuario->cambiarClave($claveVieja, $claveNueva);
                    if ($resp) {
                        Mensajes::suceso('Cambió su constraseña satisfactoriamente');
                    }
                    else  Mensajes::error('La contraseña actual que colocó es incorrecta.');

                }
                else Mensajes::error('La contraseña actual no corresponde con la confirmacion.');
            }
            else Mensajes::error('Los datos introducidos no son validos.');
        }

        $this->data(['formulario' => $formCambioClave->render()]);
    }
}