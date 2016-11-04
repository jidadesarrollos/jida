<?php

/**
 * Clase Modelo para s_formularios
 *
 * Clase creada para la transición de Formularios creados con la clase Formulario del Framework
 * en versiones anteriores a la version 0.5
 *
 *
 * @package Aplicacion
 * @category Modelo
 * @version 0.4

*/

namespace Jida\ModelFramework;
class Formulario extends JFormulario{

	protected $tablaBD='s_formularios';
	protected $objetoCampos = 'Jida\CampoFormulario';
	protected $tieneMuchos= [
		'Jida\CampoFormulario'
	];



}//fin clase