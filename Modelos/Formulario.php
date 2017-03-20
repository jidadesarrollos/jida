<?php

/**
 * Clase Modelo para s_formularios
 *
 * @internal Clase creada para la transición de Formularios creados con la clase Formulario del Framework
 * en versiones anteriores a la version 0.5
 *
 *
 * @package Aplicacion
 * @category Modelo
 * @version 0.4

*/

namespace Jida\Modelos;
class Formulario extends JFormulario{
	var $id_form;
	var $nombre_f;
	var $query_f;
	var $clave_primaria_f;
	var $nombre_identificador;
	var $estructura;
	protected $tablaBD='s_formularios';
	protected $objetoCampos = 'Jida\CampoFormulario';
	protected $tieneMuchos= [
		'\Jida\Modelos\CampoFormulario'
	];



}//fin clase