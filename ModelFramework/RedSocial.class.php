<?PHP

/**
 * Modelo para conexion con API de Redes Sociales dentro de la aplicacion
 *
 * @author Felix Tovar
 * @package Framework
 *
 * @category Model
 * @version 0.1 10-10-2017
 */

class RedSocial extends DataModel {

	var $id_red_social;
	var $red_social;
	var $identificador;
	var $access_token;
	var $data;

	protected $tablaBD = "s_redes_sociales";
	protected $pk = "id_red_social";
	protected $unico = ['identificador'];

}
