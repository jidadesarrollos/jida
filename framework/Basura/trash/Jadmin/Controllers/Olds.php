<?php

namespace Jida\Jadmin\Controllers;

use Jida\Render as Render;
use Jida\Helpers as Helpers;
use Jida\Core\GeneradorCodigo;

class Olds extends JController {

    use GeneradorCodigo\GeneradorArchivo;
    private $controles = [
        1  => "hidden",
        2  => "text",
        3  => "textarea",
        4  => "password",
        5  => "checkbox",
        6  => "radio",
        7  => "select",
        8  => "identificacion",
        9  => "Telefono",
        10 => "Fecha",
        11 => 'Captcha'];
    var $manejoParams = TRUE;

    function index() {
        $this->redireccionar('/jadmin/olds/forms');
    }

    function forms($config = "electron") {
        $metodo = ($config == 'electron') ? 'lista' : 'listaesp';
        $jvista = new Render\JVista('\App\Modelos\Formulario.' . $metodo,
            [
                'titulos' => ["id", 'Formulario']
            ], 'Formulario');
        $jvista->accionesFila([
            // ['span'=>'fa fa-eye','title'=>"Ver Subcategorias",'href'=>$this->obtUrl('subcategorias',['{clave}'])],
            ['span' => 'fa fa-edit', 'title' => "Generar Form", 'href' => '/jadmin/olds/crear-json/{clave}']
        ]);
        $jvista->buscador = ['nombre_f'];
        $this->data('vista', $jvista->obtenerVista());
    }

    function crearJson($id) {

        $form = new \App\Modelos\Formulario($id);

        $formArray = [
            'nombre'         => $form->nombre_f,
            'query'          => $form->query_f,
            'estructura'     => $form->estructura,
            'identificador'  => $form->nombre_identificador,
            'clave_primaria' => $form->clave_primaria_f
        ];
        array_walk($form->CampoFormulario, function (&$key, $value) {
            $key['id'] = $key['id_propiedad'];
            if (!is_null($key['eventos']))
                $key['eventos'] = json_decode("{" . $key['eventos'] . "}");
            if (!is_null($key['control']))
                $key['type'] = $this->controles[$key['control']];
            foreach ($key as $id => $valor) {
                if (is_null($valor)) unset($key[$id]);
            }
            unset($key['id_propiedad']);
            unset($key['id_campo']);
            unset($key['id_form']);
            unset($key['control']);

        });
        $formArray['campos'] = $form->CampoFormulario;

        $json = json_encode($formArray, JSON_PRETTY_PRINT, JSON_UNESCAPED_SLASHES);
        $this
            ->crear(DIR_APP . 'formularios/' . $form->nombre_identificador . ".json")
            ->escribir($json)
            ->cerrar();
        Render\JVista::msj('formulario', 'suceso', 'Formulario <strong>' . $form->nombre_f . '</strong> creado correctamente', $this->urlController());

    }
}
