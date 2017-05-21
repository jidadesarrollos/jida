<?php
/**
 * Clase Conexion BD
 *
 * @internal Define y establece una Conexion a la Base de Datos
 *
 * @category    framework
 * @package     BD
 * @author      Julio Rodriguez <jirodriguez@sundecop.gob.ve>
 * @license     http://www.gnu.org/copyleft/gpl.html    GNU General Public License
 * @version     0.1 - 09/09/2013
 *
 */

namespace Jida\BD;
use \Exception as Excepcion;
use Jida\Helpers as Helpers;
class ConexionBD {
	use \Jida\Core\ObjetoManager;
	private $_ce="003";
    protected $bd;
    /**
     *
     * @var unknown
     */
    protected $idConexion;

    /**
     *
     * @var unknown
     */
    protected $usuario;

    /**
     *
     * @var unknown
     */
    protected $clave;

    /**
     *
     * @var unknown
     */
    protected $servidor;

    /**
     *
     * @var unknown
     */
    protected $manejadorBD;
	/**
	 * Nombre del manejador de base de datos
	 * @param $manejador
	 */
	protected $manejador;

    /**
     *
     * @var unknown
     */
    protected $conexionID;


    /**
     * Define el puerto de conexión a base de datos
     * @var string $puerto
     * @access protected
     */
    protected $puerto;

	private $_conexion;
	protected $_clase;
    /**

     * @throws Exception Error de Conexion a la Base de Datos
     */
    public function __construct($conexion="default",$clase="") {
    	$this->_clase = $clase;
		if(class_exists('\App\Config\BD')){
			$this->_conexion = $conexion;

			$this->_establecerConfiguracion();

		}else{
			throw new Excepcion("No existe el objeto De configuracion de Base de datos", $this->_ce."1");

		}
    }
	/**
	 * Define una nueva configuracion para la base de datos
	 *
	 * @internal
	 * @method cambiarBD
	 * @param string $conexion Nombre de la conexion a crear debe ser una propiedad
	 * del objeto \App\Config\BD
	 * @see \App\Config\BD
	 *
	 *
	 */
	function cambiarBD($conexion){
		$this->_conexion = $conexion;
		$this->_establecerConfiguracion();
	}
	/**
	 * Establece los atributos de la conexion a partir de la propiedad del objeto BD
	 * @method _establecerConfiguracion
	 */
	private function _establecerConfiguracion(){
		$configuracion = new \App\Config\BD();
		 $this->manejador = $configuracion->manejador;
		if(property_exists($configuracion, $this->_conexion))
		{
			$this->establecerAtributos($configuracion->{$this->_conexion},$this);
		}
	}
}