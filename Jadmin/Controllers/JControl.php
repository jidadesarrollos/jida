<?php

namespace Jida\Jadmin\Controllers;

use Jida\Configuracion\Config;
use Jida\Core\Controlador;
use Jida\Helpers as Helpers;
use Jida\Render as Render;

class JControl extends Controlador {

    function __construct () {

        parent::__construct();
        $this->data('nombreApp', "Jida");
        $this->layout('jadmin');

    }

    function phpInfo () {

        echo phpinfo();
        exit;

    }

    protected function formularioInicioSesion () {

        $configuracion = Config::obtener();
        $form = new Render\Formulario('jida/Login');
        $form->boton('principal')
            ->attr([
                       'value' => 'Iniciar Sesi&oacute;n',
                       'id'    => 'btnJadminLogin',
                       'name'  => 'btnJadminLogin'
                   ]);
        if ($this->post('btnJadminLogin')) {

            $userClass = MODELO_USUARIO;
            $user = new $userClass();

            if ($user->validarLogin($this->post('nombre_usuario'), $this->post('clave_usuario'))) {

                $perfiles = $user->getPerfiles();
                Helpers\Sesion::set('Usuario', $user);
                Helpers\Sesion::set('__msjInicioSesion',
                                    Helpers\Mensajes::crear('suceso', 'Bienvenido ' . $user->nombre_usuario));

                return true;
            }
            else {
                Formulario::msj('error', 'Usuario o clave invalidos');
            }
        }

        $this->layout('jadminIntro');
        $this->dv->usarPlantilla('login');
        $this->data([
                        'nombreApp' => $configuracion::NOMBRE_APP
                    ]);
        $this->tituloPagina = $configuracion::NOMBRE_APP;
        $this->data('formLoggin', $form->armarFormulario());

    }
}