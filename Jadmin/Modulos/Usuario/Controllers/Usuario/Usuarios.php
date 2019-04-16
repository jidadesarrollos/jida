<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 25/02/19
 * Time: 03:52 PM
 */

namespace Jida\Jadmin\Modulos\Usuario\Controllers\Usuario;

use Jida\Manager\Estructura;
use Jida\Manager\Vista\Render;
use Jida\Medios\Archivos\ProcesadorCarga;
use Jida\Medios\Debug;
use Jida\Medios\Directorios;
use Jida\Medios\Mensajes;
use Jida\Medios\Sesion;
use Jida\Medios\Archivos\Imagen;
use Jida\Modulos\Usuarios\Modelos\Perfil;
use Jida\Modulos\Usuarios\Modelos\Usuario;
use Jida\Modulos\Usuarios\Modelos\UsuarioPerfil;
use Jida\Render\Formulario;
use Jida\Render\Selector;
use Jida\Render\JVista;

trait Usuarios {

    public function index() {

        $listaUsuarios = Usuario::listaUsuarios();
        $parametros = ['titulos' => ['Usuario', 'Nombre', 'Apellido', 'Correo', 'Perfiles']];
        $vista = new JVista($listaUsuarios, $parametros);

        $vista->accionesFila([
            ['span'  => 'fas fa-user-alt',
             'title' => 'Cambiar Perfiles',
             'href'  => '/jadmin/usuario/perfil/{clave}'],
            ['span'  => 'fa fa-edit',
             'title' => "Editar Usuario",
             'href'  => '/jadmin/usuario/gestion/{clave}'],
            ['span'        => 'fa fa-trash',
             'title'       => 'Eliminar usuario',
             'href'        => '/jadmin/usuario/eliminar/{clave}',
             'data-jvista' => 'confirm',
             'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el cliente seleccionado?']
        ]);
        $vista->acciones([
            'Nuevo Usuario' => ['href' => '/jadmin/usuario/gestion/']
        ]);

        $render = $vista->render(
            function ($datos) {

                foreach ($datos as $key => &$users) {
                    $listaPerfiles = '<ul>';
                    foreach ($users['perfiles'] as $perfil) {
                        $listaPerfiles .= "<li>{$perfil['perfil']}</li>";
                    }
                    $listaPerfiles .= '</ul>';
                    $users['perfiles'] = $listaPerfiles;
                }
                return $datos;
            }
        );

        $this->data([
            'vista' => $render
        ]);

    }

    public function perfil($id_usuario) {

        $usuarioPerfil = new UsuarioPerfil();
        $usuarioPerfil2 = $usuarioPerfil
            ->consulta(['id_usuario_perfil', 'id_perfil'])
            ->filtro(['id_usuario' => $id_usuario])
            ->obt();
        $listaPerfiles = [];
        foreach ($usuarioPerfil2 as $fila) {
            $listaPerfiles[] = $fila['id_perfil'];
        }
        $perfiles = new Perfil();
        $perfiles = $perfiles->consulta()->obt();
        $usuario = new Usuario($id_usuario);

        if ($this->post('btnGestionPerfiles')) {
            foreach ($usuarioPerfil2 as $fila) {
                $usuarioPerfil->eliminar($fila['id_usuario_perfil']);
            }

            $nuevosPerfiles = [];
            foreach ($this->post('id_perfil') as $list) {
                $nuevosPerfiles[] = ['id_perfil' => $list, 'id_usuario' => $id_usuario];
            }
            $usuarioPerfil->salvarTodo($nuevosPerfiles);
            Mensajes::almacenar(Mensajes::suceso('Perfiles modificados con exito'));
            $this->redireccionar('/jadmin/usuario');

        }

        $this->data([
            'listaPerfiles' => $listaPerfiles,
            'perfiles'      => $perfiles,
            'id_usuario'    => $id_usuario,
            'name'          => "{$usuario->nombres} {$usuario->apellidos}"
        ]);
    }

    public function gestion($id_usuario) {

        $this->layout()->incluirJS(['{base}/jida/htdocs/js/libs/jCargaFile.js', '{tema}/htdocs/js/cargarImagen.js']);

        $form = new Formulario('jida/Usuarios/GestionUsuarios', $id_usuario);
        $form->attr(['enctype' => 'multipart/form-data']);

        $usuario = new Usuario($id_usuario);

        if ($this->post('btnUsuarios')) {

            if ($form->validar()) {

                $this->post('clave', $usuario->clave);

                if ($this->files('imagen')['name']) {
                    $procesador = new ProcesadorCarga('imagen');
                    if ($procesador->validar()) {
                        $ruta = Estructura::$directorio . "/htdocs/imgs/perfiles/{$id_usuario}";
                        Directorios::eliminar($ruta);
                        $archivo = $procesador->mover($ruta)->archivos();
                        $this->post('img_perfil', str_replace(Estructura::$directorio, '', $archivo[0]->directorio()));
                    }
                }

                if ($usuario->salvar($this->post())) {

                    $accion = (empty($id)) ? 'guardado' : 'modificado';
                    Mensajes::almacenar(Mensajes::suceso("El usuario ha sido {$accion} correctamente"));
                    $this->redireccionar('/jadmin/usuario');
                }
            }
            else {
                $form::msj('error', 'Los datos ingresados no son v&aacute;lidos');
            }
        }

        $botonImg = Selector::crear('input', ['type'  => 'button',
                                              'title' => 'Cargar nueva imagen de perfil',
                                              'value' => 'Cargar nueva imagen de perfil',
                                              'class' => 'btn btn-default pull-right',
                                              'id'    => 'btnCargar'
        ]);
        $botonLimpiar = Selector::crear('input', ['type'  => 'button',
                                                 'title' => 'Borrar imagen de perfil',
                                                 'value' => 'Borrar imagen de perfil',
                                                 'class' => 'btn btn-default pull-right',
                                                 'id'    => 'btnLimpiar'
        ]);
        $form->addFinal($botonImg);
        $form->addFinal($botonLimpiar);


        $this->data([
            'vista'      => $form->render(),
            'img_perfil' => Estructura::$urlBase . $usuario->img_perfil
        ]);
    }

    public function miPerfil() {
        $id_propio = Sesion::$usuario->obtener('id_usuario');
        $this->redireccionar("/jadmin/usuario/gestion/{$id_propio}");
    }

    public function eliminar($id_usuario) {

        if (!empty($id_usuario)) {

            $usuario = new Usuario($id_usuario);
            if (!empty($usuario->id_usuario) and $usuario->eliminar()) {
                $ruta = Estructura::$directorio . "/htdocs/imgs/perfiles/{$id_usuario}";
                Directorios::eliminar($ruta);
                Mensajes::almacenar(Mensajes::suceso('El usuario ha sido eliminado correctamente'));
                $this->redireccionar('/jadmin/usuario');
            }
            else {
                Mensajes::almacenar(Mensajes::error('El usuario no ha sido eliminado'));
                $this->redireccionar('/jadmin/usuario');
            }

        }
        else {
            Mensajes::almacenar(Mensajes::error('El usuario indicado no existe.'));
            $this->redireccionar('/jadmin/usuario');
        }
    }
}