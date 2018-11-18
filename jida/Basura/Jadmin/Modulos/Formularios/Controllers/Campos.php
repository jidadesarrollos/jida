<?php
/**
 * Clase Controladora
 * @author Julio Rodriguez
 * @package
 * @version
 * @category Controller
 */

namespace Jida\Jadmin\Modulos\Formularios\Controllers;

use Jida\Helpers as Helpers;
use Jida\Render as Render;

class Campos extends Fcontroller {

    public $manejoParams = true;

    function gestion ($id, $modulo = 'app') {

        if (!empty($id)) {

            Helpers\Sesion::destruir('JFormulario');

            $nombreFormulario = $id . '.json';
            $form = $this->_instanciarFormulario($nombreFormulario, $modulo);

            $this->data([
                            'campos' => $form->campos,
                            'moduloFormulario' => $modulo,
                            'idFormulario' => $id,
                            'url' => implode('/',
                                             [
                                                 '/jadmin/formularios/campos/configuracion',
                                                 $id,
                                                 $modulo
                                             ])
                        ]);

        }
        else {
            $this->_404();
        }

    }

    function configuracion ($idFormulario, $modulo, $idCampo) {

        $error = false;
        $formulario = $idFormulario . '.json';

        if ($this->_instanciarFormulario($formulario, $modulo)) {

            if (!empty($this->_formulario->identificador)) {

                $data = $this->_formulario->dataCampo($idCampo)->obtenerPropiedades();

                $form = new Render\Formulario('jida/CamposFormulario', $data);
                $form->attr('action',
                            $this->obtUrl('guardar',
                                          [
                                              $idFormulario,
                                              $modulo,
                                              $idCampo
                                          ]));

                $this->data(['form' => $form->render()]);

            }
            else {
                $error = true;
            }

        }

        if ($error)
            $this->_404();

    }

    function guardar ($formulario, $modulo, $idCampo) {

        $formulario = $formulario . '.json';
        if ($this->_instanciarFormulario($formulario, $modulo)) {

            $this->_formulario->dataCampo($idCampo, $this->post());
            $this->_formulario->modulo($modulo);
            if ($this->_formulario->salvar()) {
                $msj = Helpers\Mensajes::crear('suceso', 'Campo guardado correctamente.');
                $this->respuestaJson([
                                         'ejecutado' => true,
                                         'mensaje'   => $msj
                                     ]);
            }

        }
        else {
            $msj = Helpers\Mensajes::crear('suceso', 'No se pudo guardar el campo.');
            $this->respuestaJson([
                                     'ejecutado' => false,
                                     'mensaje'   => $msj
                                 ]);
        }

    }

    function ordenar ($formulario, $modulo) {

        if ($this->solicitudAjax() and $formulario) {

            $this->_instanciarFormulario($formulario . ".json", $modulo);
            $this->_formulario->modulo($modulo);
            $this->_formulario->orden($this->post('campos'));

            if ($this->_formulario->salvar()) {

                $this->respuestaJson([
                                         'ejecutado' => true,
                                         'msj'       => "Se ha guardado el orden del formulario"
                                     ]);

            }
            else {
                $this->respuestaJson([
                                         'ejecutado' => false,
                                         'msj'       => "No se ha podido guardar el formulario"
                                     ]);
            }

        }
        else $this->_404();

    }
}