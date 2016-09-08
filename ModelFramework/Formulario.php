<?php

/**
 * Clase Modelo para s_formularios
 *
 *
 * @package Aplicacion
 * @category Modelo

*/
namespace Jida;

class Formulario extends JFormulario{

	protected $tablaBD='s_formularios';
	protected $objetoCampos = 'Jida\CampoFormulario';
	protected $tieneMuchos= [
		'Jida\CampoFormulario'
	];



}//fin clase