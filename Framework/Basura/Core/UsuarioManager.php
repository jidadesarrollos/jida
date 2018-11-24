<?php
/**
 * Trait para manejo de funciones generales de Usuario
 *
 * @internal Provee un conjunto de funcionalidades que son reutilizables para el controlador de Usuario y las que extiendan de el
 *
 */

namespace Jida\Core;

use Jida\Render as Render;
use Jida\Modelos as Modelos;
use \Jida\Medios as Helpers;
use \Jida\Medios\Debug as Debug;

trait UsuarioManager {

    /**
     * Genera el grid de visualización de usuarios
     * @method vistaUser
     * @access protected
     * @param $url del controlador de usuarios que invoca el metodo
     */
    protected function vistaUser (
        $url = null, $perfiles = false, $titulos = false, $acciones = false, $accionesFila = false, $consulta = false,
        $idVista = false
    ) {

        if (empty($url))
            $url = $this->url;
        if (!$titulos)
            $titulos = [
                'Usuario',
                'Fecha Creaci&oacute;n',
                'Activo',
                'Ultima Sesi&oacute;n',
                'Estatus'
            ];
        if (!$consulta)
            $consulta = '\Jida\Modelos\User.obtUsers';
        if (!$idVista)
            $idVista = 'Usuarios';

        $jvista = new Render\JVista($consulta, ['titulos' => $titulos], $idVista);

        if ($perfiles) {
            if (is_array($perfiles))
                $jvista->clausula('in', $perfiles, 's_usuarios_perfiles.id_perfil');
            else
                $jvista->clausula('filtro', ['s_usuarios_perfiles.id_perfil' => $perfiles]);
        }

        if ($accionesFila)
            $jvista->accionesFila($accionesFila);
        else
            $jvista->accionesFila([
                                      [
                                          'span'        => 'glyphicon glyphicon-user',
                                          'title'       => 'Asignar perfiles de acceso',
                                          'href'        => $this->obtUrl('asociarPerfiles', ['{clave}']),
                                          'data-jvista' => 'modal'
                                      ],
                                      [
                                          'span'  => 'glyphicon glyphicon-edit',
                                          'title' => 'Modificar usuario',
                                          'href'  => $this->obtUrl('setUsuario', ['{clave}'])
                                      ],
                                      [
                                          'span'        => 'glyphicon glyphicon-trash',
                                          'title'       => 'Eliminar Usuario',
                                          'href'        => $this->obtUrl('eliminarUsuario', ['{clave}']),
                                          'data-jvista' => 'confirm',
                                          'data-msj'    => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el usuario seleccionado?'
                                      ]
                                  ]);

        if (is_array($acciones)):
            $jvista->acciones($acciones);
        else:
            if ($acciones)
                $jvista->acciones(['Registrar ' => ['href' => $this->obtUrl('setUsuario')]]);
        endif;

        $jvista->addMensajeNoRegistros('No hay Usuarios Registrados');

        return $jvista;

    }

    /**
     * Muestra formulario de gestión de usuarios
     *
     * @internal El metodo puede ser configurado en controladores que hereden de UsersControllers por
     * medio de los parametros que solo son pasados al ser llamado explicitamente
     * @param string $url Url del metodo que hereda
     * @param $externo Consulta sql para filtrar los perfiles a mostrar
     * @param $idVista Id de la vista en la cual mostrar mensaje de suceso
     * @param $urlVista Url de la vista a la cual redireccionar
     * @method setUsuario
     */
    protected function _setUsuario ($idUser = '', $url = "", $externo = "", $idVista = 'usuarios', $urlVista = "") {

        $urlVista = (empty($urlVista)) ? $this->url : $urlVista;

        $datosForm = $this->formGestionUser($idUser, $url, $externo);

        $metodo = (empty($metodo)) ? 'set-usuario' : $metodo;
        $this->dv->action = $this->url . $metodo . '/' . $idUser;

        if ($this->post('btnRegistroUsuarios')):

            if ($datosForm['guardado'] and $datosForm['guardado']['ejecutado'] == 1) {
                $accion = (!empty($idUser)) ? 'actualizado' : 'creado';
                $msj = 'El usuario <strong>' . $this->post('nombre_usuario') . '</strong> ha sido ' . $accion . ' exitosamente';
                Render\JVista::msj($idVista, 'suceso', $msj, $urlVista);
            }
            else
                Medios\Sesion::set('__msjForm',
                                    Mensajes::crear('error',
                                                    "No se ha podido registrar el usuario, vuelva a intentarlo"),
                                    false);

        endif;

        $this->dv->valueBotonForm = (!empty($idUser)) ? 'Actualizar Datos' : 'Registrar Usuario';
        $this->dv->tituloForm = 'Gesti&oacute;n de Usuarios';
        $this->dv->form = $datosForm['form']->enArreglo();
        $this->dv->formPerfiles = $datosForm['formPerfiles']->enArreglo();

    }

    /**
     * Devuelve el formulario para gestion de usuarios
     *
     * @internal Devuelve el html del usuario configurado con el action del form hacia un metodo 'set-componente' del
     * controlador en el cual sea llamado
     * @method formGestionUser
     * @param int $tipoform
     * @param $campoUpdate
     * @return array $form Arreglo asociativo con dos posiciones 'guardado' result del save de DBContainer 'form' Objeto Formulario
     */
    protected function formGestionUser ($campoUpdate = '', $metodo = 'set-usuario', $externo = '') {

        $metodo = (empty($metodo)) ? 'set-usuario' : $metodo;

        $form = new Render\Formulario('RegistroUsuarios', $campoUpdate);
        $formPerfiles = new Render\Formulario('PerfilesAUsuario', $campoUpdate);

        $retorno = [
            'guardado'     => '',
            'form'         => '',
            'formPerfiles' => ''
        ];

        if ($this->post('btnRegistroUsuarios')) {

            if ($form->validar() && $formPerfiles->validar()) {

                $user = new Modelos\User($campoUpdate);
                $this->post('validacion', 1);
                $this->post('activo', 1);
                if ($this->post('clave_usuario') != $user->clave_usuario)
                    $this->post('clave_usuario', md5($this->post('clave_usuario')));

                if ($user->salvar($this->post())->ejecutado() == 1)
                    $user->asociarPerfiles($this->post('id_perfil'));

                $retorno['guardado'] = ['ejecutado' => $user->getResult()->ejecutado()];

            }
            else {
                exit('El formulario no cumple con las validaciones, revisar la clase Formulario de Render para ajustar este detalle');
                // $retorno['guardado'] = $validacion;
            }

        }

        $retorno['form'] = $form;
        $retorno['formPerfiles'] = $formPerfiles;

        return $retorno;
    }

    /**
     * Realiza el proceso de registro de usuarios
     *
     * @internal La data a registrar debe haber sido validada previamente
     *
     * @method registrarPerfilesDeUsuario
     * @param object $formulario Objeto Formulario de Perfiles a Usuario instanciado
     * @param mixed $user Objeto instanciado de usuario o en su defecto el id del usuario
     */
    protected function registrarPerfilesDeUsuario ($form, $user, $perfiles) {

        if (!is_object($user))
            $user = new Modelos\User($user);

        $ejecutado = $user->asociarPerfiles($perfiles);

        if ($ejecutado['ejecutado'] == 1)
            return true;
        else
            return false;
    }

    /**
     * Metodo para la gestion de perfiles de usuario
     *
     * @internal Se debe pasar el id de usuario
     *
     * @method asociarPerfiles
     * @param string $user id del usuario al cual se quieren asociar los perfiles
     */
    protected function _asociarPerfiles ($user = '') {

        if (!empty($user)) {
            $form = new Render\Formulario('PerfilesAUsuario', $user);
            $user = new Modelos\User($user);

            $form->action = $this->url . "asociar-perfiles";
            $form->valueSubmit = "Asignar Perfiles a Objeto";
            $form->titulo('Asignar perfiles al usuario ' . $user->nombre_usuario);

            if ($this->post('btnPerfilesAUsuario')) {
                if ($form->validar()) {
                    $accion = $user->asociarPerfiles($this->post('id_perfil'));
                    if ($accion['ejecutado'] == 1) {
                        Render\JVista::msj('componentes',
                                           'suceso',
                                           'Asignados los perfiles al usuario ' . $user->nombre_usuario,
                                           $this->urlController());
                    }
                    else {
                        Formulario::msj('error', "No se pudieron asignar los perfiles, por favor vuelva a intentarlo");
                    }
                }
                else {
                    Formulario::msj('error', "No se han asignado perfiles");
                }
            }

            if ($this->solicitudAjax())
                $this->layout = '../ajax.tpl.php';

            $this->dv->form = $form->armarFormulario();

        }
        else
            Render\JVista::msj('usuarios', 'error', "Debe seleccionar un usuario", $this->urlController());

    }

    /**
     * Devuelve el formulario para gestion de usuarios
     *
     * @internal Devuelve el html del usuario configurado con el action del form hacia un metodo 'asociar-perfiles' del
     * controlador en el cual sea llamado
     * @method formAsignacionPerfiles
     * @param int $tipoform
     * @param $campoUpdate Id del usuario al que se asignaran los perfiles
     */
    protected function formAsignacionPerfiles ($campoUpdate = "", $perfiles = "") {

        $form = new Render\Formulario('PerfilesAUsuario', $campoUpdate);

        $form->valueBotonForm = 'Asignar Perfiles';
        $form->action = $this->urlController() . 'asociar-perfiles';

        if (!empty($perfiles) and is_array($perfiles)) {
            $form->externo['id_perfil'] = "select id_perfil,perfil from s_perfiles where id_perfil in (" . implode(",",
                                                                                                                   $perfiles) . ") order by perfil";
        }
        else {
            $form->externo['id_perfil'] = "select id_perfil,perfil from s_perfiles where id_perfil order by id_perfil";
        }

        $retorno = ['form' => $form];

        return $retorno;
    }

    /**
     * Elimina un usuario de Base de datos
     * @method formCambioContrasenia
     * @return object $form Objeto Tipo Formulario
     * @see Formulario
     */
    protected function _eliminarUsuario ($idUser = '') {

        if (!empty($idUser)) {
            $user = new Modelos\User($idUser);

            if ($user->eliminar())
                return true;
            else
                return false;

        }
        else {
            throw new Exception("Debes especificar un usuario para eliminarlo", 111);
        }

    }

    /**
     * Registra la sesion de un usuario
     *
     * @internal Crea la variable de Sesion Usuario con el usuario en sesión actual
     * @method crearSesionUsuario
     */
    protected function crearSesionUsuario () {

        Medios\Sesion::sessionLogin();
        Medios\Sesion::set('Usuario', $this->modelo);

        #- Se guarda como arreglo para mantener soporte a aplicaciones anteriores
        if (isset($data))
            Medios\Sesion::set('usuario', $data);

        return $this;
    }

    /**
     * Verifica los datos para iniciar sesion
     *
     * @internal Verifica los datos del usuario y si el mismo existe registra la sesion y lo habilita
     * caso contrario retorna falso
     * @method validarInicioSesion
     */
    protected function validarInicioSesion ($usuario, $clave) {

        $data = $this->modelo->validarLogin($usuario, $clave);

        if ($data) {
            $this->crearSesionUsuario();

            return true;
        }
        else
            return false;
    }

    /**
     * Cierra la Sesion de Usuario y destruye su variable de Sesion
     *
     * @method cierresesion
     * @param string $url url para redireccionar al cerrar la sesion de usuario
     */
    protected function _cierresesion ($url = "") {

        if (Medios\Sesion::destruir()) {

            if (Medios\Sesion::obt('Usuario') instanceof MODELO_USUARIO)
                Medios\Sesion::obt('Usuario')->cerrarSesion();

            if (empty($url))
                $url = $this->urlCierreSession;

            $this->redireccionar($url);
        }
    }

    /**
     * Retorna un Objeto Formulario para Formulario Login
     *
     * @method obtenerFormulariologin
     * @return object $form
     * @see Formulario
     */
    protected function formularioLogin ($called = false) {

        if ($called) {
            if (Medios\Sesion::obt('FormLoggin') and Medios\Sesion::obt('FormLoggin') instanceof Formulario) {
                $form = Medios\Sesion::obt('FormLoggin');

            }
            else {
                $form = new Render\Formulario('Login', null);
                $form->titulo('Iniciar Sesi&oacute;n');
                // $form->boton('_labelBotonEnvio','Iniciar Sesi&oacute;n');
            }

            return $form;
        }
        else
            $this->_404();

    }

    /**
     * Devuelve un formulario para modificar la contrasenia de un usuario
     * @method formCambioContrasenia
     * @return object $form Objeto Tipo Formulario
     * @see Formulario
     */
    protected function formCambioContrasenia ($idUser = '') {

        $form = new Render\Formulario('CambioClave', $idUser);

        return $form;
    }

    /**
     * Crea una clave aleatoria
     * @method generarContrasenia
     * @param int $length Tamaño de la cadena, por defecto 30
     */
    protected function generarContrasenia ($length = 30) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $string = substr(str_shuffle($chars), 0, $length);

        return $string;
    }

}

