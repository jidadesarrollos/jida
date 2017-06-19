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

class FormulariosController extends FController
{

    private $_rutaJida;
    public $manejoParams = TRUE;

    function __construct()
    {


        parent::__construct();
        $this->_rutaJida = DIR_FRAMEWORK . 'formularios';

    }

    function index()
    {

        $this->vista = 'vista';
        $jidaForms = Helpers\Directorios::listar($this->_rutaJida);
        Helpers\Sesion::destruir('JFormulario');
        $formsInvalidos = $data = $params = [];

        foreach ($jidaForms as $key => $archivo) {

            if (!is_dir($this->_rutaJida . DS . $archivo)) {

                $dataFormulario = $this->_dataVistaFormulario($archivo);
                if ($dataFormulario) {
                    $data[] = $dataFormulario;
                } else {
                    $formsInvalidos[] = $archivo;
                }
            } else {
                unset($jidaForms[$key]);
            }
        }

        $params = [
            'titulos' => ['nombre', 'estructura', 'ID', 'Clave Primaria', 'Total Campos']
        ];

        $jvista = new Render\JVista($data, $params, 'Formularios');
        $jvista->accionesFila([
            ['span' => 'fa fa-edit', 'title' => "Editar", 'href' => $this->obtUrl('gestion', ['{clave}'])],
            ['span' => 'fa fa-picture-o', 'title' => 'Editar Campos', 'href' => $this->obtUrl('gestion', ['{clave}'])],
            ['span' => 'fa fa-trash', 'title' => "Eliminar Formulario", 'href' => $this->obtUrl('eliminar', ['{clave}']),
                'data-jvista' => 'confirm', 'data-msj' => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el formulario seleccionado?'],

        ]);
        $jvista->acciones([
            'Nuevo Formulario' => ['href' => $this->obtUrl('gestion')]

        ]);
        $this->data([
            'vista' => $jvista->obtenerVista()
        ]);
    }

    /**
     * Lee la data del formulario y retorna un arreglo con los valores
     */
    private function _dataVistaFormulario($formulario)
    {

        $data = $this->_dataFormulario($formulario);
        if ($data) {
            if (array_key_exists('query', $data)) {

                unset($data['query']);
                return $data;

            }
        }
        return false;
    }

    function gestion($id = "")
    {

        $this->_instanciarFormulario($id);
        $dataForm = [];
        $nombreFormulario = "";

        if (!empty($id)) {

            $nombreFormulario = $id . '.json';
            $dataForm = $this->_dataFormulario($nombreFormulario);
            $titulo = 'Editar <strong>' . $dataForm['nombre'] . '</strong>';

        } else {

            $titulo = 'Crear Nuevo Formulario';

        }

        $form = new Render\Formulario('GestionFormulario', $dataForm);
        $form->boton('principal', 'Guardar y editar campos');
        $form->titulo($titulo);

        if ($this->post('btnGestionFormulario') or $this->post('btnCampos')) {

            if ($this->_guardarFormulario($nombreFormulario)) {

                $this->redireccionar($this->obtUrl('Campos.gestion', [$this->_formulario->identificador]));

            } else {
                exit("no guarda");
            }
        }


        $this->data([
            'form' => $form->render()
        ]);

    }


    /**
     * Gestiona el guardado del formulario
     * @param string $nombreFormulario identificador del formulario en UpperCamelCase
     */
    function _guardarFormulario($nombreFormulario)
    {

        $post = $this->post();
        $bandera = false;

        if ($this->_formulario->salvar($post)) {

            $msj = Helpers\Mensajes::crear('suceso', 'Formulario guardado correctamente');
            Helpers\Sesion::set('__msj', $msj);

            return true;

        } else {
            Helpers\Debug::imprimir("No se pudo guardar el formulario", true);
        }

        return false;

    }

    function eliminar()
    {

    }


}
