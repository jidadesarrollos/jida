<?php
/**
 * Clase Modelo
 * @author Julio Rodriguez
 * @package
 * @since 1.4
 * @version
 * @category
*/
namespace Jida\Elementos;
abstract class JElemento{

	protected $nombre;
	protected $descripcion;
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
	abstract function jform();
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
