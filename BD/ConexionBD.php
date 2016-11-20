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
    
    /**
     
     * @throws Exception Error de Conexion a la Base de Datos
     */
    public function __construct($conexion="default") {
		if(class_exists('\App\Config\BD')){
			$configuracion = new \App\Config\BD();
			 
			if(property_exists($configuracion, $conexion))
			{
				$this->establecerAtributos($configuracion->{$conexion},$this);
			}
			
		}else{
			throw new Excepcion("No existe el objeto De configuracion de Base de datos", $this->_ce."1");
			
		}       
    }
}