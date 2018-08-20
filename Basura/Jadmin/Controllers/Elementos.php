<?php
/**
 * Clase Controladora
 * @author Julio Rodriguez
 * @package
 * @version
 * @since 1.4
 * @category Controller
 *
 */

namespace Jida\Jadmin\Controllers;

use Jida\Helpers as Helpers;
use Jida\Modelos as Modelos;

class Elementos extends JController {

    var $layout = "jadmin.tpl.php";
    var $helpers = [
        'Arrays',
        'Cadenas',
        'Debug'
    ];

    function __construct () {

        parent::__construct();
        $this->dv->addJsModulo('/Framework/htdocs/js/jadmin/elementos.js', false);
    }

    function index () {

        global $elementos;
        $elemento = new Modelos\Elemento();

        if (Helpers\Directorios::validar(DIR_APP . "Contenido/elementos.php")) {
            include_once 'Contenido/elementos.php';
        }
        else {
            //Helpers\Debug::string(DIR_APP."Contenido/elementos.php");
        }

        $tema = JD('TEMA_APP');

        $listaElementos = [
            'jida' => [
                'namespace' => '\Jida\Elementos\\',
                'elementos' => []
            ]
        ];
        Helpers\Directorios::listarDirectoriosRuta(DIR_FRAMEWORK . 'Elementos',
                                                   $listaElementos['jida']['elementos'],
                                                   '/.php/');
        if (!empty($tema)) {
            $listaElementos[$tema] = [
                'namespace' => '\App\Layout\\' . $tema . '\\Elementos\\',
                'elementos' => []
            ];
            Helpers\Directorios::listarDirectoriosRuta(DIR_APP . 'Layout/' . $tema . '/Elementos/',
                                                       $listaElementos[$tema]['elementos'],
                                                       '/php/');
        }
        #Helpers\Debug::imprimir(JD('TEMA_APP'),$listaElementos,true);
        $listadoFinal = [];

        foreach ($listaElementos as $modulo => $data) {

            foreach ($data['elementos'] as $key => $elemento) {
                $objeto = $data['namespace'] . str_replace(".php", "", $elemento);

                $listadoFinal[$modulo][] = new $objeto();
            }
        }
        $elementosCargados = [];
        // $data = $elemento
        // ->consulta()
        // ->in($this->Arrays->obtenerKey('id',$elementos['areas']),'area')
        // ->obt();
        //
        // foreach ($data as $key => $elemento) {
        // $elementosCargados[$elemento['area']][] = $elemento;
        // }
        $this->data([
                        'areas'             => $elementos['areas'],
                        'elementos'         => $listadoFinal,
                        'elementosCargados' => $elementosCargados
                    ]);
        $this->vista = 'listaElementos';

    }

    function guardar () {

        if ($this->solicitudAjax() and $this->post('btnGuardarElemento')) {
            $eleUsado = new Elementos\Elemento();

            $elementoName = str_replace(".", "\\", $this->post('elemento'));
            if ($this->post('elemento') and class_exists($elementoName)) {

                $elemento = new $elementoName;

                return $this->respuestaJson($elemento->gestion($this->post()));

                // $eleUsado->identificador=$this->post('identificador');
                // $eleUsado->area=$this->post('area');
                // $eleUsado->data=json_encode($post);
                // if($eleUsado->salvar()) $this->respuestaJson(['ejecutado'=>true]);

            }
            $this->respuestaJson(['ejecutado' => false]);

        }
    }
}



