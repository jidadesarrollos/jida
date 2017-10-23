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

class FormulariosController extends FController {

    private $_rutaJida;
    public $manejoParams = TRUE;

    function __construct() {

        parent::__construct();
        $this->_rutaJida = DIR_FRAMEWORK . 'formularios';

    }

    function index($modulo = "") {

        $this->vista = 'vista';
        $forms = [];
        if ($modulo == 'jida') {

            $forms = [
                'jida' => [
                    'formularios' => Helpers\Directorios::listar($this->_rutaJida),
                    'path' => $this->_rutaJida,
                    'modulo' => 'Jida'
                ]
            ];

        } else {
            $forms = $this->_obtenerFormularios();
        }

        $formsInvalidos = $data = $params = [];

        $formularios = [];
        foreach ($forms as $modulos => $data) {
            foreach ($data['formularios'] as $index => $formulario) {
                if (!is_dir($this->_rutaJida . DS . $formulario)) {
                    $dataFormulario = $this->_dataVistaFormulario($formulario, $data['modulo']);
                    if ($dataFormulario) {
                        $formularios[] = $dataFormulario;
                    } else {
                        $formsInvalidos[] = $formulario;
                    }
                } else {
                    unset($data[$index]);
                }
            }
        }

        $params = [
            'titulos' => ['nombre', 'estructura', 'ID', 'Clave Primaria', 'Total Campos', 'Modulo']
        ];

        $jvista = new Render\JVista($formularios, $params, 'Formularios');
        $jvista->accionesFila([
            ['span' => 'fa fa-edit', 'title' => "Editar", 'href' => $this->obtUrl('gestion', ['{clave}', '{modulo}'])],
            ['span' => 'fa fa-picture-o', 'title' => 'Editar Campos', 'href' => $this->obtUrl('gestion', ['{clave}'])],
            ['span' => 'fa fa-trash', 'title' => "Eliminar Formulario", 'href' => $this->obtUrl('eliminar', ['{clave}',]),
                'data-jvista' => 'confirm', 'data-msj' => '<h3>¡Cuidado!</h3>&iquest;Realmente desea eliminar el formulario seleccionado?'],

        ]);
        $jvista->acciones([
            'Nuevo Formulario' => ['href' => $this->obtUrl('gestion')]

        ]);
        $this->data([
            'vista' => $jvista->render()
        ]);

    }

    /**
     * Retorna un arreglo con el listado de formularios existentes en la aplicación.
     */
    private function _obtenerFormularios() {

        $modulos = $this->_conf()->modulos;
        $coleccion = [];
        foreach ($modulos as $modulo) {

            $path = DIR_APP . 'Modulos' . DS . ucwords($modulo) . DS . 'Formularios';

            if (Helpers\Directorios::validar($path)) {
                $formularios = [
                    'formularios' => Helpers\Directorios::listar($path),
                    'modulo' => $modulo,
                    'path' => $path
                ];

                $coleccion[$modulo] = $formularios;
            }

        }
        return $coleccion;

    }

    /**
     * Lee la data del formulario y retorna un arreglo con los valores
     */
    private function _dataVistaFormulario($formulario, $modulo) {

        $data = $this->_dataFormulario($formulario, $modulo);
        if ($data) {
            if (array_key_exists('query', $data)) {

                unset($data['query']);
                return $data;

            }
        }
        return false;
    }

    private function _dataGestion($nombreFormulario, $modulo) {

        $form = $this->_instanciarFormulario($nombreFormulario, $modulo);
        $dataForm = $this->_dataFormulario($nombreFormulario, $modulo);
        Helpers\Debug::imprimir($form, true);

        $campos = [];
        foreach ($form->campos as $id => $campo) {
            $campos[] = $campo->name;
        }
        $dataForm['campos'] = implode(", ",$campos);

        return $dataForm;

    }

    function gestion($id = "", $modulo = "") {

        $dataForm = [];
        $nombreFormulario = "";

        if (!empty($id)) {

            $nombreFormulario = $id . '.json';

            $dataForm = $this->_dataGestion($nombreFormulario, $modulo);
            $titulo = 'Editar <strong>' . $dataForm['nombre'] . '</strong>';

        } else {
            $titulo = 'Crear Nuevo Formulario';
        }


        $form = new Render\Formulario('GestionFormulario', $dataForm);
        $form->boton('principal', 'Guardar y editar campos');
        $form->titulo($titulo);
        $form->campo('modulo')
            ->addOpciones(
                array_merge(
                    ['principal' => 'Principal'],
                    $this->_conf()->modulos
                )
            );

        if ($this->post('btnGestionFormulario') or $this->post('btnCampos')) {

            if ($this->_guardarFormulario($nombreFormulario)) {
                exit("guardado");
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
    function _guardarFormulario($nombreFormulario) {

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

    function eliminar() {

    }


}
