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


class CamposController extends Fcontroller
{

    public $manejoParams = TRUE;

    function configuracion()
    {
        $url = '/jadmin/formularios/campos/configuracion/:formulario/:campo';

        $error = false;

        if ($this->_instanciarFormulario($this->post('form'))) {

            if (!empty($this->_formulario->identificador)) {
                #Helpers\Debug::imprimir($this->_formulario->dataCampo($this->post('idCampo')),true);
                $form = new Render\Formulario('CamposFormulario', $this->_formulario->dataCampo($this->post('idCampo')));
                $this->data(['form' => $form->render()]);

            } else $error = TRUE;

        }

        if ($error) $this->_404();

    }

    function gestion($id)
    {

        if (!empty($id)) {

            Helpers\Sesion::destruir('JFormulario');
            $this->_instanciarFormulario($id);
            $this->data([
                'campos' => $this->_formulario->campos,
                'idFormulario' => $id
            ]);

        } else {
            $this->_404();
        }

    }

    function ordenar()
    {

        if ($this->solicitudAjax() and $this->post('formulario')) {

            $this->_instanciarFormulario($this->post('formulario'));
            $this->_formulario->orden($this->post('campos'));

            if ($this->_formulario->salvar()) {

                $this->respuestaJson([
                    'ejecutado' => TRUE,
                    'msj' => "Se ha guardado el orden del formulario"
                ]);

            } else {
                exit("no");
                $this->respuestaJson([
                    'ejecutado' => FALSE,
                    'msj' => "No se ha podido guardar el formulario"
                ]);
            }

        } else $this->_404();

    }
}