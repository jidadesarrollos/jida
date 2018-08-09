<?php
/**
 * Clase Modelo
 * @author Julio Rodriguez
 * @package
 * @since 1.4
 * @version
 * @category
*/
namespace Jida\Core;
abstract class Elemento{
	/**
	 * Nombre publico del elemento creado
	 * @property string $nombre
	 * @since 0.5
	 */
	protected $nombre;
	/**
	 * Descripción breve del elemento creado
	 * 
	 * Se recomienda que la descripción no exeda los 100 caracteres
	 * @property string $descripcion
	 */
	protected $descripcion;
	/**
	 * Identificador del elemento
	 * 
	 * Usado por el framework. no es público
	 * @property string $id
	 */
	protected $id;

	/**
	 * Renderizacion del Elemento para el FrontEnd
	 *
	 * @method elemento
	 */
	abstract function elemento();
	/**
	 * Formulario del elemento a ser manejado en los paneles administrativos
	 *
	 * @method jform
	 */
	abstract function form();
	abstract function gestion($data);

	function nombre(){
		return $this->nombre;
	}
	function descripcion(){
		return $this->descripcion;
	}

	function id(){
		return $this->id;
	}
}
