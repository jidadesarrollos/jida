<?PHP

/**
 * Modelo para conexion con API de Redes Sociales dentro de la aplicacion
 *
 * @author Felix Tovar
 * @package Framework
 *
 * @category Modelo
 * @version 0.1 10-2017
 */

namespace Jida\Modelos;

use Jida\BD as BD;

class RedSocial extends BD\DataModel {

    var $id_red_social;
    var $red_social;
    var $identificador;
    var $access_token;
    var $data;

    protected $tablaBD = "s_redes_sociales";
    protected $pk = "id_red_social";
    protected $unico = ['identificador'];

}
